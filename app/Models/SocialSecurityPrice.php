<?php

namespace App\Models;

use App\Traits\SocialSecurityOptions;
use Illuminate\Database\Eloquent\Model;

class SocialSecurityPrice extends Model
{
    use SocialSecurityOptions;

    public $guarded = [];
}
