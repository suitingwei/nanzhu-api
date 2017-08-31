<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientMovieRequirement extends Model
{
    const TYPE_CLIENT = 'CLIENT';
    const TYPE_MOVIE  = 'MOVIE';

    public static $storeRules = [
        'invest_types'  => 'required',
        'movie_types'   => 'required',
        'reward_types'  => 'required',
        'start_date'    => 'required|date',
        'end_date'      => 'required|date|time_greater_than_field:start_date',
        'budget_bottom' => 'required|numeric',
        'budget_top'    => 'required|numeric|greater_than_field:budget_bottom',
    ];

    public $guarded = [];
}
