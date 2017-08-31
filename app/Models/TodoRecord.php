<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DailyReportRecord
 * @package App
 * @property int    user_id
 * @property int    movie_id
 * @property int    todo_id
 * @property string user_name
 * @property string movie_name
 * @property string group_name
 */
class TodoRecord extends Model
{
    public $guarded = [];
}
