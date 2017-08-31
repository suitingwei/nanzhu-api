<?php
namespace App\Formatters;

use App\Models\GroupUserFeedBack;
use App\Models\ReceivePower;
use App\User;

class GroupUserFeedbackFormatter
{
    public static function getListFormatter(User $user)
    {
        return function (GroupUserFeedBack $feedback) use ($user) {
            return [
                'id'                => $feedback->id,
                'title'             => $feedback->title,
                'created_at'        => $feedback->created_at->toDateString(),
                'can_see_receivers' => $user->hadAssignedPowerInMovie($feedback->movie_id, ReceivePower::class),
                'h5_receivers_url'  => $feedback->h5_receivers_url,
                'status'            => $feedback->getStatusForUser($user->FID),
                'read_rate'         => $feedback->read_rate,
                'had_read_count'    => $feedback->had_read_count,
                'total_read_count'  => $feedback->total_read_count,
                'cover'             => $feedback->cover,
                'content'           => $feedback->content,
                'author_name'       => $feedback->author->FNAME,
                'group_name'        => $feedback->author->groupNamesInMovie($feedback->movie_id),
            ];
        };
    }

    /**
     * Get the show
     */
    public static function getShowFormatter()
    {
        return function (GroupUserFeedBack $feedBack) {
            $originalArray = $feedBack->toArray();

            $originalArray['content'] = str_replace('https://', 'http://', $feedBack->content);
            $author                   = $feedBack->author;
            return array_merge($originalArray, [
                'title'           => $feedBack->title,
                'author_name'     => $author->FNAME,
                'group_name'      => $author->groupNamesInMovie($feedBack->movie_id),
                'last_updated_at' => $feedBack->updated_at->toDateTimeString(),
            ]);
        };
    }
}
