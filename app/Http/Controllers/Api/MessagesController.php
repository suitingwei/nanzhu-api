<?php

namespace App\Http\Controllers\Api;

use App\Formatters\MessageFormatter;
use App\Models\Message;
use App\Models\MessageReceiver;
use App\Models\Movie;
use App\Models\Picture;
use App\Models\PushRecord;
use App\Models\ReceivePower;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class MessagesController extends BaseController
{
    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $current_user_id = $request->get("current_user_id");
        MessageReceiver::where("message_id", $id)->where("receiver_id", $current_user_id)->update(["is_read" => 1]);
        return response()->json(["msg" => "操作成功", "ret" => 0]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $files = $request->file("pic_url");

        $message = Message::create($data);
        if ($message->type == "BLOG" || $message->type == "JUZU") {
            if ($files) {
                foreach ($files as $key => $file) {
                    if ($file) {
                        $picture             = new Picture;
                        $picture->url        = Picture::upload("pictures/" . $message->id, $file);
                        $picture->message_id = $message->id;
                        $picture->save();
                    }
                }
            }
            $uri = $request->root() . "/mobile/messages/" . $message->id;
        }
        if ($message->type == "SYSTEM") {
            //$uri = "nanzhu://message?id=".$message->id;

            $uri = $request->root() . "/mobile/messages/" . $message->id;
        }
        if ($message->type == "NOTICE") {
            $uri = $message->uri;
        }
        $extra        = ["uri" => $uri];
        $message->uri = $uri;
        $message->save();
        if ($message->scope == 1) {

            //$uri = "nanzhu://message?id=".$message->id;

            foreach (explode(",", $data["scope_ids"]) as $user_id) {
                if ($user_id) {
                    $receiver              = new MessageReceiver;
                    $receiver->receiver_id = $user_id;
                    $receiver->message_id  = $message->id;
                    $receiver->is_read     = 0;
                    $receiver->save();
                    $user = User::where("FID", $user_id)->first();
                    if ($user) {
                        if ($user->FALIYUNTOKEN) {
                            //Log::info($user->FALIYUNTOKEN);
                            PushRecord::send($user->FALIYUNTOKEN, $message->title, $message->content, $message->title,
                                $extra, false);
                        }
                    }
                }
            }
        } else {

            //PushRecord::send("",$message->title,$message->content,$message->title,$extra,true);
        }

        return response()->json(["ret" => 0, "msg" => "发送成功"]);
    }

    /**
     * 剧组通知index.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $movieId     = $request->input("movie_id");
        $userId      = $request->input('user_id');
        $messageType = strtoupper($request->input('type'));
        $user        = User::find($userId);

        //接受详情权限,因为都是一个剧组,所以查询一次做参数穿进去
        $canSeeReceivers = $user->hadAssignedPowerInMovie($movieId, ReceivePower::class);

        //获取剧组通知剧本扉页paginator
        $messages = $this->getPaginatedMessages($userId, $movieId, $messageType);

        //是否能创建新的剧组通知是全局字段,所以放在了单独的外面
        $results['can_create'] = $user->canOperateMovieJuzuAndFeiye($movieId);

        //使用回调函数格式化返回数据,同时按照日期进行分组
        $results['messages'] = array_map(MessageFormatter::getIndexListFormatter($user, $canSeeReceivers),
            $messages->items());

        return $this->responseSuccess('操作成功', ['results' => $results]);
    }


    /**
     * @param Request $request
     * @param         $messageId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showDetail(Request $request, $messageId)
    {
        $userId  = $request->input('user_id');
        $message = Message::find($messageId);

        $message->receivers()->where("receiver_id", $userId)->update(["is_read" => 1]);

        $author = $message->author;
        $result = [
            'id'         => $message->id,
            'title'      => $message->title,
            'content'    => $message->content,
            'pictures'   => $message->pictures(),
            'created_at' => $message->created_at,
            'author'     => $author->FNAME,
            'position'   => $author->groupNamesInMovie($message->movie_id)
        ];

        //目前剧本扉页可以上传文件,所以会有单独的文件url
        if ($message->isBlog()) {
            $result['files'] = $message->files;
        }

        return $this->responseSuccess('操作成功', ['result' => $result]);
    }

    /**
     * 创建新的剧组通知剧本扉页
     * --------------------------------------
     * 需要的参数:
     * 1. movie_id
     * 2. user_id
     * 3.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNew(Request $request)
    {
        $this->createNewMessage($request);

        return $this->responseSuccess();
    }

    /**
     * 不在使用message model里的新建方法,那个耦合太严重
     * 兼容了旧版本的h5上传,太麻烦
     *
     * @param Request $request
     *
     */
    private function createNewMessage(Request $request)
    {
        $movie                       = Movie::find($request->input('movie_id'));
        $newMessageData['scope']     = Message::SCOPE_SOME_BODY;
        $newMessageData["scope_ids"] = implode(',', $movie->allUserIds());
        $newMessageData['title']     = "{$movie->FNAME}:{$request->input('title')}";
        $newMessageData['type']      = strtoupper($request->input('type'));
        $newMessageData['from']      = $request->input('user_id');
        $newMessageData['movie_id']  = $request->input('movie_id');
        $newMessageData['content']   = $request->input('content');

        $message = Message::create($newMessageData);

        //创建通知的图片
        $this->uploadAndSavePictures($request, $message);

        //更新push的uri,因为涉及到需要messageId,所以只能在创建完之后更新,为了兼容其他非push消息,也没有使用model event
        $message->updatePushUri();

        //创建消息接受者,发送push
        $message->push();
    }

    /**
     * 保存剧组通知,剧本扉页图片
     *
     * @param Request $request
     * @param Message $message
     */
    private function uploadAndSavePictures(Request $request, Message $message)
    {
        if ($imageUrls = $request->input('img_url')) {

            foreach (explode(',', $imageUrls) as $imageUrl) {
                Picture::create([
                    'url'        => $imageUrl,
                    'message_id' => $message->id
                ]);
            }
        }
    }

    /**
     * @param $userId
     * @param $movieId
     *
     * @param $type
     *
     * @return LengthAwarePaginator
     */
    private function getPaginatedMessages($userId, $movieId, $type)
    {
        return Message::select("messages.*", "message_receivers.is_read as r_is_read")
                      ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                      ->where('messages.type', $type)
                      ->where('messages.scope_ids', 'like', "%{$userId}%")
                      ->where("message_receivers.receiver_id", $userId)
                      ->where('messages.movie_id', $movieId)
                      ->orderby("id", 'desc')
                      ->groupby("messages.id")
                      ->paginate(20);
    }

    /**
     * 撤销发送
     *
     * @param         $messageId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function undo($messageId, Request $request)
    {
        $message = Message::find($messageId);

        $message->undo($request->input('user_id'));

        return $this->responseSuccess();
    }

}
