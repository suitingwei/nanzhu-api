<?php
namespace App\Traits\GroupUser;

use App\Models\Group;
use App\Models\Movie;
use App\Models\SparePhone;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait RelationShips
{

    /**
     * 一个组员是一个用户
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'FUSER', 'FID');
    }

    /**
     * 一个组员属于一个部门
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'FGROUP', 'FID');
    }

    /**
     * 一个组员属于一个剧组
     *
     * @return BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'FMOVIE', 'FID');
    }

    /**
     * 组员拥有多个共享电话
     *
     * @return HasMany
     */
    public function sharePhones()
    {
        return $this->hasMany(SparePhone::class, 'FGROUPUSERID', 'FID');
    }
}
