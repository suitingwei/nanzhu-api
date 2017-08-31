<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\Company;
use App\Models\MovieBasement;
use App\Models\Profile;
use App\Models\TradeScript;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SearchController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    private $searchParameter;

    const limitCount = 4;

    public function __construct(Request $request)
    {
        $this->request         = $request;
        $this->searchParameter = $request->input('q');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        if ($this->searchParameter == '') {
            return $this->responseSuccess();
        }
        return $this->responseSuccess('操作成功', [
            'before_profiles' => $this->searchBriefProfiles(self::limitCount, 'before'),
            'behind_profiles' => $this->searchBriefProfiles(),
            'trade_resources' => $this->searchBriefTradeResources(),
            'industry_trends' => $this->searchBriefIndustryTrends(),
        ]);
    }

    public function searchMore(Request $request)
    {
        static $allowMethods = [
            //'BeforeProfiles',
            //'BehindProfiles',
            'TradeResources',
            //'IndustryTrends',
        ];

        $type = $request->input('type');
        if ($this->searchParameter == "") {
            return $this->responseSuccess();
        }

        if (!in_array($type, $allowMethods)) {
            return $this->responseSuccess();
        }

        if ($type == 'BeforeProfiles' || $type == 'BehindProfiles') {

            $thistype   = strtolower(substr($type, 0, 6));
            $type       = 'Profiles';
            $snakeType  = Str::snake($type);
            $methodName = 'searchBrief' . $type;
            return $this->responseSuccess([
                $snakeType => call_user_func_array([$this, $methodName], array(null, $thistype))
            ]);
        }
        $snakeType = Str::snake($type);

        $methodName = 'searchBrief' . $type;
        return $this->responseSuccess('操作成功', [
            $snakeType => call_user_func_array([$this, $methodName], array(null))
        ]);
    }

    /**
     * @param int $takCount
     *
     * @return array
     */
    public function searchBriefTradeResources($takCount = self::limitCount)
    {

        $companies = Company::where('title', 'like', "%{$this->searchParameter}%")
                            ->orWhere('plain_introduction', 'like', "%{$this->searchParameter}%")
                            ->get()
                            ->map(function (Company $company) {
                                $url = env('APP_URL');
                                return [
                                    'id'      => $company->id,
                                    'cover'   => $company->logo,
                                    'title'   => $company->title,
                                    'q'       => $this->searchParameter,
                                    'content' => $company->short_introduction,
                                    'url'     => $url . '/mobile/trade-resources/companies/' . $company->id . '?can_share=true&wechat_share_json=' . urlencode($company->wechat_share_json) . '&from=app',
                                ];
                            });

        $scripts = TradeScript::where('title', 'like', "%{$this->searchParameter}%")
                              ->orWhere('plain_content', 'like', "%{$this->searchParameter}%")
                              ->get()
                              ->map(function (TradeScript $script) {
                                  $url = env('APP_URL');
                                  return [
                                      'id'      => $script->id,
                                      'cover'   => $script->logo,
                                      'title'   => $script->title,
                                      'q'       => $this->searchParameter,
                                      'content' => $script->short_introduction,
                                      'url'     => $url . '/mobile/trade-resources/scripts/' . $script->id . '?can_share=true&wechat_share_json=' . urlencode($script->wechat_share_json) . '&from=app',
                                  ];
                              });

        if (!$takCount) {
            $prays    = $basements = $hotels =
            $marketingCompanies = $lawAssistant =
            $specialEffects = $photographEquipments =
            $desserts = $items = $lightEquipments =
            $overseasRecords = $uniforms =
            $studios = $insurances =
            $economyCompanies = [];
            $allDatas = $this->searchInVarietyBasements('all');
            foreach ($allDatas as $allData) {
                switch ($allData['type']) {
                    case 'pray':
                        array_push($prays, $allData);
                        break;
                    case 'lawassist':
                        array_push($lawAssistant, $allData);
                        break;
                    case 'uniform':
                        array_push($uniforms, $allData);
                        break;
                    case 'basement':
                        array_push($basements, $allData);
                        break;
                    case 'yinxiaoCompany':
                        array_push($marketingCompanies, $allData);
                        break;
                    case 'specialEffect':
                        array_push($specialEffects, $allData);
                        break;
                    case 'photographEquip':
                        array_push($photographEquipments, $allData);
                        break;
                    case 'dessert':
                        array_push($desserts, $allData);
                        break;
                    case 'items':
                        array_push($items, $allData);
                        break;
                    case 'lightEquip':
                        array_push($lightEquipments, $allData);
                        break;
                    case 'overseasRecord':
                        array_push($overseasRecords, $allData);
                        break;
                    case 'studio':
                        array_push($studios, $allData);
                        break;
                    case 'insurance':
                        array_push($insurances, $allData);
                        break;
                    case 'economyCompany':
                        array_push($economyCompanies, $allData);
                        break;
                    case 'hotel':
                        array_push($hotels,$allData);
                        break;
                }
            }

            return array(
                'companies'            => $companies->all(),
                'scripts'              => $scripts->all(),
                'basements'            => $basements,
                'prays'                => $prays,
                'marketingCompanies'   => $marketingCompanies,
                'lawAssistant'         => $lawAssistant,
                'specialEffects'       => $specialEffects,
                'photographEquipments' => $photographEquipments,
                'desserts'             => $desserts,
                'economyCompanies'     => $economyCompanies,
                'items'                => $items,
                'lightEquipments'      => $lightEquipments,
                'overseasRecords'      => $overseasRecords,
                'uniforms'             => $uniforms,
                'studios'              => $studios,
                'insurances'           => $insurances,
                'hotels'               => $hotels,
            );
        }
        $basements = MovieBasement:: where('type', '!=', 'companyvip')
                                  ->where(function ($query) {
                                      $query->where('title', 'like', "%{$this->searchParameter}%")
                                            ->orWhere('introduction', 'like', "%{$this->searchParameter}%");
                                  })
                                  ->get()
                                  ->map(function (MovieBasement $company) {
                                      $url = env('APP_URL');
                                      return [
                                          'id'      => $company->id,
                                          'cover'   => $company->cover,
                                          'title'   => $company->title,
                                          'q'       => $this->searchParameter,
                                          'type'    => $company->type,
                                          'content' => $company->short_introduction,
                                          'url'     => $url . '/mobile/trade-resources/basements/' . $company->id . '?can_share=true&wechat_share_json=' . urlencode($company->wechat_share_json) . '&from=app',
                                      ];
                                  });

        return array_slice(array_merge($companies->all(), $scripts->all(), $basements->all()), 0, 4);
    }

    /**
     * @param int    $takeCount
     *
     * @param string $type
     *
     * @return Collection BehindProfiles
     */
    private function searchBriefProfiles($takeCount = self::limitCount, $type = 'behind')
    {
        $profilesQueryBuilder = Profile::shown()->where(function ($query) {
            $query->where('name', 'like', "%{$this->searchParameter}%")
                  ->orWhere('work_ex', 'like', "%{$this->searchParameter}%")
                  ->orWhere('prize_ex', 'like', "%{$this->searchParameter}%")
                  ->orWhere('hometown', 'like', "%{$this->searchParameter}%")
                  ->orWhere('email', 'like', "%{$this->searchParameter}%")
                  ->orWhere('college', 'like', "%{$this->searchParameter}%");
        });

        if ($type == 'before') {
            $profilesQueryBuilder = $profilesQueryBuilder->beforeScene();
        }
        else {
            $profilesQueryBuilder = $profilesQueryBuilder->behindScene();
        }

        if (!is_null($takeCount)) {
            $profilesQueryBuilder = $profilesQueryBuilder->take($takeCount);
        }
        return $profilesQueryBuilder->orderBy('sort', 'desc')
                                    ->get()
                                    ->map(function (Profile $profile) {
                                        return [
                                            'avatar'     => $profile->avatar,
                                            'profile_id' => $profile->id,
                                            'user_id'    => $profile->user_id,
                                            'q'          => $this->searchParameter,
                                            'name'       => $profile->name,
                                        ];
                                    });
    }

    /**
     * @param int $takCount
     *
     * @return Collection Trends
     */
    private function searchBriefIndustryTrends($takCount = self::limitCount)
    {
        $blogQueryBuduilers = Blog::news()
                                  ->notDeleted()
                                  ->approved()
                                  ->where(function ($query) {
                                      $query->where('content', 'like', "%{$this->searchParameter}%")
                                            ->orWhere('title', 'like', "%{$this->searchParameter}%");
                                  });

        if (!is_null($takCount)) {
            $blogQueryBuduilers = $blogQueryBuduilers->take($takCount);
        }

        return $blogQueryBuduilers->orderBy('created_at', 'desc')
                                  ->get()
                                  ->map(function (Blog $profile) {
                                      $data = $profile->toArray();
                                      return array_merge($data, [
                                          'content' => strip_tags($profile->short_introduction),
                                          'q'       => $this->searchParameter,
                                      ]);
                                  });

    }

    private function searchInVarietyBasements($type)
    {
        $thisData = MovieBasement::where(function ($query) {
            $query->where('title', 'like', "%{$this->searchParameter}%")
                  ->orWhere(function ($query) {
                      $query->where('introduction', 'like', "%{$this->searchParameter}%");
                  });
        })->get()
                                 ->map(function (MovieBasement $company) {
                                     $url = env('APP_URL');
                                     return [
                                         'id'      => $company->id,
                                         'cover'   => $company->cover,
                                         'title'   => $company->title,
                                         'type'    => $company->type,
                                         'q'       => $this->searchParameter,
                                         'content' => $company->short_introduction,
                                         'url'     => $url . '/mobile/trade-resources/basements/' . $company->id . '?can_share=true&wechat_share_json=' . urlencode($company->wechat_share_json) . '&from=app',
                                     ];
                                 });

        return $thisData;


    }
}
