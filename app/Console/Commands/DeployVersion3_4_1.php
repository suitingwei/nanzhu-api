<?php

namespace App\Console\Commands;

use App\Models\ContactPower;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Movie;
use App\Models\ProgressPower;
use App\Models\ReceivePower;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeployVersion3_4_1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:version3.4.1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run commands for version 3.4.1';

    /**
     * Create a new command instance.
     *
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
//        $this->addChangjiDepartmentToGroupsTemplates();
//        $this->addChangjiDepartmentToExistedNotEndMovie();
        $this->reorderMovieGroups();
    }

    /**
     * Add changji department to groups templates.
     */
    private function addChangjiDepartmentToGroupsTemplates()
    {
        Group::create([
            'FMOVIE'     => 0,
            'FID'        => Group::max('FID') + 1,
            'FNAME'      => '场记',
            'FNEWDATE'   => Carbon::now(),
            'FGROUPTYPE' => Group::TYPE_CHANGJI,
        ]);
    }

    /**
     * Add changji department to exiting and not shoot end movies.
     */
    private function addChangjiDepartmentToExistedNotEndMovie()
    {
        $movies = Movie::where('shootend', Movie::NOT_SHOOT_END)->get();

        foreach ($movies as $movie) {
            $this->info('----------Operating ' . $movie->FID . '----------------' . PHP_EOL);

            if ($movie->groups()->where('t_biz_group.FNAME', 'like', '场记%')->count() > 0) {
                $this->warn('-----Movie' . $movie->FID . ' already had Changji department.' . PHP_EOL);
                continue;
            }

            $movieAdmin = $movie->admin();
            if (is_null($movieAdmin)) {
                $this->warn('-----Movie' . $movie->FID . ' don\'t has admin' . PHP_EOL);
                continue;
            }
            $this->info('----Movie admin is:' . $movieAdmin . PHP_EOL);

            $group = Group::create([
                'FID'        => Group::max("FID") + 1,
                'FNAME'      => '场记',
                'FMOVIE'     => $movie->FID,
                'FNEWDATE'   => date('Y-m-d H:i:s'),
                'FGROUPTYPE' => Group::TYPE_CHANGJI,
                'FLEADERID'  => $movieAdmin->FID,
                'FPOS'       => 1
            ]);
            $this->info('----ChangJi group created successfully,' . PHP_EOL . $group);

            $newGroupUser = GroupUser::create([
                'FID'            => GroupUser::max("FID") + 1,
                'FUSER'          => $movieAdmin->FID,
                'FGROUP'         => $group->FID,
                'FMOVIE'         => $movie->FID,
                'FREMARK'        => '建剧人',
                'FGROUPUSERROLE' => 20,
                'FOPEN'          => 10,
                'FOPENED'        => 1,
                'FPUBLICTEL'     => 20,
                'FNEWDATE'       => date('Y-m-d H:i:s'),
                'FEDITDATE'      => date('Y-m-d H:i:s'),
            ]);
            $this->info('----Movie admin join changji group successfully,' . PHP_EOL . $newGroupUser);

            $oldGroupUser = $movieAdmin->firstGroupUserInMovie($movie->FID);
            $this->info('----Movie admin\'s first group user in movie' . PHP_EOL . $oldGroupUser);

            $this->copyOldGroupUserPower($oldGroupUser, $newGroupUser, $movie->FID);

            $movieAdmin->joinHxGroup($group);
            $this->info('----Movie admin joining changji hx group');
        }
    }

    /**
     * Copy the group user power.
     *
     * @param $oldGroupUser
     * @param $newGroupUser
     * @param $movieId
     */
    private function copyOldGroupUserPower($oldGroupUser, $newGroupUser, $movieId)
    {
        $powerNeedToCopyWhenJoinNewGroup = [
            ContactPower::class,
            ProgressPower::class,
            ReceivePower::class
        ];

        foreach ($powerNeedToCopyWhenJoinNewGroup as $powerNeedToCopy) {
            $oldPower = $powerNeedToCopy::where([
                'FGROUPUSERID' => $oldGroupUser->FID,
                'FMOVIEID'     => $movieId
            ])->first();
            if ($oldPower) {
                $powerNeedToCopy::create([
                    'FID'          => $powerNeedToCopy::max('FID') + 1,
                    'FGROUPUSERID' => $newGroupUser->FID,
                    'FMOVIEID'     => $movieId
                ]);
            }
        }
    }

    /**
     * Reorder all movies' groups.
     */
    private function reorderMovieGroups()
    {
        Group::where('FNAME', 'like', '制片%')->update(['FPOS' => 4]);
        Group::where('FNAME', 'like', '统筹%')->update(['FPOS' => 3]);
        Group::where('FNAME', 'like', '导演%')->update(['FPOS' => 2]);
        Group::where('FNAME', 'like', '场记%')->update(['FPOS' => 1]);
    }

}
