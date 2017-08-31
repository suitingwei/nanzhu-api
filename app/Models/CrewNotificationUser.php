<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * 剧组通知
 * Class CrewNotificationUser
 * @package App
 */
class CrewNotificationUser extends Model
{

    public $timestamps = false;

    protected $primaryKey = 'FID';

    protected $table = 't_biz_tzuser';

}
