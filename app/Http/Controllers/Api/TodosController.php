<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use App\User;
use App\Utils\OssUtil;
use Illuminate\Http\Request;

class TodosController extends BaseController
{
    public function show(Request $request, $id)
    {
        $todo          = Todo::find($id);
        $currentUserId = $this->current_user($request);
        $user          = User::find($currentUserId);

        if (!$todo) {
            return response()->json(['todo' => null]);
        }

        if ($todo->notShared()) {
            $todo->timestamps = false;
            $todo->update(['is_read' => 1]);
        }
        $todo->addNewReadUserId($currentUserId);

        //temporarily replace the https with http
        $todo->content = str_replace('https://', 'http://', $todo->content);
        //只是代表当前用户的is_read是读取的,不要去修改保存数据库的is_read字段
        $todo->is_read = 1;

        $result                 = $todo->toArray();
        $result['is_share']     = (boolean)$todo->share_group;
        $result['is_out_movie'] = $user ? !(boolean)$user->isInMovie($todo->movie_id) : false;

        return response()->json(['todo' => $result]);
    }

    public function index(Request $request)
    {
		\Log::info('-----request todo index');
        $user  = User::find($userId = $request->input('user_id'));
        $date  = $request->get("date");
        $q     = $request->get("q");
        $todos = new Todo;

        if ($q) {
            $todos = $todos::where("title", "like", "%{$q}%")->canSee($userId)->get()->map(function ($todo) use ($user) {
                return array_merge($todo->toArray(), [
                    'is_read'      => (int)$todo->isReadByUser($user->FID),
                    'is_share'     => (boolean)$todo->share_group,
                    'is_out_movie' => !(boolean)$user->isInMovie($todo->movie_id),
                ]);
            });
            return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $todos]);
        }

        if ($userId) {
            $todos = $todos->canSee($userId);
        }

        if ($date) {
            $todos = $todos->where('date', $date);
        }

        $todos = $todos->get()->map(function ($todo) use ($user) {
            return array_merge($todo->toArray(), [
                'is_read'      => (int)$todo->isReadByUser($user->FID),
                'is_share'     => (boolean)$todo->share_group,
                'is_out_movie' => !(boolean)$user->isInMovie($todo->movie_id),
            ]);
        });

        $resultData = $this->format_json($todos);

        return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $resultData]);

    }

    /**
     * @param $todos
     *
     * @return array
     */
    public function format_json($todos)
    {
        $arrs = [];
        $s    = [];
        foreach ($todos as $item) {
            $arrs[$item['date']] = [];
        }

        foreach ($todos as $todo) {
            if (array_key_exists($todo['date'], $arrs)) {
                $arrs[$todo['date']][] = $todo;
            }
        }

        foreach ($arrs as $key => $todosInDay) {
            $isTodayAllRead = true;
            foreach ($todosInDay as $todo) {
                $isTodayAllRead = $isTodayAllRead && $todo['is_read'];
            }
            $s[] = ["date" => $key, "data" => $todosInDay, 'is_all_read' => $isTodayAllRead];
        }
        return $s;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = User::find($data['user_id']);
        $todo = Todo::create($data);
        $this->snapVideoShoot($todo);
        if ($todo->shared() && $data['movie_id'] && $user) {
            $this->updateTodoShareUserIds($user, $data, $todo);
        }
        $todo->record();
        return response()->json(["ret" => 0, "msg" => "保存成功"]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $todo = Todo::find($id);
        $user = User::find($data['user_id']);

//        if ($todo->movie->shootEnded() || !$user->isInMovie($todo->movie_id) || !$todo) {
//            return $this->responseFail('不能编辑已经杀青或者退出的剧组共享备忘录');
//        }

        $todo->update($data);
        $this->snapVideoShoot($todo);

        if ($todo->shared() && $user) {
            $this->updateTodoShareUserIds($user, $data, $todo);
            $todo->update(['read_ids' => '']);
        } else {
            $todo->update(['is_read' => 0]);
        }

        $todo->record();
        return response()->json(["ret" => 0, "msg" => "保存成功"]);
    }

    /**
     * Delete the todolist.
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $todo = Todo::find($id);

        \Log::info('destroying todo' . $todo);
        //From version 3.7,user cannot delete the shared todolist,they can only
        //remove themselves from the shareids.
        if (!$todo || !($userId = $request->input('user_id'))) {
            return response()->json(["ret" => 0, "msg" => "删除成功"]);
        }

        //If the todolists is only in user's scope,she can totally delete it.
        if ($todo->notShared()) {
            $todo->delete();
            return response()->json(["ret" => 0, "msg" => "删除成功"]);
        }

        //If the todolists has been shared,it cannot be deleted anymore. Besides,
        //if the movie has been shootended, or the user exit the movie,
        //she can only remove themselves from the share ids.
        $user = User::find($userId);
        if ($todo->movie->shootEnded() || !$user->isInMovie($todo->movie_id)) {
            $todo->removeUserFromShared($userId);
            return response()->json(["ret" => 0, "msg" => "删除成功"]);
        }

        //If the movie is still shooting,or the user is still in the movie,
        //tell her that the todolist cannot be deleted.
        return response()->json(["ret" => -99, "msg" => "剧组未杀青前或用户仍在剧组时,共享备忘录不允许删除"]);
    }

    /**
     * @param $user
     * @param $data
     * @param $todo
     */
    private function updateTodoShareUserIds(User $user, $data, $todo)
    {
        $allUserIdsShouldShared = [];
        $groups                 = $user->groupsInMovie($data['movie_id']);

        foreach ($groups as $group) {
            //If the user have chosen the group, he wish to share,we should only use the chosen group.
            if (isset($data['group_id']) && $group->FID != $data['group_id']) {
                continue;
            }

            $allUserIdsShouldShared = array_merge($allUserIdsShouldShared,
                $group->members()->where('FUSER', '!=', $user->FID)->selectRaw('distinct FUSER')->lists('FUSER')->all()
            );
        }

        $allUserIdsShouldShared = array_unique($allUserIdsShouldShared, SORT_NUMERIC);

        $todo->update(['share_ids' => implode(',', $allUserIdsShouldShared)]);
    }

    /**
     * Snap the shoot for the possible video.
     *
     * @param Todo $todo
     */
    private function snapVideoShoot(Todo $todo)
    {
        if (!preg_match_all('/(\[video,(\d*\.?\d*\,){2}(.+?)\])/', $todo->content, $matches)) {
            return;
        }

        $ossUtil = new OssUtil;

        //Snap all videos
        foreach ($matches[3] as $videoUrl) {
            $ossUtil->snapNanzhuVideosBucket($videoUrl);
        }
    }

    /**
     * All update records in the daily report.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRecords($id)
    {
        $todo = Todo::find($id);

        $records = $todo->records()->orderBy('todo_records.created_at', 'desc')->paginate(20);

        return $this->responseSuccess('success', [
            'records' => $records->toArray()['data']
        ]);
    }
}
