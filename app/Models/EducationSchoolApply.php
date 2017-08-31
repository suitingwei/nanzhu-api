<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mail;

class EducationSchoolApply extends Model
{
    public $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::created(function(EducationSchoolApply $educationSchoolApply){
            Mail::send('new_education_apply', ['msg' => '有新的用户报名容艺教育','apply'=>$educationSchoolApply], function ($message) {
                $message->to('houliyuan@rongyiedu.com', '容艺教育报名')->subject("容艺教育报名");
                $message->cc('ryjy@nanzhuxinyu.com');
                $message->from('postmaster@nanzhuxinyu.com', 'nanzhuxinyu');
            });
        });
    }
    
}
