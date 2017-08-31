<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 预备通告单
 * Class PreReceive
 * @package App\Models
 */
class PreReceive extends Model
{

    public $timestamps = false;

    protected $primaryKey = 'FID';

    protected $table = 't_biz_prepnereceive';


}
