<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\MessageReceiver;
use App\Models\Movie;
use App\Models\Notice;
use App\Models\NoticeExcel;
use App\User;
use App\Utils\StringUtil;
use Illuminate\Http\Request;


/**
 * Class NoticesController
 * @package App\Http\Controllers\Mobile
 */
class NoticesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('mobile.user_must_in_movie', [
            'only' => [
                'index',  //每日通告干
            ]
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $movie_id = $request->get("movie_id");
        $day      = $request->get("day");
        $user_id  = $request->get("user_id");
        $type     = $request->get("type");
        $name     = date('Y-m-d', time());
        $user     = User::find($user_id);

        $is_tongchou = $user->isTongChouInMovie($movie_id);

        if ($is_tongchou && $type == 20) {
            //显示最后一条已经发送的预备通告单
            $last = Message::select("notice_id", "created_at")
                           ->where("type", "notice")
                           ->where("movie_id", $movie_id)
                           ->where("from", $user_id)
                           ->orderby("created_at", "desc")
                           ->first();
            if (isset($last)) {
                $name = $last->created_at->toDateString();
            }
        }

        $notice_backup_num = Message::leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                                    ->leftjoin("t_biz_noticeexcel", "t_biz_noticeexcel.FID", "=", "messages.notice_id")
                                    ->whereRaw("messages.type = 'notice' and messages.notice_type = 20  and messages.movie_id = " . $movie_id)
                                    ->where("message_receivers.receiver_id", $user_id)
                                    ->where("message_receivers.is_read", 0)
                                    ->count();

        //默认显示最后一条的通告单
        $unread_date_data = Message::selectRaw("t_biz_noticeexcel.FDATE as unread_date")
                                   ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                                   ->leftjoin("t_biz_noticeexcel", "t_biz_noticeexcel.FID", "=", "messages.notice_id")
                                   ->whereRaw("messages.type = 'notice' and messages.notice_type = " . $type . " and  messages.movie_id = " . $movie_id)
                                   ->where("message_receivers.receiver_id", $user_id)
                                   ->where("message_receivers.is_read", 0)
                                   ->orderby("unread_date", "desc")
                                   ->first();
        //默认显示最后一条的预备通告单
        if ($type == "20" && $notice_backup_num == 0) {
            $unread_date_data = Message::selectRaw("t_biz_noticeexcel.FDATE as unread_date")
                                       ->leftJoin("message_receivers", "message_receivers.message_id", "=",
                                           "messages.id")
                                       ->leftjoin("t_biz_noticeexcel", "t_biz_noticeexcel.FID", "=",
                                           "messages.notice_id")
                                       ->whereRaw("messages.type = 'notice' and messages.notice_type = " . $type . " and  messages.movie_id = " . $movie_id)
                                       ->where("message_receivers.receiver_id", $user_id)
                                       ->orderby("unread_date", "desc")
                                       ->first();
        }

        if ($unread_date_data) {
            $name = substr($unread_date_data->unread_date, 0, 10);
        }

        if ($day) {
            $name = $day;
        }

        $movie = \DB::table('t_biz_movie')
                    ->selectRaw("t_biz_groupuser.FID as groupuserid, t_biz_groupuser.FGROUPUSERROLE, t_biz_groupuser.FGROUP, t_biz_movie.FID as  juzuid, t_biz_movie.FNAME, t_biz_movie.FTYPE, t_biz_movie.fid movieid")
                    ->leftJoin('t_biz_groupuser', 't_biz_groupuser.fmovie', '=', 't_biz_movie.FID')
                    ->where("t_biz_movie.FID", $movie_id)
                    ->where("t_biz_groupuser.FUSER", $user_id)
                    ->first();

        $is_show_receivers = \DB::table("t_biz_nereceivepower")
                                ->where("fgroupuserid", $movie->groupuserid)
                                ->where("fmovieid", $movie_id)
                                ->count();

        //是统筹就跳转到发送页面
        if ($is_tongchou) {
            $notice = Notice::where("FMOVIEID", $movie_id)->where("FDATE", $name)->where("FNOTICEEXCELTYPE",
                $type)->first();
            return view("mobile.notices.tongchou", [
                "user_id"           => $user_id,
                "movie_id"          => $movie_id,
                "type"              => $type,
                "day"               => $name,
                "notice"            => $notice,
                "is_show_receivers" => $is_show_receivers
            ]);
        }

        $results = \DB::select("select 
                                m.notice_id, 
                                m.notice_file_id 
                                from  message_receivers as mr 
                                left join messages as m  
                                on m.id = mr.message_id 
                                left join t_biz_noticeexcel as e 
                                on e.FID = m.notice_id 
                                where e.FDATE = '" . $name . "' 
                                and e.FNOTICEEXCELTYPE = " . $type . " 
                                and m.movie_id=" . $movie_id . " 
                                and m.is_undo = 0  
                                and mr.receiver_id = " . $user_id . " 
                                group by notice_file_id");
        $excels  = [];
        $notice  = "";
        foreach ($results as $result) {
            $notice   = Notice::where("FID", $result->notice_id)->first();
            $excels[] = NoticeExcel::where("FID", $result->notice_file_id)->first();
        }

        return view("mobile.notices.daily", [
            "user_id"           => $user_id,
            "movie_id"          => $movie_id,
            "type"              => $type,
            "day"               => $name,
            "excels"            => $excels,
            "notice"            => $notice,
            "is_show_receivers" => $is_show_receivers
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $message = $this->createNewNoticeMessage($request);

        $message->push();

        return redirect()->to("/mobile/notices?day=" . $request->get("day") . "&type=" . $request->get("type") . "&movie_id=" . $data['movie_id'] . "&user_id=" . $data['user_id']);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redo(Request $request)
    {
        $data     = $request->all();
        $messages = Message::where("notice_id", $data['notice_id'])->where("notice_file_id", $data['notice_file_id'])->get();
        foreach ($messages as $message) {
            //\Log::info("redo->" . $message->id);
            $message->update(["is_undo" => 1]);
            MessageReceiver::where("message_id", $message->id)->delete();
        }
        return redirect()->to("/mobile/notices?day=" . $request->get("day") . "&type=" . $request->get("type") . "&movie_id=" . $data['movie_id'] . "&user_id=" . $data['user_id']);
    }

    /**
     * @param Request $request
     * @param         $notice_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $notice_id)
    {
        $user_id = $this->current_user($request);

        $messages = Message::where("notice_id", $notice_id)
                           ->where("notice_file_id", $request->get("excel_id"))
                           ->orderby("id", "desc")
                           ->get();

        foreach ($messages as $message) {
            if ($message) {
                $ms = MessageReceiver::where("message_id", $message->id)->where("receiver_id", $user_id)->first();
                if ($ms) {
                    $ms->is_read = 1;
                    $ms->save();
                }
            }
        }

        $file = NoticeExcel::where("FID", $request->get("excel_id"))->first();

        return redirect()->to($file->FFILEADD);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function choose(Request $request)
    {
        $data    = $request->all();
        $movieId = $data['movie_id'];

        $chars = StringUtil::chars();
        $users = Movie::find($data['movie_id'])->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view("mobile.notices.choose", compact('data', 'chars', 'groupedUsers', 'movieId'));
    }


    /**
     * @param Request $request
     * @param         $notice_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function receivers(Request $request, $notice_id)
    {
        $receivers    = [];
        $un_receivers = [];
        $message      = Message::where("notice_id", $notice_id)->where("notice_file_id",
            $request->get("excel_id"))->orderby("id", "desc")->first();
        if ($message) {
            $ms = MessageReceiver::where("message_receivers.message_id",
                $message->id)->where("message_receivers.is_read", 0)->get();
            foreach ($ms as $receiver) {
                $rer    = [];
                $result = \DB::select("select tu.FPHONE as phone, g.FLEADERID as leader,g.FID as groupid , g.FNAME as groupname,u.FREMARK as job,tu.FNAME as username,u.FID as group_user_id,u.FOPENED from  t_biz_groupuser as u left join t_biz_group as g  on g.FID = u.FGROUP left join t_sys_user as tu on tu.FID = u.FUSER  where u.FMOVIE = " . $message->movie_id . " and u.FUSER = " . $receiver->receiver_id . " order by groupid");
                if (isset($result[0])) {

                    $rer['groupid']       = $result[0]->groupid;
                    $rer['groupname']     = $result[0]->groupname;
                    $rer['job']           = $result[0]->job;
                    $rer['phone']         = $result[0]->phone;
                    $rer['FOPENED']       = $result[0]->FOPENED;
                    $rer['group_user_id'] = $result[0]->group_user_id;
                    $rer['uid']           = $receiver->receiver_id;
                    $rer['username']      = $result[0]->username;
                    $rer['updated_at']    = $receiver->updated_at;
                    $rer['created_at']    = $receiver->created_at;
                    $rer['leader']        = $result[0]->leader;
                    $un_receivers[]       = $rer;
                }
            }
            $ms = MessageReceiver::where("message_receivers.message_id", $message->id)->where("is_read",
                1)->orderbyRaw(" is_read , updated_at desc")->get();
            foreach ($ms as $receiver) {
                $rer    = [];
                $result = \DB::select("select tu.FPHONE as phone, g.FLEADERID as leader , g.FNAME as groupname,u.FREMARK as job,tu.FNAME as username,u.FID as group_user_id,u.FOPENED from  t_biz_groupuser as u left join t_biz_group as g  on g.FID = u.FGROUP left join t_sys_user as tu on tu.FID = u.FUSER  where u.FMOVIE = " . $message->movie_id . " and u.FUSER = " . $receiver->receiver_id);
                if (isset($result[0])) {
                    $rer['groupname']     = $result[0]->groupname;
                    $rer['job']           = $result[0]->job;
                    $rer['phone']         = $result[0]->phone;
                    $rer['FOPENED']       = $result[0]->FOPENED;
                    $rer['group_user_id'] = $result[0]->group_user_id;
                    $rer['uid']           = $receiver->receiver_id;
                    $rer['username']      = $result[0]->username;
                    $rer['updated_at']    = $receiver->updated_at;
                    $rer['created_at']    = $receiver->created_at;
                    $rer['leader']        = $result[0]->leader;
                    $receivers[]          = $rer;
                }
            }
        }
        \Log::info('--receivers' . json_encode(array_column($un_receivers, 'uid')));
        return view("mobile.notices.receivers",
            ["receivers" => $receivers, "un_receivers" => $un_receivers, 'movieId' => $request->input('movie_id')]);
    }


    /**
     * @param Request $request
     *
     * @return Message
     */
    private function createNewNoticeMessage(Request $request)
    {
        $data          = $request->all();
        $data['from']  = $data['user_id'];
        $data['scope'] = 1;
        $data['uri']   = $request->root() . $data['uri'];
        $data['type']  = "NOTICE";
        if (!isset($data['scope_ids'])) {
            $data["scope_ids"] = implode(',', GroupUser::where("FMOVIE", $data['movie_id'])->selectRaw('distinct FUSER')->lists('FUSER')->all());
        }
        return Message::create($data);
    }


}
