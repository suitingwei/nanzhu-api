<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Models\Movie;
use App\Models\ReceivePower;
use App\Models\ReferencePlan;
use App\Models\ReferencePlanPower;
use App\User;
use Illuminate\Http\Request;

class ReferencePlansController extends BaseController
{
    /**
     * 参考大计划列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(Request $request)
    {
        $user  = User::find($request->input('user_id'));
        $movie = Movie::find($request->input('movie_id'));

        //如果是统筹,直接进行分页,所有这个剧组的参考大计划
        $isTongchou = $user->isTongChouInMovie($movie->FID);
        $order      = $request->input('order', 'asc');

        if (!$isTongchou) {
            $plansPaginator = $this->getNotTongchouNoticeDates($movie->FID, $user->FID, $order);
        } else {
            if ($order == 'desc') {
                $plansPaginator = $movie->referencePlans()->orderBy('created_at', 'desc')->paginate(15);
            } else {
                $plansPaginator = $movie->referencePlans()->orderBy('created_at')->paginate(15);
            }
        }

        $plans = $this->formatJson($user, $isTongchou, $plansPaginator, $request, $order);


        return $this->responseSuccess('操作成功', ['plans' => $plans]);
    }

    /**
     * @param $movieId
     * @param $userId
     *
     * @param $order
     *
     * @return mixed
     * @internal param $noticeType
     */
    private function getNotTongchouNoticeDates($movieId, $userId, $order)
    {
        return ReferencePlan::selectRaw("reference_plans.*")
                            ->leftjoin("messages", "messages.plan_id", "=", "reference_plans.id")
                            ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                            ->whereRaw("messages.type = 'PLAN' and  messages.movie_id = " . $movieId)
                            ->where('messages.is_undo', 0)
                            ->where("message_receivers.receiver_id", $userId)
                            ->orderby("reference_plans.created_at", $order)
                            ->paginate(15);
    }

    /**
     * 格式化数据
     * 添加是否可以撤销
     *
     * @param User    $user
     * @param         $isTongChou
     * @param         $plansPaginator
     * @param Request $request
     *
     * @return
     * @internal param $userId
     * @internal param $canSeeReceivers
     */
    private function formatJson(User $user, $isTongChou, $plansPaginator, Request $request, $order)
    {
        $canSeeReceivers = $user->hadAssignedPowerInMovie($request->input('movie_id'), ReceivePower::class);

        //这里需要对plans里的分页数据进行逆序
        //因为整体是遵循聊天记录的,比如获取最新的5条记录, order by created_at desc,这样是最新的在最上面
        //但是返回给前端的时候,需要把最新的放在最下面
        $results = $plansPaginator->toArray();

        $resultData = [];
        foreach ($plansPaginator as &$plan) {
            $plan->can_redo          = $isTongChou &&
                                       $plan->isSend() &&
                                       !Message::isMessageUndo($plan->id);
            $plan->can_see_receivers = $canSeeReceivers;
            $plan->status            = $plan->getStatusForUser($user->FID);
            $plan->file_url          = $plan->getFileUrl($user->FID);
            $plan->h5_receivers_url  = $request->root() . '/mobile/plans/' . $plan->id . '/receivers?movie_id=' . $request->input('movie_id');

            $order == 'desc' ? array_push($resultData, $plan) : array_unshift($resultData, $plan);
        }

        $results['data'] = $resultData;
        return $results;
    }

    /**
     * 发送大计划
     *
     * @param         $planId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function send($planId, Request $request)
    {
        $plan = ReferencePlan::find($planId);

        $planMessage = $this->createMessage($request, $plan);

        $planMessage->push();

        return $this->responseSuccess();
    }

    /**
     * @param Request $request
     *
     * @param         $plan
     *
     * @return Message
     */
    private function createMessage(Request $request, ReferencePlan $plan)
    {
        $movie = Movie::find($request->input('movie_id'));

        if ($request->has('scoped_ids') && $request->input('scoped_ids')) {
            $noticeUserIds = implode(',', array_unique(explode(',', $request->input('scoped_ids'))));
        } else {
            $noticeUserIds = implode(',', $movie->allUsersWithPower(ReferencePlanPower::class)->pluck('FID')->all());
        }

        $plan->rememberReceivers($noticeUserIds);

        return Message::create([
            'type'        => Message::TYPE_PLAN,
            'scope'       => 1,
            "scope_ids"   => $noticeUserIds,
            'title'       => $movie->FNAME . ':您有新的参考大计划请接收。',
            'content'     => $plan->file_name,
            'filename'    => $plan->file_name,
            'plan_id'     => $plan->id,
            'from'        => $request->input('user_id'),
            'notice_type' => '',
            'movie_id'    => $request->input('movie_id'),
            'uri'         => $plan->getFileUrl($request->input('user_id'))
        ]);
    }

    /**
     * 撤回发送的大计划
     *
     * @param $planId
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function undo($planId)
    {
        $plan = ReferencePlan::find($planId);

        foreach ($plan->messages as $message) {
            $message->undo();
        }

        return $this->responseSuccess();
    }

    /**
     * 通告单接受详情
     *
     * @param $planId
     *
     * @return array
     * @internal param $noticeId
     * @internal param Request $request
     *
     */
    public function receivers($planId)
    {
        $allReceivers = [];

        $messages = ReferencePlan::find($planId)->messages->first();

        if (!$messages) {
            return $this->responseSuccess('操作成功', []);
        }

        $receivers = $messages->receivers;

        foreach ($receivers as $messageReceiver) {
            $receiverUser = $messageReceiver->user;

            $tempData = [
                'group_name'        => $receiverUser->groupNamesInMovie($messages->movie_id),
                'position'          => $receiverUser->positionInMovie($messages->movie_id),
                'user_name'         => $receiverUser->FNAME,
                'received_at'       => $messageReceiver->updated_at,
                'short_received_at' => $messageReceiver->updated_at->toTimeString(),
                'share_phones'      => $receiverUser->isSharePhonesInMovieOpened($messages->movie_id)
                    ? $receiverUser->sharePhonesInMovie($messages->movie_id)->lists('FPHONE')->all()
                    : [],
                'is_leader'         => $receiverUser->isLeaderInMovie($messages->movie_id),
                'is_read'           => $messageReceiver->hadRead()
            ];

            if ($messageReceiver->hadRead()) {
                array_push($allReceivers, $tempData);
            } else {
                array_unshift($allReceivers, $tempData);
            }
        }

        return $this->responseSuccess('操作成功', [
            'receivers' => $allReceivers,
        ]);

    }

    /**
     * 大计划择接收人
     *
     * @param         $planId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function choose($planId, Request $request)
    {
        $plan    = ReferencePlan::find($planId);
        $movieId = $plan->movie->FID;
        $users   = $plan->movie->allUsersWithPower(ReferencePlanPower::class)->map(function ($user) use ($movieId) {
            $userInfoObj              = $user->formatBasicClass()
                                             ->withPositionInMovie($movieId)
                                             ->withGroupNamesInMovie($movieId)
                                             ->get();
            $userInfoObj->is_selected = true;

            return $userInfoObj;
        });

        return $this->responseSuccess('操作成功', ['users' => $users]);
    }

    /**
     * 大计划详情
     *
     * @param         $planId
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($planId, Request $request)
    {
        $userId = $this->current_user($request);

        ReferencePlan::userReadMessage($planId, $userId);

        return response()->json(["msg" => "ok"]);
    }
}
