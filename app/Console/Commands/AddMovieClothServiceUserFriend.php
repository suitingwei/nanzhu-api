<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class AddMovieClothServiceUserFriend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:add_movie_clothes_service_user_friend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Let all users add movie clothes service user to be friend';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $serviceUser = User::find(env('MOVIE_CLOTHES_SERVER_USER_ID'));
        foreach (User::where('FID', '!=', env('MOVIE_CLOTHES_SERVER_USER_ID'))->get() as $user) {
            if ($serviceUser->isFriendOfUser($user)) {
                $this->warn($user->FID . "is friend of the service man");
                continue;
            }
            $this->info($user->FID . "now is a friend of the service man");
            $serviceUser->addFriend($user);
        }
    }
}
