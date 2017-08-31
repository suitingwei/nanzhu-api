<?php

namespace App\Traits;

trait MessageReadCalator
{
    /**
     * 让某一个用户读某一个model的消息
     * 如: 用户读参考大计划,读场记日报表
     *
     * @param $modelId
     * @param $readerId
     */
    public static function userReadMessage($modelId, $readerId)
    {
        $model = static::find($modelId);

        $messages = $model->messages;

        if ($messages->count() == 0) {
            return;
        }

        foreach ($messages as $message) {
            $message->receivers()->where("receiver_id", $readerId)->update(['is_read' => 1]);
        }
    }

    /**
     * A thing may have many messages.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    abstract public function messages();

    /**
     * 大计划是否已经发送
     * @return boolean
     */
    public function isSend()
    {
        return $this->messages()->count() > 0;
    }

    /**
     * 参考大计划阅读百分比
     */
    public function readRate()
    {
        if ($this->messages()->count() == 0) {
            return '0/0';
        }

        return $this->messages()->first()->readRate();
    }

    /**
     * 是否发送的getter
     * @return boolean
     */
    public function getIsSendAttribute()
    {
        return $this->isSend();
    }

    /**
     * 获取阅读百分比
     */
    public function getReadRateAttribute()
    {
        return $this->readRate();
    }

    /**
     * 接受总数
     * @return string
     */
    public function getTotalReadCountAttribute()
    {
        return explode('/', $this->readRate())[1];
    }

    /**
     * 接受分子
     * @return string
     */
    public function getHadReadCountAttribute()
    {
        return explode('/', $this->readRate())[0];
    }

}
