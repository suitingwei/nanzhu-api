<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\ShootOrder;
use Illuminate\Http\Request;

class PaysController extends Controller
{

    public function callback(Request $request)
    {
        $event = json_decode(file_get_contents("php://input"));
        //\Log::info(json_encode($event));

        if (!isset($event->type)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            exit("fail");
        }
        if ($event->type == "charge.succeeded") {
            \Log::info($event->data->object->order_no);
            $order_no = $event->data->object->order_no;
            $id       = substr($order_no, 10, strlen($order_no));
            \Log::info($id);
            $purchase = Purchase::find($id);
            if ($purchase) {
                $purchase->update(['paid' => true]);
            }
            $shoot_order = ShootOrder::find($id);
            if ($shoot_order) {
                $shoot_order->is_payed = 1;
                $shoot_order->payed_at = date("Y-m-d H:i:s");
                $shoot_order->save();
                return response()->json(['ret' => 0, "msg" => "保存成功"]);
            }
        }
    }

    public function charge(Request $request)
    {
        \Log::info($request->all());
        $extra   = [];
        $channel = $request->get("channel");
        if ($channel == "wx_pub") {
            \Log::info($request->get("wx_openid"));
            $extra = ['open_id' => $request->get("wx_openid")];
        }
        $order_no = strtotime(date("Y-m-d H:i:s")) . $request->get("order_no");
        \Pingpp\Pingpp::setApiKey(env('PINGPP_SECRET_KEY'));
        $ch = \Pingpp\Charge::create(
            array(
                'order_no'  => $order_no,
                'app'       => array('id' => 'app_Sy1eLCKKiL84aLW5'),
                'channel'   => $channel,
                'amount'    => 150000,
                //'amount'    => 1,
                'client_ip' => '127.0.0.1',
                'currency'  => 'cny',
                'subject'   => '专业视频拍摄服务',
                'body'      => '专业视频拍摄服务',
                'extra'     => $extra
            )
        );

        return response()->json(["charge" => $ch]);
    }
}
