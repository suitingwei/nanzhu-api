<?php

namespace App\Managers\Statistics;


use App\Models\DailyReportPower;
use App\Models\GroupUserFeedBackPower;
use App\Models\Message;
use App\Models\Notice;
use App\Models\PreviousProspectPower;
use App\Models\ReferencePlanPower;
use App\User;
use Illuminate\Support\Collection;

class UserStatManager
{
    public $user;

    /**
     * UserStatManager constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 是否可以查看预备通告单
     *
     * @param $movieId
     *
     * @return bool
     */
    public function canSeePrepareNoticeMenuInMovie($movieId)
    {
        return Message::leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                      ->whereRaw("messages.type = 'notice' and messages.notice_type = 20  and messages.movie_id = " . $movieId)
                      ->where("message_receivers.receiver_id", $this->user->FID)
                      ->count();
    }

    /**
     * 某一个类型消息的未读数目
     *
     * @param      $movieIds
     * @param null $type
     *
     * @return int
     */
    public function unReadCertainTypeMessageCountInMovie($movieIds, $type = null)
    {
        $movieIds = (array)$movieIds;

        $messagesBuilder = Message::leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                                  ->whereRaw("messages.scope_ids like '%{$this->user->FID}%'")
                                  ->where("message_receivers.receiver_id", $this->user->FID)
                                  ->whereIn('messages.movie_id', $movieIds)
                                  ->where('messages.is_undo', 0)
                                  ->orderby("messages.id", "desc")
                                  ->where("message_receivers.is_read", 0);
        if ($type) {
            $messagesBuilder = $messagesBuilder->where('type', $type);
        }

        return $messagesBuilder->count();
    }

    /**
     * 未读通告单(每日,预备)数量
     *
     * @param      $movieIds
     * @param null $type
     *
     * @return mixed
     */
    public function unReadNoticeMessageCountInMovie($movieIds, $type = null)
    {
        $movieIds = (array)$movieIds;

        $messages = Message::leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                           ->whereRaw("messages.type = 'notice'")
                           ->whereIn('messages.movie_id', $movieIds)
                           ->where("message_receivers.receiver_id", $this->user->FID)
                           ->where('messages.is_undo', 0)
                           ->orderby("messages.id", "desc")
                           ->where("message_receivers.is_read", 0);
        if ($type) {
            $messages = $messages->whereIn('notice_type', (array)$type);
        }

        return $messages->count();
    }

    /**
     * 用户所有的工作台未读数(不区分剧组)
     * ------------------------------
     * 1. 预备通告单(如果可以看)
     * 2. 每日通告单
     * 3. 剧组通知
     * 4. 剧本扉页
     *
     * @param Collection|null $userJoinedNotEndMovies
     *
     * @return int|mixed
     */
    public function allUnReadMessageCountAmongMovies(Collection $userJoinedNotEndMovies = null)
    {
        $allJoinedNotEndMovies   = $userJoinedNotEndMovies ?: $this->user->joinedNotEndMovies();
        $allJoinedNotEndMovieIds = $allJoinedNotEndMovies->pluck('FID')->all();

        return array_sum(array_map(function ($movieId) {
            return $this->allUnReadMessagesCountInMovie($movieId);
        }, $allJoinedNotEndMovieIds));
    }

    /**
     * All un-read messages count in a certain movie.
     *
     * @param $movieId
     *
     * @return int
     */
    public function allUnReadMessagesCountInMovie($movieId)
    {
        $totalUnReadCount = 0;

        $totalUnReadCount += $this->unReadNoticeMessageCountInMovie($movieId, Notice::TYPE_DAILY);

        $totalUnReadCount += $this->unReadCertainTypeMessageCountInMovie($movieId, Message::TYPE_JUZU);

        $totalUnReadCount += $this->unReadCertainTypeMessageCountInMovie($movieId, Message::TYPE_BLOG);

        if ($this->user->isTongChouInMovie($movieId) || $this->canSeePrepareNoticeMenuInMovie($movieId)) {
            $totalUnReadCount += $this->unReadNoticeMessageCountInMovie($movieId, Notice::TYPE_PREPARE);
        }

        //Reference plan's unread messages count.
        if ($this->user->hadAssignedPowerInMovie($movieId, ReferencePlanPower::class)) {
            $totalUnReadCount += $this->unReadCertainTypeMessageCountInMovie($movieId, Message::TYPE_PLAN);
        }

        //Daily report's unread messages count.
        if ($this->user->hadAssignedPowerInMovie($movieId, DailyReportPower::class)) {
            $totalUnReadCount += $this->unReadCertainTypeMessageCountInMovie($movieId, Message::TYPE_DAILY_REPORT);
        }

        //Groupuser feedback un read messages count.
        if ($this->user->hadAssignedPowerInMovie($movieId, GroupUserFeedBackPower::class)) {
            $totalUnReadCount += $this->unReadCertainTypeMessageCountInMovie($movieId,
                Message::TYPE_GROUPUSER_FEEDBACK);
        }

        //Daily report's unread messages count.
        if ($this->user->hadAssignedPowerInMovie($movieId, PreviousProspectPower::class)) {
            $totalUnReadCount += $this->unReadCertainTypeMessageCountInMovie($movieId, Message::TYPE_PREVIOUS_PROSPECT);
        }

        return $totalUnReadCount;
    }

}