<?php

namespace App\Console\Commands;

use App\Models\SocialSecurity;
use App\Models\SocialSecurityPrice;
use Illuminate\Console\Command;

class DeployVersion3_5_1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:version_3_5_1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Social Security Prices Table';

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
        foreach (SocialSecurity::$hukouTypes as $hukouType => $hukouDesc) {
            $lostJobPrice = 24.66 ;
            $totalPrice   = 1479.79 ;

            //农村户口的失业保险
            if ($hukouType == 1 || $hukouType == 3) {
                $lostJobPrice = 30.82 ;
                $totalPrice   = 1485.95 ;
            }
            SocialSecurityPrice::create([
                'base_number'         => 3082,
                'hukou_type'          => $hukouType,
                'pension_price'       => 837.14 ,
                'lost_job_price'      => $lostJobPrice,
                'work_accident_price' => 23.12 ,
                'born_price'          => 36.99 ,
                'medical_price'       => 557.88 ,
                'total_price'         => $totalPrice
            ]);
        }
    }
}
