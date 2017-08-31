<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Models\ReferencePlan::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'file_url' => 'http://www.newsofbahrain.com/admin/post/upload/000PST_31-03-2016_1459426231_bYViJTGH2j.jpg' ,
        'file_name' => $faker->words,
        'file_path' => str_random(10),
        'creator_id'=>28507
    ];
});

$factory->define(App\Models\Company::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'logo' => 'http://www.newsofbahrain.com/admin/post/upload/000PST_31-03-2016_1459426231_bYViJTGH2j.jpg' ,
        'introduction' => $faker->paragraph(rand(10,20)),
        'contact_user_ids'=>'28507,28520'
    ];
});


$factory->define(App\Models\TradeScript::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'author'=>$faker->firstName,
        'content' => $faker->paragraph(rand(10,20)),
        'contact_user_ids'=>'28507,28520'
    ];
});


$factory->define(App\Models\CooperateEditor::class, function (Faker\Generator $faker) {
    $users = \App\User::lists('FID')->all();
    $companies = \App\Models\Company::lists('id')->all();
    return [
        'company_id' => $companies[array_rand($companies)] ,
        'editor_id'=> $users[array_rand($users)]
    ];

});
