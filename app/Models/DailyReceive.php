<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 每日通告单
 * Class DailyReceive
 * @package App
 */
class DailyReceive extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'FID';

    protected $table = 't_biz_dailynereceive';
}
