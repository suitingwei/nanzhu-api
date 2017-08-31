<?php

namespace App\Formatters;

use App\Models\PreviousProspect;
use App\Models\ReceivePower;
use App\User;

class PreviousProspectFormatter
{
    public static function getListFormatter(User $user)
    {
        return function (PreviousProspect $previousProspect) use ($user) {
            $author = $previousProspect->author;
            return [
                'id'                => $previousProspect->id,
                'title'             => $previousProspect->title,
                'created_at'        => $previousProspect->created_at->toDateString(),
                'can_see_receivers' => $user->hadAssignedPowerInMovie($previousProspect->movie_id, ReceivePower::class),
                'h5_receivers_url'  => $previousProspect->h5_receivers_url,
                'status'            => $previousProspect->getStatusForUser($user->FID),
                'read_rate'         => $previousProspect->read_rate,
                'had_read_count'    => $previousProspect->had_read_count,
                'total_read_count'  => $previousProspect->total_read_count,
                'cover'             => $previousProspect->cover,
                'content'           => str_replace('https://', 'http://', $previousProspect->content),
                'author_name'       => $author->FNAME,
                'group_name'        => $author->groupNamesInMovie($previousProspect->movie_id),
                'last_updated_at'   => $previousProspect->updated_at->toDateTimeString(),
            ];
        };
    }

    /**
     * Get the show
     */
    public static function getShowFormatter()
    {
        return function (PreviousProspect $previousProspect) {
            $originalArray = $previousProspect->toArray();

            $originalArray['content'] = str_replace('https://', 'http://', $previousProspect->content);
            $author                   = $previousProspect->author;
            return array_merge($originalArray, [
                'title'           => $previousProspect->title,
                'author_name'     => $author->FNAME,
                'group_name'      => $author->groupNamesInMovie($previousProspect->movie_id),
                'last_updated_at' => $previousProspect->updated_at->toDateTimeString(),
            ]);
        };
    }
}