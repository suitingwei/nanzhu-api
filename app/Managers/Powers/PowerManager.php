<?php

namespace App\Managers\Powers;

use App\Models\ContactPower;
use App\Models\DailyReportPower;
use App\Models\GroupUser;
use App\Models\GroupUserFeedBackPower;
use App\Models\PreviousProspectPower;
use App\Models\ProgressPower;
use App\Models\ReceivePower;
use App\Models\ReferencePlanPower;

/**
 * Class PowerManager
 * @package App\Managers
 */
class PowerManager
{
    /**
     * All power model use the power operation trait.
     * @see \App\Traits\Power\PowerOperation
     * @var array
     */
    public static $allPowerModels = [
        ReceivePower::class,
        ContactPower::class,
        ProgressPower::class,
        DailyReportPower::class,
        ReferencePlanPower::class,
        PreviousProspectPower::class,
        GroupUserFeedBackPower::class,
    ];

    public static $powerModelPrefix = 'App\\Models\\';

    /**
     * @param      $powerModel
     *
     * @param bool $throwException
     *
     * @return bool
     * @throws \Exception
     */
    public static function isModelLegal($powerModel, $throwException = true)
    {
        if (is_null($powerModel)) {
            if ($throwException) {
                throw new \Exception('Can not assign user null power.');
            } else {
                return false;
            }
        }

        if (!in_array($powerModel, static::$allPowerModels)) {
            if ($throwException) {
                throw new \Exception($powerModel . ' is not in power manager\'s manage');
            } else {
                return false;
            }
        }

        if (!$throwException) {
            return true;
        }
    }
    /**
     * Retrieve all powers from group-user.
     *
     * @param GroupUser $groupUser
     */
    public static function retrieveAllPowersFromGroupUser(GroupUser $groupUser)
    {
        foreach (self::$allPowerModels as $powerModel) {
            $powerModel::retrieveGroupUser($groupUser);
        }
    }
}
