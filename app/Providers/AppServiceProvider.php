<?php

namespace App\Providers;

use Carbon\Carbon;
use DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        \Validator::extend('greater_than_field', function ($attribute, $value, $parameters, $validator) {
            return $value > $validator->getData()[$parameters[0]];
        });

        \Validator::extend('time_greater_than_field', function ($attribute, $value, $parameters, $validator) {
            $validateDate = Carbon::createFromTimestamp(strtotime($value));
            $minimumDate  = Carbon::createFromTimestamp(strtotime($validator->getData()[$parameters[0]]));
            return $validateDate->gt($minimumDate);
        });
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {

    }

}
