<?php

namespace App\Models;

use App\Traits\Power\PowerOperation;
use Illuminate\Database\Eloquent\Model;

class UnionPower extends Model
{
    use PowerOperation;

    public $fillable = ['group_user_id', 'union_id'];

    /**
     * Get the database group-user key,because the old power table contains the
     * different key from the new power table.
     * @return string
     */
    public static function getGroupUserKey()
    {
        return 'group_user_id';
    }

    /**
     * Get the database movie key,because the old power table contains the
     * different key from the new power table.
     * @return string
     */
    public static function getMovieKey()
    {
        return 'union_id';
    }
}
