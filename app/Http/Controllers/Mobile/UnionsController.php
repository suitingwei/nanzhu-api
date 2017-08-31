<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Message;
use App\Models\MovieBasement;
use App\Models\Profile;
use App\Models\Union;
use App\Models\UnionApply;
use App\Models\UnionUserFeedback;
use App\User;
use DB;
use Illuminate\Http\Request;

/**
 * Class UnionsController
 * @package App\Http\Controllers\Mobile
 */
class UnionsController extends BaseController
{
    //首页
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId = $request->input('user_id', null);
        if (!$userId) {
            return view('mobile.unions.soon');
        }
        $user = User::find($userId);
        return view('mobile.unions.index', compact('user'));
    }

    //联盟首页

    /**
     * @param Request $request
     * @param         $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function UnionIndex(Request $request, $unionId)
    {
        $userId                = $request->get('user_id');
        $hasAccessKey          = Union::where('user_id', $userId)->where('union_id', $unionId)->where('type', 'normal')->first();
        $messages              = Message::where(["type" => 'UNIONNOTICE', "union_id" => $unionId])->get();
        $messagesForBignews    = Message::where(["type" => 'UNIONBIGNEWS', "union_id" => $unionId])->get();
        $messagesForCoopration = Message::where(["type" => 'UNIONCOOPRAT', "union_id" => $unionId])->get();
        return view('mobile.unions.Guangdian.index', compact('unionId', 'hasAccessKey', 'userId', 'messages', 'messagesForBignews', 'messagesForCoopration'));
    }

    /**
     * @param Request $request
     * @param         $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Notice(Request $request, $unionId)
    {

        $user_id = $request->get('user_id');

        $messages = Message::where('type', 'UNIONNOTICE')->where('union_id', $unionId)->orderBy('created_at',
            'desc')->get();

        return view('mobile.unions.Guangdian.notice', compact('messages', 'unionId', 'user_id'));
    }

    /**
     * @param Request $request
     * @param         $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Bignews(Request $request, $unionId)
    {
        $user_id  = $request->get('user_id');
        $messages = Message::where('type', 'UNIONBIGNEWS')->where('union_id', $unionId)->orderBy('created_at',
            'desc')->get();
        return view('mobile.unions.Guangdian.bignews', compact('messages', 'unionId', 'user_id'));
    }

    /**
     * @param Request $request
     * @param         $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Coopration(Request $request, $unionId)
    {
        $user_id  = $request->get('user_id');
        $messages = Message::where('type', 'UNIONCOOPRAT')->where('union_id', $unionId)->orderBy('created_at',
            'desc')->get();
        return view('mobile.unions.Guangdian.coopration', compact('messages', 'unionId', 'user_id'));
    }

    /**
     * @param Request $request
     * @param         $detailType
     * @param         $unionId
     * @param         $messageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Detail(Request $request, $detailType, $unionId, $messageId)
    {
        $userId = $request->get('user_id');
        DB::table('message_receivers')->where('message_id', $messageId)->where('receiver_id',
            $userId)->update(['is_read' => 1]);
        $message   = Message::where('id', $messageId)->first();
        $DetailUrl = env('MESSAGE_REQUEST_ROOT_URL');
        return view('mobile.unions.Guangdian.detail', compact('message', 'detailType', 'DetailUrl', 'unionId'));
    }

    //联盟艺人

    /**
     * @param $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function UnionStar($unionId)
    {
        $persons    = Profile::whereIn('union_type', ['star', 'all'])->where('union_id', $unionId)->get();
        $ProfileUrl = env('MESSAGE_REQUEST_ROOT_URL');
        $title      = '联盟艺人推荐';
        return view('mobile.unions.Guangdian.persondetail', compact('persons', 'ProfileUrl', 'title'));
    }

    //个人会员

    /**
     * @param $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function UnionVip($unionId)
    {
        $persons    = Profile::whereIn('union_type', ['normal', 'all'])->where('union_id', $unionId)->get();
        $ProfileUrl = env('MESSAGE_REQUEST_ROOT_URL');
        $title      = '联盟个人会员';
        return view('mobile.unions.Guangdian.persondetail', compact('persons', 'ProfileUrl', 'title'));
    }

    //联盟简介

    /**
     * @param $Unionid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUnion($Unionid)
    {
        $basement = MovieBasement::where('union_id', $Unionid)->where('type', 'union')->first();

        return view('mobile.unions.Guangdian.introduction', compact('basement'));
    }

    /**
     * @param $Unionid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function UnionVipCompany($Unionid)
    {
        $basements = MovieBasement::where('type', 'companyvip')->where('union_id', $Unionid)->orderBy('sort', 'desc')->get();

        return view('mobile.unions.Guangdian.vipcompany', compact('basements'));
    }


    /**
     * @param Request $request
     * @param         $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Feedback(Request $request, $unionId)
    {
        $userId = $request->get('user_id');

        return view('mobile.unions.Guangdian.feedback', compact('unionId', 'userId'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Feedbackpost(Request $request)
    {
        $userId  = $request->input('userId');
        $unionId = $request->input('unionId');
        \Log::info(json_encode($request->all()) . 'xoxoxo');
        $data['title']    = $request->input("title");
        $data['content']  = $request->input('content');
        $data['union_id'] = $unionId;
        $data['user_id']  = $userId;

        UnionUserFeedback::create($data);

        return redirect()->to("/mobile/unions/{$unionId}?user_id={$userId}");

    }

    /**
     * 申请
     * @param Request $request
     * @param         $unionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply(Request $request, $unionId)
    {
        $userId = $request->get('user_id');
        return view('mobile.unions.Guangdian.apply', compact('unionId', 'userId'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postapply(Request $request)
    {
        $applydata = $request->all();
        $userId    = $request->input('user_id');
        $unionId   = $request->input('union_id');
        UnionApply::create($applydata);
        return redirect()->to("/mobile/unions/{$unionId}?user_id={$userId}");
    }

}


