<?php

namespace App\Models;

use App\Interfaces\ReadStatus;
use App\Traits\MessageReadCalator;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property mixed  id
 * @property mixed  movie_id
 * @property Movie  movie
 * @property int    user_id
 * @property User   author
 * @property string content
 * @property mixed  total_read_count
 * @property mixed  had_read_count
 */
class PreviousProspect extends Model implements ReadStatus
{
    use MessageReadCalator;

    public $fillable = ['movie_id', 'user_id', 'content'];

    /**
     * A thing may have many messages.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('messages.created_at', 'desc');
    }

    /**
     * 一个场记日报表属于一个剧组
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'FID');
    }

    /**
     * Get the h5 receivers page url.
     */
    public function getH5ReceiversUrlAttribute()
    {
        return env('APP_URL') . '/mobile/previous-prospects/' . $this->id . '/receivers?movie_id=' . $this->movie_id;
    }

    /**
     * @param $userId
     *
     * @return string
     */
    public function getStatusForUser($userId)
    {
        if ($this->isSend() && !Message::isMessageUndo($this->id, 'previous_prospect_id')) {
            if (MessageReceiver::isModelMessageRead('previous_prospect_id', $this->id, $userId)) {
                return static::STATUS_READED;
            }
            return static::STATUS_WAIT_READ;
        }
        return static::STATUS_WAIT_SEND;
    }

    /**
     * Push the messages.
     */
    public function pushMessages()
    {
        $message = $this->createPushMessage();

        $message->push(true, ['previous_prospect_id' => $this->id]);
    }

    /**
     * 创建push的消息
     * @return Message
     */
    private function createPushMessage()
    {
        $allUsersWithPower = $this->movie->allUsersWithPower(PreviousProspectPower::class)->pluck('FID')->all();

        return Message::create([
            'type'                 => Message::TYPE_PREVIOUS_PROSPECT,
            'scope'                => Message::SCOPE_SOME_BODY,
            "scope_ids"            => implode(',', $allUsersWithPower),
            'title'                => $this->movie->FNAME . ':您有新的堪景资料接收。',
            'content'              => '',
            'previous_prospect_id' => $this->id,
            'from'                 => $this->user_id,
            'movie_id'             => $this->movie_id,
            'notice_type'          => '',
            'filename'             => '',
            'uri'                  => ''
        ]);
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

        $title = preg_replace('/(\[.*?\])*[\r\n]*/', '', $this->content);

        return Str::limit($title, 20);
    }

    /**
     * A previous prospect belongs to a user.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'FID');
    }

    /**
     * Record the update operation on the daily report.
     */
    public function record()
    {
        //Not all daily report update the attribtues,some only update the images,
        //these daily report will not trigger the update method,so we should mannally
        //update the timestamp.
        $this->setUpdatedAt(date('Y-m-d H:i:s', time()));
        $this->save();

        $recordData = [
            'user_id'              => $this->author,
            'user_name'            => $this->author->FNAME,
            'movie_id'             => $this->movie_id,
            'movie_name'           => $this->movie->FNAME,
            'previous_prospect_id' => $this->id,
            'group_name'           => $this->author->groupNamesInMovie($this->movie_id),
            'created_at'           => date('Y-m-d H:i:s', time()),
            'updated_at'           => date('Y-m-d H:i:s', time()),
        ];
        PreviousProspectRecord::create($recordData);
    }

    /**
     * A daily report may have many records.
     */
    public function records()
    {
        return $this->hasMany(PreviousProspectRecord::class);
    }

    /**
     * @param $id
     *
     * @return PreviousProspect
     */
    public static function find($id)
    {
        return static::where('id', $id)->first();
    }

    /**
     * Refersh all receivers and push again.
     */
    public function refreshAllReceiversReadStatus()
    {
        $this->messages()->first()->receivers()
             ->update([
                 'is_read'    => MessageReceiver::RECEIVER_UNREAD,
                 'updated_at' => DB::raw("`created_at`")
             ]);

        $this->messages()->first()->push(false, ['daily_report_id' => $this->id]);
    }

    /**
     * Get the cover attribute.
     */
    public function getCoverAttribute()
    {
        preg_match('/\[img,(\d+\.?\d*,){2}(.*?)\]/', $this->content, $matches);

        return isset($matches[2]) ? $matches[2] : '';
    }
}


