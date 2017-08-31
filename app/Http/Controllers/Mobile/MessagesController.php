<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Message;
use App\Models\MessageReceiver;
use App\Models\ReceivePower;
use App\User;
use DB;
use Illuminate\Http\Request;

/**
 * Class MessagesController
 * @package App\Http\Controllers\Mobile
 */
class MessagesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('mobile.user_must_in_movie', [
            'only' => [
                'index',  //剧组通知,具备扉页
            ]
        ]);
    }


    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $message = Message::find($id);
        if ($message->isUndo()) {
            return view('errors.message_not_exists');
        }
        $movie_id        = $message->movie_id;
        $user_id         = $message->from;
        $group_name      = "";
        $current_user_id = $request->get("user_id");
        if (!$current_user_id) {
            $current_user_id = $this->current_user($request);
        }

        $messageReceiver = MessageReceiver::where("message_id", $id)->where("receiver_id", $current_user_id)->first();
        if ($messageReceiver) {
            if ($messageReceiver->is_read == 1) {
                $messageReceiver->timestamps = false;
            }
            $messageReceiver->update(["is_read" => 1]);
        }

        if ($movie_id && $user_id != 0) {
            $group_name = DB::select("select g.FNAME from t_biz_group g left join t_biz_groupuser gu on g.FID = gu.FGROUP where gu.FMOVIE = " . $movie_id . " and gu.FUSER = " . $user_id);
            $group_name = isset($group_name[0]) ? $group_name[0]->FNAME : '';
        }
        return view("mobile.messages.show",
            ["message" => $message, "group_name" => $group_name, 'from' => $request->input('from', 'h5')]);
    }

    public function receivers(Request $request, $id)
    {
        $receivers    = [];
        $un_receivers = [];
        $message      = Message::find($id);
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
                $result = \DB::select("select 
                                       tu.FPHONE as phone, 
                                       g.FLEADERID as leader , 
                                       g.FNAME as groupname,
                                       u.FREMARK as job,
                                       tu.FNAME as username,
                                       u.FID as group_user_id,
                                       u.FOPENED 
                                       from  t_biz_groupuser as u 
                                       left join t_biz_group as g  
                                       on g.FID = u.FGROUP 
                                       left join t_sys_user as tu 
                                       on tu.FID = u.FUSER  
                                       where u.FMOVIE = " . $message->movie_id . " 
                                       and u.FUSER = " . $receiver->receiver_id);
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
        return view("mobile.messages.receivers",
            ["receivers"    => $receivers,
             'un_receivers' => $un_receivers,
             'movieId'      => $request->input('movie_id'),
             'from'         => $request->input('from', 'h5')
            ]);
    }

    public function redo(Request $request, $id)
    {
        $data    = $request->all();
        $message = Message::find($id);
        if ($message) {
            $message->update(["is_undo" => 1]);
            MessageReceiver::where("message_id", $message->id)->where("receiver_id", "<>", $data['user_id'])->delete();
        }
        return $this->ajaxResponseSuccess('操作成功',
            ['redirect_url' => "/mobile/users/{$data["user_id"]}/messages?type={$data['type']}&movie_id={$data['movie_id']}&title={$data['title']}"]);
    }

    /**
     * @param Request $request
     * @param         $user_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $user_id)
    {
        $iosVersion     = $this->iosVersion($request);
        $androidVersion = $this->androidVersion($request);
        $movie_id       = $request->input("movie_id");
        $type           = $request->input("type");
        $user           = User::find($user_id);

        $wherearr = "messages.type = '" . $type . "' and messages.scope_ids like '%" . $user_id . "%' and messages.movie_id = " . $movie_id;

        if ($type == "SYSTEM") {
            $wherearr = "messages.type = '" . $type . "' and ( messages.scope_ids like '%" . $user_id . "%') and messages.movie_id = " . $movie_id;
        }

        $messages = Message::select("messages.*", "message_receivers.is_read as r_is_read")
                           ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                           ->whereRaw($wherearr)
                           ->where("message_receivers.receiver_id", $user_id)
                           ->orderby("id", 'desc')
                           ->groupby("messages.id")
                           ->paginate(5);

        $arrs    = [];
        $results = [];
        foreach ($messages as $message) {
            $arrs[$message->date] = "";
        }

        foreach ($messages as $message) {
            if (array_key_exists($message->date, $arrs)) {
                $arrs[$message->date][] = $message;
            }
        }

        foreach ($arrs as $key => $a) {
            $a = array_reverse($a);
            array_unshift($results, ["date" => $key, "data" => $a]);
        }

        $is_show_receivers = $user->hadAssignedPowerInMovie($movie_id, ReceivePower::class);

        if ($request->ajax()) {
            return view("mobile.messages.ajax_message_data", [
                "messages"          => $results,
                "movie_id"          => $movie_id,
                "user_id"           => $user_id,
                "type"              => $type,
                "is_show_receivers" => $is_show_receivers,
                'nextPage'          => $request->input('page') + 1,
                'iosVersion'        => $iosVersion,
                'androidVersion'    => $androidVersion
            ]);
        }

        return view("mobile.messages.index", [
            "messages"          => $results,
            "movie_id"          => $movie_id,
            "user_id"           => $user_id,
            "type"              => $type,
            "is_show_receivers" => $is_show_receivers,
            'nextPage'          => 1,
            'iosVersion'        => $iosVersion,
            'androidVersion'    => $androidVersion
        ]);
    }

    /**
     * @param Request $request
     * @param         $user_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, $user_id)
    {
        $movie_id      = $request->get("movie_id");
        $type          = $request->get("type");
        $iosAppVersion = $request->header('app-version');
        return view("mobile.messages.create",
            ["user_id" => $user_id, "movie_id" => $movie_id, "type" => $type, 'iosAppVersion' => $iosAppVersion]);
    }

    /**
     * 上传剧组通知
     *
     * @param Request $request
     * @param         $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $user_id)
    {
        Message::createNewJuzuNofity($request, $user_id);

        return redirect()->to("/mobile/users/" . $request->input('user_id') . "/messages?type=" . $request->input('type') . "&movie_id=" . $request->input('movie_id') . '&title=' . $request->input('url_title'));
    }

}

