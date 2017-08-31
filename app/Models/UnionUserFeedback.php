<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnionUserFeedback extends Model
{
    //
    protected $fillable = ["user_id", "content", "title","union_id"];
}
