<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Company;
use App\Models\Message;
use App\Models\MovieBasement;
use App\Models\Profile;
use App\User;
use App\Models\PushRecord;
use App\Models\TradeScript;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Predis;

class TradeResourcesController extends BaseController
{
    /**
     *
     */
    public function index(Request $request)
    {
        $userId = $this->getCurrentUserByRequest($request)->FID;

        return view('mobile.trade_resources.index', compact('userId'));
    }


    /**
     * 公司界面
     */
    public function indexCompanies()
    {
        $companies = Company::all();

        return view('mobile.trade_resources.companies.index', compact('companies'));
    }

    /**
     * 可交易剧本
     */
    public function indexScripts()
    {
        $scripts = TradeScript::all();

        return view('mobile.trade_resources.scripts.index', compact('scripts'));
    }

    /**
     * 大基地
     *
     *
     */
    public function indexBasement()
    {
        $basements = MovieBasement::where('type', 'basement')->orderBy('sort', 'desc')->get();
        $title     = '影视基地';
        return view('mobile.trade_resources.basement.index', compact('basements','title'));
    }

    /**
     * 摄影器材
     *
     *
     */
    public function indexPhotographEquipment()
    {
        $basements = MovieBasement::where('type', 'photographEquip')->orderBy('sort', 'desc')->get();
        $title     = '摄影器材';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    /**
     * 公司详情
     *
     * @param $companyId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCompany($companyId, Request $request)
    {
        $from    = $request->input('from');
        $company = Company::find($companyId);
        if (!$company) {
            abort(404);
        }
/*
        if ($introduction = Predis::get(request()->fullUrl())) {
            $introduction = json_decode($introduction);
            return view('mobile.trade_resources.companies.show', compact('company', 'from', 'introduction'));
        }*/

        $introduction = Markdown::convertToHtml($company->introduction);

    /*    Predis::set(request()->fullUrl(), json_encode($introduction), 'EX', 86400, 'NX');*/

        return view('mobile.trade_resources.companies.show', compact('company', 'from', 'introduction'));
    }

    /**
     * 剧本详情
     *
     * @param $scriptId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showScript($scriptId, Request $request)
    {
        $script = TradeScript::find($scriptId);
        if (!$script) {
            abort(404);
        }
        $from = $request->input('from');
        return view('mobile.trade_resources.scripts.show', compact('script', 'from'));
    }

    /**
     * 创建新的合作要约
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCoopration(Request $request,$type){
        $basementId = $request->input('basementId');
        $userId     = $request->input('userId');
        return view('mobile.trade_resources.coopration',compact('basementId','userId','type'));
    }
    public function sendCV(Request $request)
    {
        return $this->searchThisUserProfileIsQualified($request);
    }

    public function searchThisUserProfileIsQualified(Request $request)
    {
        $userId    = $request->input('userId');
        $profileId = Profile::where('user_id', $userId)->value('ID');
        if (!$profileId) {
            return $this->responseSuccess('0', []);
        }
        return $this->responseSuccess('1', ['ProfileId' => $profileId]);
    }

    public function sendProfile(Request $request,$type)
    {
        $scope_id = '';
        if($type=='basement'){
            $basementId              = $request->input('basementId');
            $scope_id                = MovieBasement::where('id', $basementId)->value('receive_user_ids');
        }
        if($type=='company'){
            $companyId               = $request->input('basementId');
            $scope_id                = Company::where('id', $companyId)->value('receive_user_ids');
        }
        if($type=='script'){
            $scriptId                = $request->input('basementId');
            $scope_id                = TradeScript::where('id', $scriptId)->value('receive_user_ids');
        }


        $profileId               = $request->input('profileId');
        $newMessage              = [];
        $newMessage['scope_ids'] = $scope_id;
        //5表示 发送的是简历
        $newMessage['from']      = 5;
        $newMessage['content']   = $profileId;
        //若 有title 这些字段   证明点击的是 寻求合作
        if($request->input('title')&&$request->input('content')){
            $newMessage['from']      = 6;
            $newMessage['title']     = '您有新的合作邀约';
            $newMessage['type']      = 'SYSTEM';
            $newMessage['content']   = $profileId.','.$request->input('title').','.$request->input('content').','.$request->input('phone').','.$request->input('wx');
        }

        Message::create($newMessage)->getMessageUri()->push();
    }

    /**
     * 大基地详情
     *
     */
    public function showBasement($basementId, Request $request)
    {
        $basement = MovieBasement::find($basementId);
        if (!$basement) {
            abort(404);
        }
        $from = $request->input('from');
        return view('mobile.trade_resources.basement.show', compact('basement', 'from'));
    }

    //light
    public function indexLightEquipment()
    {
        $basements = MovieBasement::where('type', 'lightEquip')->orderBy('sort', 'desc')->get();
        $title     = '灯光器材';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    //overseasRecord
    public function indexOverseasRecord()
    {
        $basements = MovieBasement::where('type', 'overseasRecord')->orderBy('sort', 'desc')->get();
        $title     = '海外协拍';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    //yinxiaoCompany
    public function indexYinxiaoCompany()
    {
        $basements = MovieBasement::where('type', 'yinxiaoCompany')->orderBy('sort', 'desc')->get();
        $title     = '营销公司';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    //economyCompany
    public function indexEconomyCompany()
    {
        $basements = MovieBasement::where('type', 'economyCompany')->orderBy('sort', 'desc')->get();
        $title     = '经纪公司';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    //specialEffect
    public function indexSpecialEffect()
    {
        $basements = MovieBasement::where('type', 'specialEffect')->orderBy('sort', 'desc')->get();
        $title     = '后期特效';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    //pray
    public function indexPray()
    {
        $basements = MovieBasement::where('type', 'pray')->orderBy('sort', 'desc')->get();
        $title     = '仪式祈福';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    //lawassist
    public function indexLawassist()
    {
        $basements = MovieBasement::where('type', 'lawassist')->orderBy('sort', 'desc')->get();
        $title     = '法律服务';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    public function indexItems()
    {
        $basements = MovieBasement::where('type', 'items')->orderBy('sort', 'desc')->get();
        $title     = '道具器材';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    public function indexStudios()
    {
        $basements = MovieBasement::where('type', 'studio')->orderBy('sort', 'desc')->get();
        $title     = 'casting工作室';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    public function indexDesserts()
    {
        $basements = MovieBasement::where('type', 'dessert')->orderBy('sort', 'desc')->get();
        $title     = '甜品冷餐';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    public function indexUniforms()
    {
        $basements = MovieBasement::where('type', 'uniform')->orderBy('sort', 'desc')->get();
        $title     = '服装服饰';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    public function indexInsurances()
    {
        $basements = MovieBasement::where('type', 'insurance')->orderBy('sort', 'desc')->get();
        $title     = '保险服务';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

    public function indexHotels()
    {
        $basements = MovieBasement::where('type', 'hotel')->orderBy('sort', 'desc')->get();
        $title     = '剧组宾馆';
        return view('mobile.trade_resources.basement.index', compact('basements', 'title'));
    }

}
