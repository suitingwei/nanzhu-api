<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Blog;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class BlogsController extends BaseController
{
    //
    public function show(Request $request, $id)
    {
        $blog = Blog::find($id);
        $blog->increment('read_count');
        $from = $request->get("from");

        if ($content = Redis::get(request()->fullUrl())) {
            return view("mobile.blogs.show", ['from' => $from, "blog" => $blog, "content" => json_decode($content)]);
        }

        $content = Markdown::convertToHtml($blog->content);
        Redis::set(request()->fullUrl(), json_encode($content), 'EX', 86400, 'NX');

        return view("mobile.blogs.show", ['from' => $from, "blog" => $blog, "content" => $content]);
    }
}
