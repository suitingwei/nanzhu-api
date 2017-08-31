<?php
/**
 * Created by PhpStorm.
 * User: sui
 * Date: 2017/1/18
 * Time: 下午12:21
 */

namespace App\Traits;

/**
 * Class SocialSecurityOptions
 * @package App\Traits
 */
use App\Models\SocialSecurityPrice;

/**
 * Class SocialSecurityOptions
 * Share the same options config among social security, order, price models.
 * @package App\Traits
 */
trait SocialSecurityOptions
{
    public static $typeNotFirst = 0;
    public static $typeFirst = 1;
    public static $typeFromOutBeijing = 2;

    public static $socialSecurityTypes = [
        ['value' => 0, 'desc' => '社保转入'],
        ['value' => 1, 'desc' => '初次参保'],
        ['value' => 2, 'desc' => '外埠转入北京'],
    ];

    /**
     * All optional banks
     * @var array
     */
    public static $banks = [
        '工商银行',
        '光大银行',
        '华夏银行',
        '建设银行',
        '交通银行',
        '民生银行',
        '农业银行',
        '邮政储蓄银行',
        '招商银行',
        '中信银行',
        '兴业银行',
        '中国银行',
    ];

    public static $defaultServicePrice = 30;

    public static $costMonths = [1, 3, 6, 12];

    public static $baseNumbers = ['3082'];

    public static $hukouTypes = ['北京城镇', '北京农村', '外埠城镇', '外埠农村'];

    public static $servicePhone = '13701351123';

    public static $minorities = [
        '汉族',
        '满族',
        '蒙古族',
        '回族',
        '藏族',
        '维吾尔族',
        '苗族',
        '彝族',
        '壮族',
        '布依族',
        '侗族',
        '瑶族',
        '白族',
        '土家族',
        '哈尼族',
        '哈萨克族',
        '傣族',
        '黎族',
        '傈僳族',
        '佤族',
        '畲族',
        '高山族',
        '拉祜族',
        '水族',
        '东乡族',
        '纳西族',
        '景颇族',
        '柯尔克孜族',
        '土族',
        '达斡尔族',
        '仫佬族',
        '羌族',
        '布朗族',
        '撒拉族',
        '毛南族',
        '仡佬族',
        '锡伯族',
        '阿昌族',
        '普米族',
        '朝鲜族',
        '塔吉克族',
        '怒族',
        '乌孜别克族',
        '俄罗斯族',
        '鄂温克族',
        '德昂族',
        '保安族',
        '裕固族',
        '京族',
        '塔塔尔族',
        '独龙族',
        '鄂伦春族',
        '赫哲族',
        '门巴族',
        '珞巴族',
        '基诺族',
    ];

    /**
     * @var string
     */
    public static $serviceInfoContent = <<<content
1.新参保每月14日24:00前，外埠转入北京每月14日24:00前，社保转入每月18日24:00前可办理当月的社保，超过截止日期以后仅可以办理下个月以后的社保服务。 

2.如果您在没有生效订单的情况下，于当月截止日期前没有购买，我们会为您办理停保。

3.每个服务操作需两个工作日左右的时间，类似减员等服务请务必提前申请，没有续费会默认无需我方代缴，会在次日办理减员。

4.以前曾在单位参保，第一次下单购买前请确认前单位已经为您减员成功。由于前单位没有减员而导致的购买不成功，我方不负责。

5.因用户原因办理不成功，或者用户符合条件申请的退款，扣除支付宝/微信收取的2%手续费。退款一般于月底或次月初前完成。

6.办理社保卡一般需要3-4个月，停保状态不能办卡；办理成功后会以快递方式送达，快递费到付。

7.每年政府会调整社保缴费基数，我们会第一时间通知您。如果因政府调整通知滞后导致划扣款金额不准确，我们承诺多退少补。

8.南竹的社保服务已经筹划许久，志在以最低的价格让业内人能安心享受社会福利（在北京购车、购房指标），但由于用户不与南竹及北京掌动易迅科技有限责任公司产生劳动关系，故用户在加入南竹社保服务期间，发生工伤和生育报销时，无法享受此项保险产生的待遇。建议用户自行购买其他相关商业保险。

9.本社保服务仅适用于18周岁至法定退休年龄之间的用户。
content;

    /**
     * @var array
     */
    public static $serviceInfoBanners = [
        [
            'image'    => 'http://nanzhu.oss-cn-shanghai.aliyuncs.com/banners/social-security-banner-2.jpg',
            'jump_url' => null
        ]
    ];

    /**
     * @var string
     */
    public static $serviceInfoTitle = '相关说明';

    public static $socialSecurityOfficalUrl = 'http://www.bjrbj.gov.cn/csibiz/home/';

    public static function getOptions()
    {
        $prices = SocialSecurityPrice::whereIn('base_number', static::$baseNumbers)->get([
            'base_number',
            'hukou_type',
            'total_price'
        ]);

        $results = [];
        foreach ($prices as $price) {
            $tempPriceArray = [
                'hukou_type' => $price->hukou_type,
                'price_desc' => "{$price->base_number}元/月工资标准,实缴{$price->total_price}元/月",
            ];

            if (($index = array_search($price->base_number, array_column($results, 'base_number'))) !== false) {
                array_push($results[$index] ['prices'], $tempPriceArray);
                continue;
            }

            $results[] = [
                'base_number'      => $price->base_number,
                'base_number_desc' => '基础档',
                'prices'           => [$tempPriceArray]
            ];
        }

        return [
            'minorities'              => static::$minorities,
            'base_numbers'            => static::$baseNumbers,
            'cost_months'             => static::$costMonths,
            'hukou_type'              => static::$hukouTypes,
            'bank'                    => static::$banks,
            'types'                   => static::$socialSecurityTypes,
            'base_numbers_and_prices' => $results,
        ];
    }
}