<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Models\Movie;
use App\Models\Notice;
use App\Models\NoticeExcel;
use App\Models\ReceivePower;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


/**
 * Class NoticesController
 * @package App\Http\Controllers\Mobile
 */
class NoticesController extends BaseController
{
    /**
     * @param Request $request
     * @param         $notice_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $notice_id)
    {
        $userId = $this->current_user($request);

        NoticeExcel::userReadMessage($request->input('excel_id'), $userId);

        return response()->json(["msg" => "ok"]);
    }

    /**
     * 每日通告单
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function daily(Request $request)
    {
        $userId  = $request->input("user_id");
        $movieId = $request->input("movie_id");
        $user    = User::find($userId);

        $isTongChou = $user->isTongChouInMovie($movieId);

        //1.查询用户能查看的通告单日期分页
        $noticeDates = $this->getNoticesDatesPaged($movieId, $userId, $isTongChou, Notice::TYPE_DAILY, $request);

        //2.判断用户是否可以查看接受详情
        $canSeeReceivers = $user->hadAssignedPowerInMovie($movieId, ReceivePower::class);

        //3.查询要显示的通告单
        $results = [];

        foreach ($noticeDates as $noticeDate) {
            $noticeData = $this->getNoticeFiles($movieId, $userId, Notice::TYPE_DAILY, $isTongChou, $noticeDate,
                $canSeeReceivers);

            //有时候files会是空,因为上传失败,或者被撤销
            if (count($noticeData['files'])) {
                $results[] = $noticeData;
            }
        }

        //4. 返回数据
        return $this->responseSuccess('操作成功', ['results' => $results]);
    }

    /**
     * 预备通告单
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function prepare(Request $request)
    {
        $userId  = $request->input("user_id");
        $movieId = $request->input("movie_id");
        $user    = User::find($userId);

        $isTongChou = $user->isTongChouInMovie($movieId);

        //1.查看用户可以查看的通告单日期分页
        $noticeDates = $this->getNoticesDatesPaged($movieId, $userId, $isTongChou, Notice::TYPE_PREPARE, $request);

        //2.判断用户是否可以查看接受详情
        $canSeeReceivers = $user->hadAssignedPowerInMovie($movieId, ReceivePower::class);

        //3.查询要显示的通告单
        $results = [];

        foreach ($noticeDates as $noticeDate) {
            $noticeData = $this->getNoticeFiles($movieId, $userId, Notice::TYPE_PREPARE,
                $isTongChou, $noticeDate, $canSeeReceivers);

            //有时候files会是空,因为上传失败,或者被撤销
            if (count($noticeData['files'])) {
                $results[] = $noticeData;
            }
        }

        return $this->responseSuccess('操作成功', ['results' => $results]);
    }


    /**
     * @param $notice
     * @param $noticeFiles
     * @param $isTongChou
     * @param $canSeeReceivers
     * @param $seeDate
     *
     *
     * @return array
     */
    private function formatReturnData($notice, $noticeFiles, $userId, $isTongChou, $canSeeReceivers, $seeDate)
    {
        $returnData['is_tongchou'] = $isTongChou;
        $returnData['date']        = $seeDate;
        $returnData['notice']      = $notice;
        if (count($noticeFiles) > 0) {
            foreach ($noticeFiles as $key => &$noticeFile) {
                //隐藏relationship的转化,导致数据变慢
                $noticeFile->makeHidden(['messages', 'notice']);
                $noticeFile->status            = $noticeFile->getStatusForUser($userId);
                $noticeFile->file_url          = $noticeFile->getFileUrl($userId);
                $noticeFile->can_redo          = $isTongChou &&
                                                 $noticeFile->is_send() &&
                                                 !Message::is_undo($notice->FID, $noticeFile->FID);
                $noticeFile->can_see_receivers = $canSeeReceivers;
                $noticeFile->h5_receivers_url  = $noticeFile->getH5ReceiversUrl();
            }
        }
        $returnData['files'] = $noticeFiles;
        return $returnData;
    }


