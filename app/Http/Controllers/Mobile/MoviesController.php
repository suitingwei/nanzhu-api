<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Managers\Powers\UserPowerManager;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\Movie;
use App\Models\MovieLock;
use App\User;
use Illuminate\Http\Request;

/**
 * Class MoviesController
 * @package App\Http\Controllers\Mobile
 */
class MoviesController extends BaseController
{
    public function __construct()
    {
        //剧组信息
        $this->middleware('mobile.user_must_in_movie', ['only' => ['show']]);

        //加入剧组的限制条件
        $this->middleware('mobile.join_movie', ['only' => 'post_join']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $name = "$$";
        if ($request->get("name")) {
            $name = $request->get("name");
        }
        if ($request->get("q")) {
            $name = $request->get("q");
        }
        $user_id   = $request->get("user_id");
        $movies    = \DB::select("select tm.FID as movie_id ,tm.FNAME as movie_name,tm.FNEWDATE as movie_create_at ,tm.chupinfang,tm.zhizuofang,tm.FSTARTDATE,tm.FENDDATE,tm.FNEWUSER,tm.FTYPE,tm.FISOROPEN from  t_biz_movie tm  where tm.shootend =0 and  tm.FNAME like '%" . $name . "%' order by FID desc");
        $movie_ids = \DB::select("SELECT   t_biz_movie.fid movieid FROM t_biz_movie LEFT OUTER JOIN (SELECT fid, fmovie, feditdate, fuser, fgroup, fgroupuserrole FROM t_biz_groupuser WHERE fuser = '" . $user_id . "' GROUP BY fid, fmovie, feditdate, fuser, fgroup, fgroupuserrole) t_biz_groupuser ON t_biz_movie.fid = t_biz_groupuser.fmovie WHERE t_biz_groupuser.fuser = '" . $user_id . "' ORDER BY t_biz_movie.fnewdate DESC");
        $arr       = [];
        foreach ($movie_ids as $movie_id) {
            $arr[$movie_id->movieid] = "";
        }
        //dd($arr);
        return view("mobile.movies.search", ["movies" => $movies, "user_id" => $user_id, "movie_ids" => $arr]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function access_join(Request $request)
    {
        $movie_id = $request->get("movie_id");
        $user_id  = $request->get("user_id");
        $groups   = Group::select("FID", "FNAME", "FGROUPTYPE")->where("FMOVIE", $movie_id)->orderBy('FPOS',
            'desc')->get();
        return view("mobile.movies.access_join", ["movie_id" => $movie_id, "user_id" => $user_id, "groups" => $groups]);
    }

    /**
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $movie   = Movie::where("FID", $id)->first();
        $user_id = $movie->FNEWUSER;
        return view("mobile.movies.show", ["movie" => $movie, "user_id" => $user_id]);
    }

    /**
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $current             = date('Y-m-d H:i:s', time());
        $data                = $request->all();
        $mdata['FEDITDATE']  = $current;
        $mdata['FTYPE']      = $data['FTYPE'];
        $mdata['FSTARTDATE'] = $data['FSTARTDATE'];
        $mdata['FENDDATE']   = $data['FENDDATE'];
        $mdata['FPASSWORD']  = $data['FPASSWORD'];
        $is_open             = 0;
        if ($request->get('FISOROPEN')) {
            $is_open = 1;
        }
        $mdata['FISOROPEN']  = $is_open;
        $mdata['FNAME']      = $data['FNAME'];
        $chupinfangArr       = array_filter($request->input('chupinfang'), function ($chupinfang) {
            return $chupinfang != '';
        });
        $zhizuofangArr       = array_filter($request->input('zhizuofang'), function ($zhizuofang) {
            return $zhizuofang != '';
        });
        $mdata['chupinfang'] = implode(",", $chupinfangArr);
        $mdata['zhizuofang'] = implode(",", $zhizuofangArr);
        Movie::where("FID", $id)->update($mdata);
        return redirect()->to("/mobile/movies/" . $id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function post_join(Request $request)
    {
        $movie_id = $request->get("movie_id");
        $user_id  = $request->get("user_id");
        $group    = Group::where("FMOVIE", $movie_id)->where("FNAME", $request->input('group_name'))->first();
        $user     = User::find($user_id);
        $movie    = Movie::find($movie_id);

        //1. 加入部门
        list($fcheck, $group_user) = $this->joinGroup($request, $group, $user_id, $movie_id);

        $user->createSharePhones($group_user, $fcheck);

        if ($request->has('is_join_chat_group')) {
            $user->joinHxGroup($group);
        }

        $movie->addUserToMessageReceiver($user_id, [Message::TYPE_NOTICE, Message::TYPE_JUZU, Message::TYPE_BLOG]);
        $group->addUserToShareTodos($user_id);
        UserPowerManager::assignPowerByGroup($user, $group);

        if ($request->ajax()) {
            return $this->ajaxResponseSuccess('成功', ['movie_id' => $movie_id]);
        }

        return redirect()->to("/mobile/result?result=" . json_encode(["ret" => 0, "msg" => "进入成功"]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $phone   = $request->get("phone");
        $user_id = User::where("FPHONE", $phone)->first()->FID;

        $movies = \DB::select("select mu.FMOVIE as movie_id,su.FNAME as username,su.FID as user_id  ,tm.FNAME as movie_name,tm.FNEWDATE as movie_create_at from t_biz_movieuser  as mu  left join t_sys_user  su on mu.FUSER=su.FID  left join t_biz_movie tm on  tm.FID = mu.FMOVIE where su.FPHONE ='" . $phone . "'");

        return view("mobile.movies.index", ["movies" => $movies, "user_id" => $user_id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $user_id  = $request->get("user_id");
        $movie_id = $request->get("movie_id");
        return view("mobile.movies.create", ["user_id" => $user_id, "movie_id" => $movie_id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = User::find($request->input('user_id'));

        //1. 创建剧组
        $movie = $this->createNewMovie($request, $user);

        //2. 创建剧组必须的部门
        $this->createEssentialDepartments($user, $movie);

        //3. 将建剧人添加到制片组
        $zhiPianGroup = $movie->groups()->where('FGROUPTYPE', Group::TYPE_ZHI_PIAN)->first();

        $groupUser = $user->joinGroup($zhiPianGroup, '', true);

        //4. 将建剧人加入制片组群聊
        $user->joinHxGroup($zhiPianGroup);

        //5. 添加制片人的默认分享电话
        $user->createSharePhones($groupUser);

        //6. Add the movie admin power with daily reports,progress,movie contacts,big plans,and receivers.
        UserPowerManager::assignAllPowersToUser($user, $movie->FID);

        return redirect()->to("/mobile/groups/templates?movie_id=" . $movie->FID . "&user_id=" . $request->input('user_id') . '&title=生成部门');
    }

    /**
     * Create the essentail departments.
     * @param User  $user
     * @param Movie $movie
     */
    public function createEssentialDepartments(User $user, Movie $movie)
    {
        foreach (Movie::$essentialDepartments as $department) {
            Group::create([
                'FMOVIE'     => $movie->FID,
                'FNAME'      => $department['name'],
                'FGROUPTYPE' => $department['type'],
                'FNEWDATE'   => date('Y-m-d H:i:s'),
                'FEDITDATE'  => date('Y-m-d H:i:s'),
                'FID'        => Group::max("FID") + 1,
                'FPOS'       => $department['sort']
            ]);
        }
    }

    /**
     * 创建新的电影
     * @param Request $request
     * @param User    $user
     * @return Movie
     */
    private function createNewMovie(Request $request, User $user)
    {
        $data               = $request->all();
        $data['FNEWUSER']   = $request->get("user_id");
        $data['FNEWDATE']   = date('Y-m-d H:i:s', time());
        $data['FID']        = Movie::max("FID") + 1;
        $chupinfangArr      = array_filter($request->input('chupinfang'), function ($chupinfang) {
            return $chupinfang != '';
        });
        $zhizuofangArr      = array_filter($request->input('zhizuofang'), function ($zhizuofang) {
            return $zhizuofang != '';
        });
        $data['chupinfang'] = implode(",", $chupinfangArr);
        $data['zhizuofang'] = implode(",", $zhizuofangArr);

        $movie = Movie::create($data);

        // 创建剧组的环信聊天室
        //$movie->createChatGroupWithOwner($user);

        return $movie;
    }

    /**
     * @param Request $request
     * @param         $group
     * @param         $user_id
     * @param         $movie_id
     * @return array
     */
    private function joinGroup(Request $request, $group, $user_id, $movie_id)
    {
        $is_public = 20;
        if ($request->get('is_public')) {
            $is_public = 10;
        }

        $fcheck = 0;
        if ($request->get("is_use_phone")) {
            $fcheck = 1;
        }
        $gdata['FGROUP']         = $group->FID;
        $gdata['FUSER']          = $user_id;
        $gdata['FREMARK']        = $request->get('job');
        $gdata['FID']            = GroupUser::max("FID") + 1;
        $gdata['FMOVIE']         = $movie_id;
        $gdata['FOPEN']          = $is_public;
        $gdata['FOPENED']        = $fcheck;
        $gdata['FPUBLICTEL']     = 20;
        $gdata['FGROUPUSERROLE'] = 20;
        $gdata['FNEWDATE']       = date('Y-m-d H:i:s');
        $gdata['FEDITDATE']      = date('Y-m-d H:i:s');
        $group_user              = GroupUser::create($gdata);
        return array($fcheck, $group_user);
    }

    /**
     * 剧组所有成员
     * @param         $movieId
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members($movieId, Request $request)
    {
        $movie = Movie::find($movieId);

        $members = $movie->allUsersInMovie();

        return view('mobile.movies.members', compact('members', 'movie'));
    }

    public function validateMovieNames(Request $request)
    {
        $lockedMovieIds = MovieLock::pluck('movie_id');
        $isLocked       = Movie::whereIn('FID', $lockedMovieIds)
                               ->where('FNAME', $request->input('name'))
                               ->where('FTYPE', $request->input('type'))->exists();
        if ($isLocked) {
            return $this->ajaxResponseFail('该剧名已经被锁定，请使用其他名字');
        }

        return $this->ajaxResponseSuccess();
    }
}
