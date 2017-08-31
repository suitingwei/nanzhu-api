<?php
namespace App\Formatters;

use App\User;

class MessageFormatter
{
    /**
     * 剧组通知,剧本扉页index接口json格式化
     *
     * @param User $currentUser
     * @param      $canSeeReceivers
     *
     * @return mixed
     */
    public static function getIndexListFormatter(User $currentUser, $canSeeReceivers)
    {
        return function ($message) use ($currentUser, $canSeeReceivers) {
            return [
                'date'              => $message->created_at->toDateString(),
                'can_see_receivers' => $canSeeReceivers,
                'id'                => $message->id,
                'status'            => $message->getStatusForUser($currentUser->FID),
                'can_redo'          => $currentUser->canOperateMovieJuzuAndFeiye($message->movie_id),
                'is_undo'           => $message->isUndo(),
                'title'             => $message->title,
                'content'           => $message->content,
                'read_rate'         => $message->readRate(),
                'total_read_count'  => $message->total_read_count,
                'had_read_count'    => $message->had_read_count,
                'pictures'          => $message->pictures(),
                'h5_receivers_url'  => $message->getH5ReceiversUrl(),
                'h5_detail_url'     => $message->getH5DetailUrl($currentUser->FID)
            ];
        };
    }

}

