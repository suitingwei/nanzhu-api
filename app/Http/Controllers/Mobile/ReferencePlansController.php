<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\MessageReceiver;
use App\Models\ReferencePlan;
use Illuminate\Http\Request;

class ReferencePlansController extends BaseController
{

    /**
     * 接受详情
     */
    public function receivers($planId, Request $request)
    {
        $receivers    = [];
        $un_receivers = [];
        $plan         = ReferencePlan::find($planId);
        $message      = $plan->messages()->first();
        if ($message) {
            $ms = MessageReceiver::where("message_receivers.message_id", $message->id)
                                 ->where("message_receivers.is_read", 0)
                                 ->get();
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
            $ms = MessageReceiver::where("message_receivers.message_id", $message->id)
                                 ->where("is_read", 1)
                                 ->orderbyRaw(" is_read , updated_at desc")
                                 ->get();
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
        return view("mobile.plans.receivers",
            ["receivers" => $receivers, "un_receivers" => $un_receivers, 'movieId' => $request->input('movie_id')]);
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
        $plan   = ReferencePlan::find($planId);

        $messages = $plan->messages;

        if ($messages->count() == 0) {
            return redirect()->to($plan->file_url);
        }

        foreach ($messages as $message) {
            MessageReceiver::where("message_id", $message->id)->where("receiver_id", $userId)->update(['is_read' => 1]);
        }

        return redirect()->to($plan->file_url);

    }
}
