<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class ProfilesController
 * @package App\Http\Controllers\Api
 */
class ProfilesController extends BaseController
{
    /**
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $current_user_id = $this->current_user($request);

        $like_count  = Like::where("like_id", $id)->count();
        $profile     = Profile::find($id);
        $is_liked    = $this->is_liked($current_user_id, "user", $id);
        $is_favorite = $this->is_favorite($current_user_id, "user", $id);
        $is_share    = $this->is_share($current_user_id, $profile->id);

        //给安卓处理profile里的结果
        $profile->is_liked    = $is_liked;
        $profile->is_share    = $is_share;
        $profile->is_favorite = $is_favorite;
        $profile->like_count  = $like_count;
        return response()->json([
            "ret"         => 0,
            "like_count"  => $like_count,
            "is_liked"    => $is_liked,
            'is_share'    => $is_share,
            "is_favorite" => $is_favorite,
            "profile"     => $profile,
            "msg"         => "操作成功"
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(Request $request)
    {
        return response()->json([
            "types"                  => Profile::types(),               //旧版本艺人职位
            'before_scene_positions' => Profile::$beforeScenePositions, //台前身份
            'behind_scene_positions' => Profile::$behindScenePositions  //幕后身份
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        header('Access-Control-Allow-Origin : *');

		\DB::enableQueryLog();
        //1.选择台前幕后
        $profiles = $this->choosePosition($request);

        //2.搜索类型
        $this->searchByType($profiles, $request);

        //3.搜索名字
        $this->searchByName($profiles, $request);

        //4. 组织数据
        $profileJsons = $this->formatJson($profiles->paginate(12), $request);
		\Log::info('profiles log '.$profiles->toSql());

        $end = microtime(true);
        return response()->json(['time' => ($end - LARAVEL_START) * 1000, "profiles" => $profileJsons]);
    }

    /**
     * 台前或者幕后
     * 默认台前艺人
     * @param Request $request
     * @return
     * @internal param Profile $profiles
     */
    private function choosePosition(Request $request)
    {
        $profiles = Profile::where("is_show", 1)->orderBy("sort", "desc");

        $position = $request->input('position');

        if ($position == 'before') {
            return $profiles->beforeScene();
        }
        elseif ($position == 'behind') {
            return $profiles->behindScene();
        }
        return $profiles;
    }

    /**
     * 根据艺人类型字段进行搜索.
     * 1. 为兼容老版本,默认的数据组类型字段为:type
     * 2. 如果查询的是台前艺人,使用的字段是:before_position
     * 3. 如果查询的是幕后大咖,使用的字段是:behind_position
     * 4. 如果查询的是全部,不需要指定type的查询条件
     * ----------------------------------------------
     * 1. 多个type之间的查询条件为or,但是整个type查询是一个and
     * @param Profile|Builder $profiles
     * @param Request         $request
     */
    private function searchByType(Builder &$profiles, Request $request)
    {
        $types = urldecode($request->input('type'));

        if (empty($types) || $types == "全部") {
            return;
        }

        $typeAttribute = $this->mapTypeToDBAttribute($request);

        $profiles->where(function ($query) use ($types, $typeAttribute) {
            foreach (explode(',', $types) as $searchTypeValue) {
 				if ($searchTypeValue == '男' || $searchTypeValue =='女') {
                    $query->where('gender',$searchTypeValue);
                }
                else {
                    $query->where($typeAttribute, 'like', "%" . $searchTypeValue . "%");
                }
            }
        });
    }

    /**
     * @param Profile|Builder $profiles
     * @param Request         $request
     */
    private function searchByName(Builder &$profiles, Request $request)
    {
        if ($name = urldecode($request->input('q'))) {
            $profiles->where(function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%")
                      ->orWhere('work_ex', 'like', "%{$name}%")
                      ->orWhere('prize_ex', 'like', "%{$name}%")
                      ->orWhere('hometown', 'like', "%{$name}%")
                      ->orWhere('email', 'like', "%{$name}%")
                      ->orWhere('college', 'like', "%{$name}%");
            });
        }
    }

    /**
     * @param Request $request
     * @param Profile $profiles
     * @return array
     */
    private function formatJson($profiles, Request $request)
    {
        $current_user_id = $this->current_user($request);
        $jsons           = [];
        foreach ($profiles as $profile) {
            array_push($jsons, [
                'id'                         => $profile->id,
                'name'                       => $profile->name,
                'is_favorite'                => $this->is_favorite($current_user_id, "user", $profile->id),
                'is_liked'                   => $this->is_liked($current_user_id, "user", $profile->id),
                'is_share'                   => $this->is_share($current_user_id, $profile->id),
                'like_count'                 => Like::where("type", "user")->where("like_id", $profile->id)->count(),
                'user_id'                    => $profile->user_id,
                'avatar'                     => $profile->avatar,
                'birthday'                   => $profile->birthday,
                'type'                       => $profile->type,
                'email'                      => $profile->email,
                'hometown'                   => $profile->hometown,
                'height'                     => $profile->height,
                'weight'                     => $profile->weight,
                'mobile'                     => $profile->mobile,
                'gender'                     => $profile->gender,
                'language'                   => $profile->language,
                'college'                    => $profile->college,
                'speciality'                 => $profile->speciality,
                'introduction'               => $profile->introduction,
                'constellation'              => $profile->constellation,
                'blood_type'                 => $profile->blood_type,
                'work_ex'                    => $profile->work_ex,
                'prize_ex'                   => $profile->prize_ex,
                'before_position'            => $profile->before_position,
                'behind_position'            => $profile->behind_position,
                'self_video_url'             => $profile->self_video_url,
                'collection_video_url'       => $profile->collection_video_url,
                'self_video_cover_url'       => $profile->self_video_obj,
                'collection_video_cover_url' => $profile->collection_video_obj,
                'schedule'                   => $profile->schedule,
                'pic_urls'                   => $profile->pic_urls(),
            ]);
        }

        return $jsons;
    }

    /**
     * 将艺人资料,幕后大咖的搜索type转换为实际的数据库字段
     * 因为两者不是一个字段,但是整体都是profilemodel,所以放在了一起
     * 1.如果搜索的是台前身份,映射字段为台前身份
     * 2.如果搜索的是幕后身份,映射的字段为幕后身份,
     * 3.如果没有指明,映射为默认搜索地段type
     * @param Request $request
     * @return string
     */
    private function mapTypeToDBAttribute(Request $request)
    {
        $position = $request->input('position');
        if ($position == 'before') {
            return 'before_position';
        }
        elseif ($position == 'behind') {
            return 'behind_position';
        }
        else {
            return 'type';
        }
    }

}
