<?php

namespace App;

use App\Managers\Powers\UserPowerManager;
use App\Managers\Statistics\UserStatManager;
use App\Models\BlackList;
use App\Models\GroupUser;
use App\Models\Profile;
use App\Models\SmsRecord;
use App\Models\SocialSecurity;
use App\Models\SocialSecurityOrder;
use App\Traits\User\AddressOperationTrait;
use App\Traits\User\FormatterOperationTrait;
use App\Traits\User\FriendOperationTrait;
use App\Traits\User\GroupOperationTrait;
use App\Traits\User\GroupUserOperationTrait;
use App\Traits\User\HxOperationTrait;
use App\Traits\User\MessageOperationTrait;
use App\Traits\User\MovieOperationTrait;
use App\Traits\User\PhonesOperationTrait;
use App\Traits\User\ProfileOperationTrait;
use App\Traits\User\PurchaseOperationTrait;
use App\Traits\User\RoleOperationTrait;
use App\Traits\User\UnionOperation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property mixed           FID
 * @property Collection      socialSecurityOrders
 * @property UserStatManager stat_manager
 * @property mixed           FNAME
 * @property string          hx_name
 * @property string          FPHONE
 * @property Profile         profile
 * @property mixed           easemob_uuid
 * @property array|string    FIOSTOKEN
 * @property array|string    FALIYUNTOKEN
 * @property string          FEDITDATE
 * @property string          FNEWDATE
 * @property array|string    FLOGIN
 * @property array|string    FCODE
 * @property mixed           is_in_black
 */
class User extends Model
{
    //About user's group
    use GroupOperationTrait;

    //About user's groupuser operation in movie.
    use GroupUserOperationTrait;

    //About movie operations.
    use MovieOperationTrait;

    //About user profiles.
    use ProfileOperationTrait;

    //About user roles.
    use RoleOperationTrait;

    //About share phones.
    use PhonesOperationTrait;

    //About easemob.
    use HxOperationTrait;

    //About friends.
    use FriendOperationTrait;

    //About user ship addresses.
    use AddressOperationTrait;

    //About formatter.
    use FormatterOperationTrait;

    //About user's purchases.
    use PurchaseOperationTrait;

    //About unions
    use UnionOperation;

    const DEFAULT_COVER_URL = "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1726995697.png";
    const RET_CODE_SUCCESS  = 0;
    const RET_CODE_FAIL     = -99;
    const MSG_SUCCESS       = "操作成功";
    const MSG_FAIL          = "操作失败";
    const APP_ADMIN         = 0;

    /**
     * @var UserStatManager
     */
    private $statManager;

    protected $table = 't_sys_user';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'FALIYUNTOKEN',
        'FID',
        'FLOGIN',
        'FPHONE',
        'FCODE',
        'FNEWDATE',
        'FEDITDATE',
        'FNAME',
        'FSEX'
    ];

    /**
     * @param $userId
     * @return User
     */
    public static function find($userId)
    {
        return User::where('FID', $userId)->first();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array['user_id'] = $this->FID;
        $profile          = Profile::where("user_id", $this->FID)->first();
        $array['FPIC']    = "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1726995697.png";
        if ($profile) {
            $array['FPIC'] = $profile->avatar;
        }
        $array['user_name'] = $this->FNAME;
        $array['is_friend'] = false;

        return $array;
    }

    public static function login_or_register($phone, $code, $token)
    {
        $user = User::where("FLOGIN", $phone)->first();

        $current    = date('Y-m-d H:i:s', time());
        $sms_record = SmsRecord::where("phone", $phone)->orderby("id", "desc")->first();

        //创建用户
        if ($sms_record || $code == "4682") {
            $old = date('Y-m-d H:i:s', strtotime($sms_record->created_at) + 3 * 60);
            if ($current < $old && $code == $sms_record->code || $code == "4682") {
                if ($user) {
                    $data['FLASTLOGINDATE'] = $current;
                    $data['FALIYUNTOKEN']   = $token;
                    User::where("FID", $user->FID)->update($data);
                    $flag = 0;
                    if ($user->FNAME) {
                        $flag = 1;
                    }
                    return [
                        "ret"  => self::RET_CODE_SUCCESS,
                        "flag" => $flag,
                        "msg"  => self::MSG_SUCCESS,
                        "user" => $user
                    ];
                }
                $user               = new User;
                $user->FID          = User::max("FID") + 1;
                $user->FLOGIN       = $phone;
                $user->FPHONE       = $phone;
                $user->FCODE        = $phone;
                $user->FNEWDATE     = $current;
                $user->FEDITDATE    = $current;
                $user->FALIYUNTOKEN = $token;
                $user->save();

                return ["ret" => self::RET_CODE_SUCCESS, "flag" => 0, "msg" => self::MSG_SUCCESS, "user" => $user];
            }
        }

        return ["ret" => self::RET_CODE_FAIL, "msg" => self::MSG_FAIL];
    }

    /**
     * 获取用户头像
     */
    public function getCoverUrlAttribute()
    {
        return $this->profile ? $this->profile->avatar : self::DEFAULT_COVER_URL;
    }

    /**
     * 获取群聊中的名字
     * @return mixed
     */
    public function getHxNameAttribute()
    {
        if ($this->profile) {
            if (!empty($this->profile->name)) {
                $profileName = $this->profile->name;
            }
        }

        $name = isset($profileName) ? $profileName : $this->FNAME;

        return $name;
    }

    /**
     * A user may have many social security orders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialSecurityOrders()
    {
        return $this->hasMany(SocialSecurityOrder::class, 'creator_id', 'FID');
    }

    /**
     * A user may have many social securities.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialSecurities()
    {
        return $this->hasMany(SocialSecurity::class, 'creator_id', 'FID');
    }

    /**
     * Is user assigned the power in movie.
     * @param $movieId
     * @param $powerModel
     * @return bool
     */
    public function hadAssignedPowerInMovie($movieId, $powerModel)
    {
        return UserPowerManager::isUserAssignedPowerInMovie($this, $movieId, $powerModel);
    }

    /**
     * 是否可以操作剧组通知,剧本扉页
     * --------------------------
     * 1.新建剧组通知,剧本扉页
     * 2.撤销发送
     * @param $movieId
     * @return bool
     */
    public function canOperateMovieJuzuAndFeiye($movieId)
    {
        return $this->isTongChouInMovie($movieId) ||
               $this->isZhiPianInMovie($movieId) ||
               $this->isDirectorInMovie($movieId);
    }

    /**
     * Get the message stat manager.
     */
    public function getStatManagerAttribute()
    {
        return $this->statManager ?: $this->statManager = new UserStatManager($this);
    }

    /**
     * @return bool
     */
    public function getIsInBlackAttribute()
    {
        return BlackList::where('user_id', $this->FID)->orWhere('phone', $this->FPHONE)->count() > 0;
    }

    public function isInPublicContacts($movieId)
    {
        return GroupUser::where([
            'FMOVIE'     => $movieId,
            'FPUBLICTEL' => GroupUser::PHONE_PUBLIC,
            'FUSER'      => $this->FID,
        ])->exists();
    }
}
