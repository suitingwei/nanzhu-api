<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\Picture;
use Cache;
use Illuminate\Http\Request;
use Log;
use Predis;

/**
 * Class BlogsController
 * @package App\Http\Controllers\Api
 */
class BlogsController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //1. 初始化选择审核通过未删除的某类型
        $blogs = Blog::notDeleted()->approved()->orderby("created_at", "desc");

        //2. 根据type进行搜索
        $this->searchByType($blogs, $request);

        //2.根据类型进行搜索
        $this->searchByTypeValue($blogs, $request);

        //3. 根据title/content搜索
        $this->searchByTitleAndContent($blogs, $request);

        //4. 获取blogs
        $blogs = $blogs->paginate(10);

        //5. 组织数据
        $s = $this->formatBlogsJson($blogs, $this->current_user($request), $request);

        return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $s]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(Request $request)
    {
        $type = $request->get("type");

        $type_arr = Blog::type_arr()[$type];

        return response()->json(["type_arr" => $type_arr]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        Log::info($data);
        $blog    = Blog::create($data);
        $pic_url = $request->get("pic_url");

        if ($pic_url) {
            $arr = explode(",", $pic_url);
            foreach ($arr as $a) {
                $pictrue          = new Picture;
                $pictrue->url     = $a;
                $pictrue->blog_id = $blog->id;
                $pictrue->save();
            }
        }

        return response()->json(["message" => "创建成功"]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            $blog->is_delete = 1;
            $blog->save();
            return response()->json(["message" => "删除成功"]);
        }
        return response()->json(["message" => "删除失败"]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {

        $blog = Blog::find($id);
        if ($blog) {
            $blog->is_liked = 1;
            $blog->increment('read_count');
            \Log::info('===访问一个blog' . $blog);
            return response()->json(["ret" => 0, "msg" => "成功", "blog" => $blog]);
        }
    }

    /**
     * 格式化业内动态的数据
     *
     * @param         $blogs
     * @param         $current_user_id
     * @param Request $request
     *
     * @return array
     * @internal param $type
     *
     */
    private function formatBlogsJson($blogs, $current_user_id, Request $request)
    {
        $type = $request->input('type');

        $arrs = [];
        $s    = [];
        foreach ($blogs as $item) {
            $arrs[$item->toArray()['d']] = "";
        }
        foreach ($blogs as $blog) {
            if (array_key_exists($blog->toArray()['d'], $arrs)) {
                $json                          = [];
                $json['is_favorite']           = $this->is_favorite($current_user_id, $type, $blog->id);
                $json['id']                    = $blog->id;
                $json['author']                = $blog->toArray()['author'];
                $json['type']                  = $blog->type;
                $json['content']               = strip_tags($blog->content);
                $json['is_liked']              = $this->is_liked($current_user_id, $type, $blog->id);
                $json['is_approved']           = $blog->is_approved;
                $json["title"]                 = $blog->title;
                $json["created_at"]            = $blog->toArray()['created_at'];
                $json['type_value']            = $blog->type_value;
                $json['pictures']              = $blog->pictures();
                $arrs[$blog->toArray()['d']][] = $json;
            }
        }
        foreach ($arrs as $key => $a) {
            $s[] = ["date" => $key, "data" => $a];
        }

        return $s;
    }

    /**
     * @param         $blogs
     * @param Request $request
     *
     */
    private function searchByTitleAndContent(&$blogs, Request $request)
    {
        $searchValue = $request->get("q");

        if ($searchValue && $searchValue != '') {
            $blogs->where(function ($query) use ($searchValue) {
                $query->orWhere('title', 'like', "%{$searchValue}%")
                      ->orWhere('content', 'like', "%{$searchValue}%");
            });
        }
    }

    /**
     * @param         $blogs
     * @param Request $request
     *
     * @return mixed
     */
    private function searchByTypeValue(&$blogs, Request $request)
    {
        if (!($type_value = $request->input('type_value'))) {
            return;
        }

        //原来有一个最热的类型,但是现在的最热是按照点击量计算,所以查询所有de
        if ($type_value == '全部' || $type_value == '最热') {
            $type_value = '%';
        }

        $blogs->where('type_value', 'like', $type_value);

        //如果是搜索最热,按照阅读次数排序
        if ($type_value == '最热') {
            $blogs->orderBy('read_count', 'desc');
        }
    }

    /**
     * 根据类型进行搜搜
     *
     * @param         $blogs
     * @param Request $request
     *
     * @return mixed
     */
    private function searchByType(&$blogs, Request $request)
    {
        $blogs->where('type', $request->input('type'));
    }

    public function clearCache(Blog $blog)
    {
        $keys  = Predis::keys("*/apiv2.nanzhuxinyu.com/mobile/blogs/{$blog->id}*");
        $count = 0;
        foreach ($keys as $key) {
			Predis::del($key);
            $count++;
        }
        return $count;
    }
}
