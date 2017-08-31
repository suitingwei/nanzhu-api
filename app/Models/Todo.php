<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed  share_group
 * @property string read_ids
 * @property int    user_id
 * @property int    movie_id
 * @property User   authorUser
 * @property Movie  movie
 * @property int    id
 * @property mixed  content
 * @property int    is_read
 * @property string share_ids
 */
class Todo extends Model
{
    //
    /**
     * @var array
     */
    protected $fillable = [
        "is_read",
        "share_ids",
        "movie_id",
        "share_group",
        "title",
        "content",
        "user_id",
        "date",
        'read_ids'
    ];

    /**
     * @return mixed
     */
    public function toArray()
    {
        $array['id']          = $this->id;
        $array['is_read']     = $this->is_read;
        $array['share_ids']   = $this->share_ids;
        $array['movie_id']    = $this->movie_id;
        $array['share_group'] = $this->share_group;
        $array['title']       = $this->title;
        $array['content']     = $this->content;
        $array['user_id']     = $this->user_id;
        $array['date']        = $this->date;

        //这里强制转换为Carbon对象的原因是:
        //备忘录在某个人读取的时候要把这个人添加到read_ids,但是这个时候不能更新updated_at,那个代表上一次编辑时间
        //所以把timestamps ==> false,但是这样的话,updated_at就变成了string,所以需要强制转换
        $array['last_updated'] = Carbon::createFromTimestamp(strtotime($this->updated_at));
        $array['editor']       = "";

        //用户删除的时候不会删除备忘录(因为可以同步别人查看),所以有可能用户不存在
        $user                = User::where("FID", $this->user_id)->first();
        $array['editor']     = $user ? $user->FNAME : '';
        $movie               = Movie::where("FID", $this->movie_id)->first();
        $array['movie_name'] = $movie ? $movie->FNAME : '';

        $array['group']       = $this->groupNames;
        return $array;
    }

    /**
     * 添加新的已读用户id到read_ids
     *
     * @param $newUserId
     */
    public function addNewReadUserId($newUserId)
    {
        //如果不分享,不用管
        if (empty($newUserId) || $this->notShared()) {
            return;
        }

        $readUserIdArray = explode(',', $this->read_ids);

        //已经读了,不用管
        if (in_array($newUserId, $readUserIdArray)) {
            return;
        }

        array_push($readUserIdArray, $newUserId);

        $this->read_ids   = implode(',', $readUserIdArray);
        $this->timestamps = false;
        $this->save();
    }

    /**
     * 判断这个备忘录是否共享
     * @return bool
     */
    public function notShared()
    {
        return !$this->shared();
    }

    /**
     * 判断这个备忘录是否共享
     * @return bool
     */
    public function shared()
    {
        return $this->share_group == 1;
    }

    /**
     * 用户是否可以看到这个备忘录
     */
    public function scopeCanSee($query, $userId)
    {
        $query->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('share_ids', 'like', "%{$userId}%");
        });
    }

    /**
     * 用户是否阅读备忘录
     *
     * @param $readerId
     *
     * @return bool
     */
    public function isReadByUser($readerId)
    {
        //如果备忘录没有共享,那么没有share_ids,所以只需要判断创建时间不等于更新时间
        //如果备忘录共享了,那么判断自己是否在read_ids里
        return ($this->notShared() && $this->is_read) || in_array($readerId, explode(',', $this->read_ids));
    }

    /**
     * 获取备忘录创建者的部门
     */
    public function getGroupNamesAttribute()
    {
        $groupUsers = GroupUser::where(['FMOVIE' => $this->movie_id, 'FUSER' => $this->user_id])->get();
        //需要这个判断,因为发备忘录的人可能已经退出部门
        if ($groupUsers->count() == 0) {
            return '';
        }

        $groupIds = array_unique($groupUsers->lists('FGROUP')->all());

        //需要进行存在判断,因为可能部门已经被删除
        $groups = Group::whereIn('FID', $groupIds)->get();

        if ($groups->count() == 0) {
            return '';
        }

        return implode('/', $groups->lists('FNAME')->all());
    }

    /**
     *
     * @param $userId
     *
     * @return bool
     */
    public function addUserToReceivers($userId)
    {
        $scopeIdArray = explode(',', $this->share_ids);

        if (in_array($userId, $scopeIdArray)) {
            return true;
        }

        array_push($scopeIdArray, $userId);

        $this->update(['share_ids' => implode(',', $scopeIdArray)]);

        return true;
    }

    /**
     * 共享的备忘录
     *
     * @param $query
     *
     * @return
     */
    public function scopeShare($query)
    {
        return $query->where('share_group', 1);
    }

    /**
     * 删除已阅读人,让所有人都未读
     */
    public function removeAllReadIds()
    {
        $this->update(['read_ids' => '']);
    }

    /**
     * A todo belongs to a movie.
     * @return BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'FID');
    }

    /**
     * A todo can be edited by many user,many times.
     * @return BelongsTo
     */
    public function authorUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'FID');
    }

    /**
     *  Record the update operation.
     */
    public function record()
    {
        TodoRecord::create([
            'user_id'    => $this->user_id,
            'user_name'  => $this->authorUser->FNAME,
            'movie_id'   => $this->movie_id ?: '',
            'movie_name' => $this->movie ? $this->movie->FNAME : '',
            'group_name' => $this->authorUser->groupNamesInMovie($this->movie_id),
            'todo_id'    => $this->id,
        ]);
    }

    /**
     * A todo may have many records.
     */
    public function records()
    {
        return $this->hasMany(TodoRecord::class, 'todo_id', 'id');
    }

    /**
     * @param $id
     *
     * @return Todo
     */
    public static function find($id)
    {
        return static::where('id', $id)->first();
    }

    /**
     * Remove the user from the share ids.
     * So she cannot see the todo.
     *
     * @param $userId
     */
    public function removeUserFromShared($userId)
    {
        $newUserId           = $this->user_id;
        $removedShareIdArray = array_diff(explode(',', $this->share_ids), (array)$userId);
        $removedReadIdArray  = array_diff(explode(',', $this->read_ids), (array)$userId);

        //If the current author of the todolist is the user, replace it with the next share ids.
        if ($this->user_id == $userId) {
            $newUserId = (count($removedShareIdArray) > 0) ? $removedShareIdArray[0] : '';
        }

        $this->update([
            'user_id'   => $newUserId,
            'share_ids' => implode(',', $removedShareIdArray),
            'read_ids'  => implode(',', $removedReadIdArray)
        ]);
    }
}
