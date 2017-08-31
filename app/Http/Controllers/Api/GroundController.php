<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Company;
use App\Models\MovieBasement;
use App\Models\Product;
use App\Models\Profile;
use App\Models\TradeScript;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * 广场界面
 * Class GroundController
 * @property  companies_count
 * @package App\Http\Controllers\Api
 */
class GroundController extends BaseController
{
    private $behindSceneBannersCount = 20;
    private $beforeSceneBannersCount = 20;
    private $industryTrendBannersCount = 20;
    private $productBannersCount = 5;
    private $companiesBannersCount = 5;
    private $tradeScriptesCount = 5;

    /**
     * Professional introduction video urls.
     * The first is the man,and the second is the girl.
     * @var array
     */
    private $video_url = [
        'http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/professional/3_new.mp4',
        'http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/professional/2.mp4'
    ];

    /**
     * The cover url of the professional introduction videos.
     * @var array
     */
    private $videoCoverUrl = [
        'http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/transcoded/582452e625470.gif',
        'http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/transcoded/58216bc8b1ebc.gif'
    ];

    public static $tradeScriptsLogos = [
        'http://nanzhu.oss-cn-shanghai.aliyuncs.com/trade-scripts/1.jpg',
        'http://nanzhu.oss-cn-shanghai.aliyuncs.com/trade-scripts/2.jpg',
        'http://nanzhu.oss-cn-shanghai.aliyuncs.com/trade-scripts/3.jpg',
        'http://nanzhu.oss-cn-shanghai.aliyuncs.com/trade-scripts/4.jpg',
        'http://nanzhu.oss-cn-shanghai.aliyuncs.com/trade-scripts/5.jpg',
    ];

    /**
     * The wechat share url of the professional introduction video.
     * @var string
     */
    private $professionalVideoShareUrl = 'https://dev.nanzhuxinyu.com/video-intro.php';

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /*
     *--------------------------------------------------------------
     * The gound banners.
     *--------------------------------------------------------------
     * 1.top banners.
     * 2.Behind scene character's banners.
     * 3.Before scene character's banners.
     * 4.Industry trend banners.
     */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $bannersData = [];

        $this->appendTopBanners($bannersData)
             ->appendBehindSceneBanners($bannersData)
             ->appendBeforeSceneBanners($bannersData)
             ->appendIndustryTrendBanners($bannersData)
             ->appendProductsBanners($bannersData)
             ->appendTradeResourcesH5EntryUrl($bannersData)
             ->appendTradeResources($bannersData);