    /**
     * 发送每日通告单
     *
     * @param         $noticeFileId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNoticeFile($noticeFileId, Request $request)
    {
        $noticeFile = NoticeExcel::find($noticeFileId);

        $noticeMessage = $this->createNewNoticeMessage($request, $noticeFile);

        $noticeMessage->push();

        return $this->responseSuccess();
    }

    /**
     * 通告单接受详情
     *
     * @param $noticeFileId
     *
     * @return array
     * @internal param $noticeId
     * @internal param Request $request
     *
     */
    public function receivers($noticeFileId)
    {
        $allReceivers = [];

        $noticeMessage = NoticeExcel::find($noticeFileId)->messages->first();

        if (!$noticeMessage) {
            return '';
        }

        $receivers = $noticeMessage->receivers;

        foreach ($receivers as $messageReceiver) {
            $receiverUser = $messageReceiver->user;

            $tempData = [
				'id'				=> $messageReceiver->id,
                'group_name'        => $receiverUser->groupNamesInMovie($noticeMessage->movie_id),
                'position'          => $receiverUser->positionInMovie($noticeMessage->movie_id),
                'user_name'         => $receiverUser->FNAME,
                'received_at'       => $messageReceiver->updated_at,
                'short_received_at' => $messageReceiver->updated_at->toTimeString(),
                'share_phones'      => $receiverUser->isSharePhonesInMovieOpened($noticeMessage->movie_id)
                    ? $receiverUser->sharePhonesInMovie($noticeMessage->movie_id)->lists('FPHONE')->all()
                    : [],
                'is_leader'         => $receiverUser->isLeaderInMovie($noticeMessage->movie_id),
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
     * @param Request $request
     *
     * @param         $noticeFile
     *
     * @return Message
     */
    private function createNewNoticeMessage(Request $request, NoticeExcel $noticeFile)
    {
        $movie = Movie::find($request->input('movie_id'));

        if ($request->has('scoped_ids') && $request->input('scoped_ids')) {
            $noticeUserIds = implode(',', array_unique(explode(',', $request->input('scoped_ids'))));
        } else {
            $noticeUserIds = implode(',', $movie->allUsersInMovie()->lists('FID')->all());
        }

        $noticeFile->rememberReceivers($noticeUserIds);

        return Message::create([
            'type'           => "NOTICE",
            'scope'          => 1,
            "scope_ids"      => $noticeUserIds,
            'title'          => $movie->FNAME . ':您有新的通告单请接收。',
            'content'        => $noticeFile->notice->FNAME,
            'filename'       => $noticeFile->FFILENAME,
            'notice_id'      => $noticeFile->notice->FID,
            'notice_file_id' => $noticeFile->FID,
            'from'           => $request->input('user_id'),
            'notice_type'    => $request->input('notice_type'),
            'movie_id'       => $request->input('movie_id'),
            'uri'            => $request->input('file_uri') ?: $request->input('file_url')
        ]);
    }

    /**
     * 撤回发送的通告单
     *
     * @param $noticeFileId
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function undo($noticeFileId)
    {
        $noticeFile = NoticeExcel::find($noticeFileId);

        foreach ($noticeFile->messages as $message) {
            $message->undo();
        }

        return $this->responseSuccess();
    }

    /**
     * 预备通告单选择接收人
     *
     * @param         $noticeFileId
     * @param Request $request
     *
     * @return array
     */
    public function choose($noticeFileId, Request $request)
    {
        $noticeFile = NoticeExcel::find($noticeFileId);
        $users      = $noticeFile->notice->movie->allUsersInMovie();
        $movieId    = $noticeFile->notice->movie->FID;

        $lastNoticeReceiver      = $noticeFile->fileReceivers()->first();
        $lastTimeChosenReceivers = $lastNoticeReceiver ? $lastNoticeReceiver->last_receivers : [];

        $result = [];
        foreach ($users as $user) {
            $userInfoObj              = $user->formatBasicClass()
                                             ->withPositionInMovie($movieId)
                                             ->withGroupNamesInMovie($movieId)
                                             ->get();
            $userInfoObj->is_selected = in_array($userInfoObj->user_id, $lastTimeChosenReceivers);

            $result[] = $userInfoObj;
        }
        return $this->responseSuccess('操作成功', ['users' => $result]);
    }

    /**
     * 获取最后一条接收人有自己的预备通告单
     *
     * @param         $movieId
     * @param         $userId
     * @param         $isTongchou
     * @param         $noticeType
     * @param Request $request
     *
     * @return array
     * @internal param int $page
     *
     */
    private function getNoticesDatesPaged($movieId, $userId, $isTongchou, $noticeType, Request $request)
    {
        if ($request->input('date')) {
            return (array)$request->input('date');
        }

        $page = $request->input('page', 0);

        $dates = $isTongchou
            ? $this->getTongchouNoticeDates($movieId, $userId, $noticeType, $page)
            : $this->getNotTongchouNoticeDates($movieId, $userId, $noticeType, $page);

        return ($request->input('order') == 'desc') ? $dates->all() : $dates->reverse()->all();
    }

    /**
     * @param $movieId
     * @param $userId
     * @param $noticeType
     * @param $is_tongchou
     * @param $seeDate
     * @param $canSeeReceivers
     *
     * @return array
     */
    private function getNoticeFiles($movieId, $userId, $noticeType, $is_tongchou, $seeDate, $canSeeReceivers)
    {
        $notice = Notice::where("FMOVIEID", $movieId)
                        ->where("FDATE", $seeDate)
                        ->where("FNOTICEEXCELTYPE", $noticeType)
                        ->first();

        $noticeFiles = $notice ? $notice->excelinfos()->all() : [];

        //如果不是统筹,不返回没发送的文件
        if (!$is_tongchou) {
            $noticeFiles = array_values(array_filter($noticeFiles, function ($noticeFile) {
                return $noticeFile->is_send() && !Message::is_undo($noticeFile->notice->FID, $noticeFile->FID);
            }));
        }

        return $this->formatReturnData($notice, $noticeFiles, $userId, $is_tongchou, $canSeeReceivers, $seeDate);
    }

    /**
     * @param $movieId
     * @param $userId
     * @param $noticeType
     * @param $page
     *
     * @return Collection
     */
    private function getNotTongchouNoticeDates($movieId, $userId, $noticeType, $page)
    {
        return Message::selectRaw("distinct t_biz_noticeexcel.FDATE as notice_date")
                      ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                      ->leftjoin("t_biz_noticeexcel", "t_biz_noticeexcel.FID", "=", "messages.notice_id")
                      ->whereRaw("messages.type = 'notice' and messages.notice_type = {$noticeType} and  messages.movie_id = " . $movieId)
                      ->where('messages.is_undo', 0)
                      ->where("message_receivers.receiver_id", $userId)
                      ->orderby("notice_date", "desc")
                      ->take(10)
                      ->skip($page * 10)
                      ->get('notice_date')
                      ->pluck('notice_date')
                      ->map(function ($date) {
                          return substr($date, 0, 10);
                      });
    }

    /**
     * @param $movieId
     * @param $userId
     * @param $noticeType
     * @param $page
     *
     * @return Collection
     */
    private function getTongchouNoticeDates($movieId, $userId, $noticeType, $page)
    {
        return Notice::where(['FMOVIEID' => $movieId, 'FNOTICEEXCELTYPE' => $noticeType])
                     ->orderBy('FDATE', 'desc')
                     ->take(10)
                     ->skip($page * 10)
                     ->get(['FDATE'])
                     ->pluck('FDATE')
                     ->map(function ($date) {
                         return substr($date, 0, 10);
                     });
    }

}
