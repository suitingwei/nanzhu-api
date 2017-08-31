<?php

namespace App\Models;

use App\Interfaces\ReadStatus;
use App\Traits\MessageReadCalator;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property mixed id
 * @property mixed movie_id
 * @property mixed user_id
 * @property mixed movie
 * @property mixed content
 * @property mixed created_at
 * @property mixed user
 * @property mixed movieId
 * @property mixed author
 */
class GroupUserFeedBack extends Model implements ReadStatus
{
    //某一个用户读取场记日报表
    use MessageReadCalator;

    public $guarded = [];

    public function pushMessages()
    {
        $message = $this->createPushMessage();

        $message->push(true, ['groupuser_feedback_id' => $this->id, 'movie_id' => $this->movie_id]);
    }

    /**
     * 创建push的消息
     * @return Message
     */
    private function createPushMessage()
    {
        $allUsersWithPower = $this->movie->allUsersWithPower(GroupUserFeedBackPower::class)->pluck('FID')->all();

        return Message::create([
            'type'                  => Message::TYPE_GROUPUSER_FEEDBACK,
            'scope'                 => Message::SCOPE_SOME_BODY,
            "scope_ids"             => implode(',', $allUsersWithPower),
            'title'                 => $this->movie->FNAME . ':您有新的组员反馈请查看。',
            'content'               => '',
            'groupuser_feedback_id' => $this->id,
            'from'                  => $this->user_id,
            'movie_id'              => $this->movie_id,
            'notice_type'           => '',
            'filename'              => '',
            'uri'                   => ''
        ]);
    }

    /**
     * A groupuser feedback belongs to a movie.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'FID');
    }

    /**
     * @param $id
     *
     * @return GroupUserFeedBack
     */
    public static function find($id)
    {
        return static::where('id', $id)->first();
    }

    /**
     * A feedback belongs to a user.
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'FID');
    }

    public function getStatusForUser($userId)
    {
        return MessageReceiver::isModelMessageRead('groupuser_feedback_id', $this->id, $userId)
            ? static::STATUS_READED
            : static::STATUS_WAIT_READ;
    }

    /**
     * A thing may have many messages.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'groupuser_feedback_id', 'id');
    }

    /**
     * Get the title of the previous prosprect.
     */
    public function getTitleAttribute()
    {
        preg_match('/^\s*\[(\w+),(\d+\.?\d*,){2}(.*?)\]/', $this->content, $matches);

        if (isset($matches[1])) {
            if ($matches[1] == 'img') {
                return '[图片]';
            }
            if ($matches[1] == 'video') {
                return '[视频]';
            }
        }

        $title = preg_replace('/(\[.*?\])*[\n\r]*/', '', $this->content);

        return Str::limit($title, 20);
    }


    /**
     * Get the h5 receivers page url.
     */
    public function getH5ReceiversUrlAttribute()
    {
        return env('APP_URL') . '/mobile/groupuser-feedbacks/' . $this->id . '/receivers?movie_id=' . $this->movie_id;
    }

    /**
     * Get the cover attribute.
     */
    public function getCoverAttribute()
    {
        preg_match('/\[img,(\d+\.?\d*,){2}(.*?)\]/', $this->content, $matches);

        return isset($matches[2]) ? $matches[2] : '';
    }

    /**
     * A previous prospect belongs to a user.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'FID');
    }

}
