<?php

namespace App\Models;

use App\Traits\Power\PowerOperation;
use Illuminate\Database\Eloquent\Model;

class ReceivePower extends Model
{
    use PowerOperation;

    protected $table = 't_biz_nereceivepower';
    protected $primaryKey = 'FID';
    protected $fillable = ['FGROUPUSERID', 'FMOVIEID', 'FID'];

    public $timestamps = false;
    public $incrementing = false;

    /**
     * Get the database group-user key,because the old power table contains the
     * different key from the new power table.
     * @return string
     */
    public static function getGroupUserKey()
    {
        return 'FGROUPUSERID';
    }

    /**
     * Get the database movie key,because the old power table contains the
     * different key from the new power table.
     * @return string
     */
    public static function getMovieKey()
    {
        return 'FMOVIEID';
    }

    /**
     * Assign the group user power.
     *
     * @param GroupUser $groupUser
     *
     * @return mixed
     */
    public static function assignGroupUser(GroupUser $groupUser)
    {
        static::create([
            static::getMovieKey()     => $groupUser->FMOVIE,
            static::getGroupUserKey() => $groupUser->FID,
            'FID'                     => static::max('FID') + 1
        ]);
    }
}
