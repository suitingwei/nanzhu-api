<?php

namespace App\Http\Controllers\Api;

use App\Formatters\GroupUserFeedbackFormatter;
use App\Models\GroupUserFeedBack;
use App\Models\Movie;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class GroupUserFeedbacksController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user      = User::find($userId = $request->input('user_id'));
        $movie     = Movie::find($movieId = $request->input('movie_id'));
        $feedbacks = $this->getFeedbackPaginator($movieId,
            $userId)->map(GroupUserFeedbackFormatter::getListFormatter($user));

        return $this->responseSuccess('操作成功', ['feedbacks' => $feedbacks, 'can_create' => false]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $movie = Movie::find($movieId = $request->input('movie_id'));
        $user  = User::find($userId = $request->input('user_id'));

        if (!$movie->is_groupuser_feedback_open) {
            return $this->responseFail('该剧组不允许组员进行反馈');
        }

        if (!$user->isInMovie($movieId)) {
            return $this->responseFail('你已被移除本组');
        }

        $groupUserFeedback = GroupUserFeedBack::create([
            'movie_id' => $movieId,
            'user_id'  => $userId,
            'content'  => $request->input('content'),
        ]);

        $groupUserFeedback->pushMessages();

        return $this->responseSuccess();
    }

    /**
     * @param         $feedbackId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($feedbackId, Request $request)
    {
        $userId   = $request->input('user_id');
        $feedback = GroupUserFeedBack::find($feedbackId);

        GroupUserFeedBack::userReadMessage($feedbackId, $userId);

        $formatter = GroupUserFeedbackFormatter::getShowFormatter();

        return $this->responseSuccess('成功', ['feedback' => $formatter($feedback)]);
    }

    /**
     * 获取日报表分页
     *
     * @param $movieId
     * @param $userId
     *
     * @return Collection
     */
    private function getFeedbackPaginator($movieId, $userId)
    {
        return GroupUserFeedBack::selectRaw("group_user_feed_backs.*")
                                ->leftjoin("messages", "messages.groupuser_feedback_id", "=",
                                    "group_user_feed_backs.id")
                                ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                                ->whereRaw("messages.type = 'GROUPUSER_FEEDBACK' and  messages.movie_id = {$movieId}")
                                ->where('messages.is_undo', 0)
                                ->where("message_receivers.receiver_id", $userId)
                                ->orderby("group_user_feed_backs.created_at", "desc")
                                ->paginate(20);
    }
}
