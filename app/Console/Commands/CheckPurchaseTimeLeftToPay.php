<?php

namespace App\Console\Commands;

use App\Models\Purchase;
use Illuminate\Console\Command;

class CheckPurchaseTimeLeftToPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase:cancel_time_expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check purchases\' left to pay';

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
        $this->info('开始查询过期订单');
        //查询没有支付的,(也还没有取消/删除的订单)过期的订单
        foreach (Purchase::where([
            'paid'     => false,
            'canceled' => false,
            'deleted'  => false,
        ])->get() as $purchase) {
            if ($purchase->time_left_to_pay == 0) {
                $this->warn('订单' . $purchase->id . '已经过期');
                $purchase->cancel();
            } else {
                $this->info('订单' . $purchase->id . '剩余支付时间为:' . $purchase->time_left_to_pay . '毫秒,没有过期' . PHP_EOL);
            }
        }
    }
}
