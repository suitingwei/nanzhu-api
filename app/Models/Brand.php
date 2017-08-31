<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property mixed title
 */
class Brand extends Model
{
    const MOVIE_CLOTHES        = 1;
    const NANZHU_MOVIE_CLOTHES = '南竹通告衫';

    public $guarded = [];

    /**
     * @return bool|mixed
     */
    public function isMovieClothesBrand()
    {
        \Log::info('function isMovieClothesBrand');
        return Str::contains($this->title, self::NANZHU_MOVIE_CLOTHES);
    }
}
