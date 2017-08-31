<?php

namespace App\Http\Controllers\Api;

use App\Managers\Behaviors\JoinMovieBehaviorManager;
use App\Models\ContactPower;
use App\Models\Group;
use App\Models\Movie;
use App\User;
use Illuminate\Http\Request;

class MoviesController extends BaseController
{
    public function index(Request $request)
    {
        $user_id = $request->get("user_id");
        //$data = \DB::select("SELECT t_biz_groupuser.fid groupuserid, t_biz_groupuser.fgroupuserrole, t_biz_groupuser.fgroup, t_biz_movie.fid juzuid, t_biz_movie.fname, t_biz_movie.ftype, t_biz_movie.fid movieid FROM t_biz_movie tm LEFT OUTER JOIN (SELECT fid, fmovie, feditdate, fuser, fgroup, fgroupuserrole FROM t_biz_groupuser WHERE fuser = '28507' GROUP BY fid, fmovie, feditdate, fuser, fgroup, fgroupuserrole) t_biz_groupuser ON t_biz_movie.fid = t_biz_groupuser.fmovie WHERE t_biz_groupuser.fuser = '".$user_id."' ORDER BY t_biz_movie.fnewdate DESC")	;
        $movies  = \DB::table('t_biz_movie')->selectRaw("t_biz_movie.FID as movie_id,t_biz_movie.FNAME as movie_name,t_biz_movie.FSTARTDATE as start_date , t_biz_movie.FENDDATE as end_date")
                      ->leftJoin('t_biz_groupuser', 't_biz_groupuser.fmovie', '=', 't_biz_movie.FID')
                      ->where("t_biz_movie.shootend", 0)
                      ->where("t_biz_groupuser.fuser", $user_id)
                      ->get();
        $s       = [];
        $user    = User::find($user_id);
        $current = time();
        foreach ($movies as $movie) {
            if (in_array($movie->movie_id, array_column($s, 'movie_id'))) {
                continue;
            }
            $json               = [];
            $json["movie_id"]   = $movie->movie_id;
            $json["movie_name"] = $movie->movie_name;
            $json["progress"]   = round((strtotime($movie->end_date) - $current) / 3600 / 24);
            $json['groups']     = $user->groupsInMovie($movie->movie_id)->map(function ($group) {
                return [
                    'group_name' => $group->FNAME,
                    'group_id'   => $group->FID
                ];
            });
            $s[]                = $json;
        }
        return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $s]);
    }

    /**
     * Get the movie's contacts data.
     * @param $movieId
     * @return \Illuminate\Http\JsonResponse
     */
    public function contacts($movieId)
    {
        $users = Movie::find($movieId)->allUsersInContacts()->map(function ($user) use ($movieId) {
            $phones = !$user->isSharePhonesInMovieOpened($movieId) ? [] : $user->sharePhonesInMovie($movieId)->pluck('FPHONE')->all();
            return [
                'name'           => $user->hx_name,
                'avatar'         => $user->cover_url,
                'group_position' => $user->groupNamesInMovie($movieId, true) . '/' . $user->positionInMovie($movieId),
                'phones'         => $phones
            ];
        });

        return $this->responseSuccess('success', ['users' => $users]);
    }

    /**
     * Get the movie's contacts data.
     * @param         $movieId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function publicContacts($movieId, Request $request)
    {
        $currentUser = User::find($this->current_user($request));
        $users       = Movie::find($movieId)->allUsersInPublicContacts()->map(function ($user) use ($movieId) {
            $phones = !$user->isSharePhonesInMovieOpened($movieId) ? [] : $user->sharePhonesInMovie($movieId)->pluck('FPHONE')->all();
            return [
                'name'           => $user->hx_name,
                'avatar'         => $user->cover_url,
                'group_position' => $user->groupNamesInMovie($movieId, true) . '/' . $user->positionInMovie($movieId),
                'phones'         => $phones
            ];
        });

        $canEditPublicContacts = $currentUser->hadAssignedPowerInMovie($movieId, ContactPower::class) &&
                                 ($currentUser->isTongChouInMovie($movieId) || $currentUser->isZhiPianInMovie($movieId));

        return $this->responseSuccess('success', ['users' => $users, 'can_edit' => $canEditPublicContacts]);
    }

    public function updatePublicContacts($movieId, Request $request)
    {
        $movie                 = Movie::find($movieId);
        $needToBePublicUserIds = explode(',', $request->input('new_public_user_ids'));
        $oldUsers            = $movie->allUsersInMovie();
        foreach ($oldUsers as $oldUser) {
            in_array($oldUser->FID, $needToBePublicUserIds) ? $oldUser->setPhonePublicInMovie($movieId) : $oldUser->setPhonePrivateInMovie($movieId);
        }

        return $this->responseSuccess();
    }


    /**
     * Search the movie.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $searchParam = $request->input('q');
        $userId      = $request->input('user_id');
        $user        = User::find($userId);

        if (empty($searchParam)) {
            return $this->responseSuccess('成功', ['movies' => []]);
        }

        $movies = Movie::notEnd()
                       ->where('FNAME', 'like', '%' . $searchParam . '%')
                       ->orderBy('FID', 'desc')
                       ->get()
                       ->map(function ($movie) use ($user) {
                           return [
                               'movie_name'  => $movie->FNAME,
                               'movie_id'    => $movie->FID,
                               'chupinfang'  => $movie->chupinfang,
                               'zhizuofang'  => $movie->zhizuofang,
                               'shoot_dates' => $movie->start_date . '至' . $movie->end_date,
                               'creator'     => $movie->creator->FNAME,
                               'type'        => $movie->FTYPE,
                               'can_join'    => ((boolean)$movie->FISOROPEN) && (!$user->isInMovie($movie->FID)),
                           ];
                       });


        return $this->responseSuccess('成功', ['movies' => $movies]);
    }

    /**
     * @param         $movieId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groups($movieId, Request $request)
    {
        $groups = Group::select("FID", "FNAME", "FGROUPTYPE")
                       ->where("FMOVIE", $movieId)
                       ->orderBy('FPOS', 'desc')
                       ->get()
                       ->map(function ($group) {
                           return [
                               'group_id'   => $group->FID,
                               'group_name' => $group->FNAME
                           ];
                       });

        $h5ReportUrl = $request->root() . '/mobile/reports/create?movie_id=' . $movieId . '&user_id=' . $request->input('user_id');

        return $this->responseSuccess('success', ['groups' => $groups, 'h5_report_url' => $h5ReportUrl]);
    }

    /**
     * Join the movie and certain group.
     * @param         $movieId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function join($movieId, Request $request)
    {
        \Log::info('join movie data' . json_encode($request->all()));
        try {
            (new JoinMovieBehaviorManager)
                ->passRequest($request)
                ->validate()
                ->joinGroup()
                ->addToZhiPianGroupIf()
                ->assignPower()
                ->joinHxGroup()
                ->createSharePhones()
                ->addToMessageReceiver()
                ->addToGroupTodos()
                ->run();
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }
        return $this->responseSuccess();
    }

    /**
     *
     */
    public function members($movieId, Request $request)
    {
        $movie = Movie::find($movieId);

        $members = $movie->allUsersInMovie()->map(function (User $user) use ($movieId) {
            $phones = !$user->isSharePhonesInMovieOpened($movieId) ? [] : $user->sharePhonesInMovie($movieId)->pluck('FPHONE')->all();
            return [
                'is_in_public_contacts' => $user->isInPublicContacts($movieId),
                'user_id'               => $user->FID,
                'name'                  => $user->hx_name,
                'avatar'                => $user->cover_url,
                'group_position'        => $user->groupNamesInMovie($movieId, true) . '/' . $user->positionInMovie($movieId),
                'phones'                => $phones
            ];
        });
        return $this->responseSuccess('success', ['members' => $members]);

    }
}