        return $this->responseSuccess('操作成功', $bannersData);
    }

    /**
     * @param Request $request
     * @param         $sceneBanners
     */
    private function getInfoWithUser(Request $request, $sceneBanners)
    {
        $userId = $this->current_user($request);

        $sceneBanners->map(function ($before) use ($userId) {
            $before->is_liked    = $this->is_liked($userId, "user", $before->id);
            $before->is_share    = $this->is_share($userId, $before->id);
            $before->is_favorite = $this->is_favorite($userId, "user", $before->id);
        });

        return $sceneBanners;
    }

    /**
     * 返回专业视频界面的视频url
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfessionalVideoUrl()
    {
        $result = [];

        foreach ($this->video_url as $index => $videoUrl) {
            $result [] = $this->buildVideoObj($this->videoCoverUrl[$index], $videoUrl);
        }

        return $this->responseSuccess('操作成功', [
            'video_url'       => $this->video_url,
            'video_cover_url' => $result,
            'share_data'      => $this->getShareProfessionalData()
        ]);
    }

    /**
     * 获取分享专业版视频的数据
     */
    private function getShareProfessionalData()
    {
        return [
            'url'     => $this->professionalVideoShareUrl,
            'title'   => '南竹通告单+',
            'content' => '专业版视频分享链接',
            'cover'   => 'http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/video_logo_new.png'
        ];
    }

    /**
     * 组件视频对象
     * @param $coverUrl
     * @param $videoUrl
     * @return \stdClass
     */
    public function buildVideoObj($coverUrl, $videoUrl)
    {
        $videoObj            = new \stdClass();
        $videoObj->cover_url = $coverUrl;
        $videoObj->video_url = $videoUrl;
        return $videoObj;
    }

    /**
     * @param array $bannersData
     */
    private function appendTradeResources(array &$bannersData)
    {
        $this->appendCompanies($bannersData)->appendTradeScripts($bannersData)->appendOtherTradeResources($bannersData);
        return $this;
    }

    /**
     * @param $bannersData
     * @return $this
     */
    private function appendTopBanners(array &$bannersData)
    {
        $banners = Banner::orderBy("sort")->where('is_show', 0)->where(function ($query) {
            $query->whereNull('product_id')->orWhere('product_id', 0);
        })->get();

        $bannersData['top_banners'] = $banners;
        return $this;
    }

    /**
     * @param array $bannersData
     * @return $this
     */
    private function appendBehindSceneBanners(array &$bannersData)
    {
        $behindSceneBanners = Profile::shown()
                                     ->behindScene()
                                     ->orderBy('sort', 'desc')
                                     ->take($this->behindSceneBannersCount)
                                     ->get();

        $behindSceneBanners = $this->getInfoWithUser($this->request, $behindSceneBanners);

        $bannersData['behind_banners'] = $behindSceneBanners;

        return $this;
    }

    /**
     * @param $bannersData
     * @return $this
     */
    private function appendBeforeSceneBanners(array &$bannersData)
    {
        $beforeSceneBanners = Profile::shown()
                                     ->beforeScene()
                                     ->notBehindScene()
                                     ->orderBy('sort', 'desc')
                                     ->take($this->beforeSceneBannersCount)
                                     ->get();

        $beforeSceneBanners = $this->getInfoWithUser($this->request, $beforeSceneBanners);

        $bannersData ['before_banners'] = $beforeSceneBanners;

        return $this;
    }

    /**
     * @param $bannersData
     * @return $this
     */
    private function appendIndustryTrendBanners(array &$bannersData)
    {
        $banners = Blog::news()
                       ->notDeleted()
                       ->approved()
                       ->orderBy('created_at', 'desc')
                       ->take($this->industryTrendBannersCount)
                       ->get();

        $bannersData['trend_banners'] = $banners;

        return $this;
    }

    /**
     * @param array $bannersData
     * @return $this
     */
    private function appendProductsBanners(array &$bannersData)
    {
        $products = Product::shown()->orderBy('sort', 'desc')->take($this->productBannersCount)->get()->map(function ($product) {
            return [
                'id'             => $product->id,
                'title'          => $product->title,
                'price'          => $product->price,
                'original_price' => $product->original_price,
                'product_cover'  => $product->product_cover,
            ];
        });

        $bannersData['product_banners'] = $products;

        return $this;
    }

    /**
     * @param array $bannersData
     * @return $this
     */
    private function appendTradeResourcesH5EntryUrl(array &$bannersData)
    {
        $bannersData ['industry_resources_h5_url'] = env('APP_URL') . '/mobile/trade-resources?user_id=' . $this->request->input('user_id') . '&can_share=false&title=业内资源';
        return $this;
    }

    /**
     * @param $bannersData
     * @return $this;
     */
    private function appendCompanies(array &$bannersData)
    {
        $companies = Company::orderBy('created_at', 'desc')->take($this->companiesBannersCount)->select('id', 'logo', 'title')->get()->map(function (Company $company) {
            $url = env('APP_URL');
            return [
                'id'    => $company->id,
                'title' => $company->title,
                'logo'  => $company->logo,
                'url'   => $url . '/mobile/trade-resources/companies/' . $company->id . '?can_share=true&wechat_share_json=' . urlencode($company->wechat_share_json) . '&from=app&user_id=' . $this->request->input('user_id'),
            ];
        });;
        $h5url                          = env('APP_URL') . '/mobile/trade-resources/companies?from=app&user_id=' . $this->request->input('user_id');
        $resDate                        = array(
            'data'   => $companies,
            'h5_url' => $h5url,
        );
        $bannersData['company_banners'] = $resDate;

        return $this;
    }

    /**
     * @param $bannersData
     * @return $this
     */
    private function appendTradeScripts(array &$bannersData)
    {
        $scripts = TradeScript::orderBy('created_at', 'desc')->take($this->tradeScriptesCount)->select('id', 'title')->get()->map(function (TradeScript $company) {
            $url = env('APP_URL');
            return [
                'id'    => $company->id,
                'title' => $company->title,
                'url'   => $url . '/mobile/trade-resources/scripts/' . $company->id . '?can_share=true&wechat_share_json=' . urlencode($company->wechat_share_json) . '&from=app&user_id=' . $this->request->input('user_id'),
            ];
        })->all();


        foreach (self::$tradeScriptsLogos as $index => $logo) {
            if (!isset($scripts[$index])) {
                continue;
            }
            $scripts[$index]['logo'] = $logo;
        }

        $h5url                                = env('APP_URL') . '/mobile/trade-resources/scripts?from=app&user_id=' . $this->request->input('user_id');
        $resDate                              = array(
            'data'   => $scripts,
            'h5_url' => $h5url,
        );
        $bannersData['trade_scripts_banners'] = $resDate;

        return $this;
    }

    /**
     * @param $bannersData
     * @return $this;
     */
    private function appendOtherTradeResources(array &$bannersData)
    {
        foreach (MovieBasement::types() as $type) {
            $resourceBanners = MovieBasement::orderBy('created_at', 'desc')->where('type', $type)->take($this->companiesBannersCount)->select('id', 'title',
                'cover')->get()->map(function (MovieBasement $company) {
                $url = env('APP_URL');
                return [
                    'id'    => $company->id,
                    'title' => $company->title,
                    'logo'  => $company->cover,
                    'url'   => $url . '/mobile/trade-resources/basements/' . $company->id . '?can_share=true&wechat_share_json=' . urlencode($company->wechat_share_json) . '&from=app&user_id=' . $this->request->input('user_id'),
                ];
            });

            $routeType = $this->getTradeResourceIndexUrl($type);
            $h5url     = env('APP_URL') . '/mobile/trade-resources/' . $routeType . '?from=app&user_id=' . $this->request->input('user_id');

            $resDate = array(
                'data'   => $resourceBanners,
                'h5_url' => $h5url,
            );

            $key                = Str::snake($type) . '_banners';
            $bannersData [$key] = $resDate;
        }

        return $this;
    }

    public function getTradeResourceIndexUrl($type)
    {
        switch ($type) {
            case 'basement':
                return 'basements';
            case 'lightEquip':
                return 'lightEquipments';
            case 'yinxiaoCompany':
                return 'yinxiaoCompanies';
            case 'economyCompany':
                return 'economyCompanies';
            case 'specialEffect';
                return 'specialEffects';
            case 'pray':
                return 'prays';
            case 'items':
                return 'items';
            case 'lawassist':
                return 'lawassist';
            case 'studio':
                return 'studios';
            case 'dessert':
                return 'desserts';
            case 'uniform':
                return 'uniforms';
            case 'insurance':
                return 'insurances';
            case 'hotel':
                return 'hotels';
            case "photographEquip":
                return 'photographEquipments';
            case 'overseasRecord':
                return 'overseasRecords';
        }
    }

}
