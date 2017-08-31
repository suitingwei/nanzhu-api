<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\PushRecord;
use App\Models\ShootOrder;
use Illuminate\Http\Request;

class ShootOrdersController extends Controller
{
    public function create(Request $request)
    {
        $user_id = $request->get("user_id");

        $wx_openid = $request->get("openid");
        if (!$wx_openid) {
            return redirect()->to("/mobile/auth");
        }
        return view("mobile.shoot_orders.create", ["wx_openid" => $wx_openid]);
    }

    public function success(Request $request)
    {
        return view("mobile.shoot_orders.success");
    }

    /**
     * 专业版视频购买成功之后需要通知
     * ----------------------------------
     * 1.崔景涛
     * 2.蓓蓓(临时)
     * @var array
     */
    private $needToNotifyPerson = [31130, 30812];

    public function store(Request $request)
    {
        $data        = $request->all();
        $shoot_order = ShootOrder::create($data);
        return response()->json(['ret' => 0, "msg" => "保存成功", "shoot_order" => $shoot_order]);
    }

    public function update(Request $request, $id)
    {
        $shoot_order = ShootOrder::find($id);
        if ($shoot_order) {
            $shoot_order->is_payed = $request->get("is_payed");
            $shoot_order->payed_at = date("Y-m-d H:i:s");
            $shoot_order->save();

            $this->notifyCallback($shoot_order);
            return response()->json(['ret' => 0, "msg" => "保存成功", "shoot_order" => $shoot_order]);
        }
    }

    /**
     * 专业版购买成功之后的推送
     *
     * @param ShootOrder $shootOrder
     */
    public function notifyCallback(ShootOrder $shootOrder)
    {
        //创建推送的消息内容
        $message = Message::createProfessionalVideoPurchasedNofify($shootOrder, $this->needToNotifyPerson);

        //消息类型
        $extra = ['uri' => $message->uri, 'type' => $message->type];

        //发送push
        PushRecord::sendManyByUserIds($this->needToNotifyPerson, $message->title, $message->content, $extra);
    }
}
