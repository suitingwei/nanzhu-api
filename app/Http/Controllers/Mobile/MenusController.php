<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\GroupUser;
use App\Models\Message;
use DB;
use Illuminate\Http\Request;

class MenusController extends BaseController
{
    public function index(Request $request)
    {
        $user_id  = $request->get("user_id");
        $movies   = \DB::table('t_biz_movie')
                       ->leftJoin('t_biz_groupuser', 't_biz_groupuser.fmovie', '=', 't_biz_movie.FID')
                       ->where("t_biz_groupuser.fuser", $user_id)
                       ->where("t_biz_movie.shootend", 0)
                       ->selectRaw("t_biz_movie.FID as movie_id,t_biz_movie.FNAME as movie_name,
                                    t_biz_movie.FSTARTDATE as start_date , 
                                    t_biz_movie.FENDDATE as end_date,
                                    t_biz_movie.FTYPE as movie_type")
                       ->orderBy("t_biz_movie.FID", "desc")
                       ->get();
        $s        = [];
        $current  = time();
        $movie_id = "";
        if (count($movies) > 0) {
            foreach ($movies as $movie) {
                if (in_array($movie->movie_id, array_column($s, 'movie_id'))) {
                    continue;
                }
                $json               = [];
                $json["movie_id"]   = $movie->movie_id;
                $json["movie_name"] = $movie->movie_name;
                $json["movie_type"] = $movie->movie_type;
                $data               = \DB::table("t_biz_progresstotaldata")->select("FSTARTDATE")->where("FMOVIEID",
                    $movie->movie_id)->first();
                $json["progress"]   = 0;
                if ($data) {
                    $n = round(($current - strtotime($data->FSTARTDATE)) / 3600 / 24 - 0.5, 0, PHP_ROUND_HALF_ODD);
                    if ($n == 0) {
                        $n = 1;
                    }
                    $json["progress"] = $n;
                }
                $s[] = $json;
            }
            $movie_id = $movies[0]->movie_id;
        }
        if ($request->get("movie_id")) {
            $movie_id = $request->get("movie_id");
        }

        $is_tongchou    = 0;
        $blog_num       = 0;
        $hasPrepread    = false;
        $progressButton = 0;
        $juzuButton     = 0;
        $contactButton  = 0;
        $groupButton    = 0;
        $bigPlanButton  = 0;

        if ($movie_id) {
            $is_tongchou = GroupUser::is_tongchou($movie_id, $user_id);
            $blog_num    = Message::leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                                  ->whereRaw("messages.type = 'blog' and  messages.scope_ids like '%" . $user_id . "%' and messages.movie_id = " . $movie_id)
                                  ->where("message_receivers.receiver_id", $user_id)
                                  ->orderby("messages.id", "desc")
                                  ->where("message_receivers.is_read", 0)
                                  ->count();

            $juzu_num = Message::leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                               ->whereRaw("messages.type = 'juzu' and  messages.scope_ids like '%" . $user_id . "%' and messages.movie_id = " . $movie_id)
                               ->where("message_receivers.receiver_id", $user_id)
                               ->orderby("messages.id", "desc")
                               ->where("message_receivers.is_read", 0)
                               ->count();

            $movie = \DB::table('t_biz_movie')->selectRaw("t_biz_groupuser.FID as groupuserid, t_biz_groupuser.FGROUPUSERROLE, t_biz_groupuser.FGROUP, t_biz_movie.FID as  juzuid, t_biz_movie.FNAME, t_biz_movie.FTYPE, t_biz_movie.fid movieid")
                        ->leftJoin('t_biz_groupuser', 't_biz_groupuser.fmovie', '=', 't_biz_movie.FID')
                        ->where("t_biz_movie.FID", $movie_id)
                        ->where("t_biz_groupuser.FUSER", $user_id)
                        ->first();

            $notice_backup_num = Message::leftJoin("message_receivers", "message_receivers.message_id", "=",
                "messages.id")
                                        ->whereRaw("messages.type = 'notice' and messages.notice_type = 20  and messages.movie_id = " . $movie_id)
                                        ->where("message_receivers.receiver_id", $user_id)
                                        ->orderby("messages.id", "desc")
                                        ->count();

            if ($notice_backup_num > 0) {
                $hasPrepread = true;
            }
            $progressButton = 0;

            $notice_num = Message::leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                                 ->whereRaw("messages.type = 'notice' and messages.notice_type = 10 and  messages.movie_id = " . $movie_id)
                                 ->where("message_receivers.receiver_id", $user_id)
                                 ->orderby("messages.id", "desc")
                                 ->where("message_receivers.is_read", 0)
                                 ->count();


            $notice_backup_num = Message::leftJoin("message_receivers", "message_receivers.message_id", "=",
                "messages.id")
                                        ->whereRaw("messages.type = 'notice' and messages.notice_type = 20  and messages.movie_id = " . $movie_id)
                                        ->where("message_receivers.receiver_id", $user_id)
                                        ->orderby("messages.id", "desc")
                                        ->where("message_receivers.is_read", 0)
                                        ->count();

            if ($movie) {

                $contactButton = \DB::table("t_biz_contactpower")->where("fgroupuserid",
                    $movie->groupuserid)->where("fmovieid", $movie_id)->count();

                $bigPlanButton = \DB::table("reference_plan_powers")->where("group_user_id",
                    $movie->groupuserid)->where("movie_id", $movie_id)->count();

                $result1 = \DB::table("t_biz_progresspower")->where("fgroupuserid",
                    $movie->groupuserid)->where("fmovieid", $movie_id)->first();

                if ($result1) {
                    $progressButton = 1;
                }

                $group = DB::table('t_biz_group')->where(['FMOVIE' => $movie_id, 'FLEADERID' => $user_id])->first();
                if ($group) {
                    $groupButton = 1;
                }


                if ($movie->FGROUPUSERROLE == 10) {
                    $juzuButton = 1;
                }
            }


        }
        return view("mobile.menus.index", [
            "notice_num"        => isset($notice_num) ? $notice_num : 0,
            "notice_backup_num" => isset($notice_backup_num) ? $notice_backup_num : 0,
            "user_id"           => $user_id,
            "movies"            => $s,
            "movie_id"          => $movie_id,
            "is_tongchou"       => $is_tongchou,
            "blog_num"          => $blog_num,
            "hasPrepread"       => $hasPrepread,
            "juzu_num"          => isset($juzu_num) ? $juzu_num : 0,
            "juzuButton"        => $juzuButton,
            "groupButton"       => $groupButton,
            "progressButton"    => $progressButton,
            "contactButton"     => $contactButton,
            'bigPlanButton'     => $bigPlanButton
        ]);
    }


}
