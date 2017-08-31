<?php

namespace App\Http\Controllers\Api;

use App\Formatters\SocialSecurityOrderFormatter;
use App\Models\SocialSecurity;
use App\Models\SocialSecurityOrder;
use App\Repositories\SocialSecurityOrdersRepository;
use Illuminate\Http\Request;

/**
 * Class SocialSecurityOrdersController
 * @package App\Http\Controllers\Api
 */
class SocialSecurityOrdersController extends BaseController
{
    private $request;

    private $repository;

    /**
     * SocialSecurityOrdersController constructor.
     *
     * @param Request                        $request
     * @param SocialSecurityOrdersRepository $repository
     */
    public function __construct(Request $request, SocialSecurityOrdersRepository $repository)
    {
        $this->request    = $request;
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = $this->repository->fetchPaginated();

        $totalPage = ceil($orders->total() / $orders->perPage());

        if ($this->repository->fromOp()) {
            $orders = $orders->map(SocialSecurityOrderFormatter::getListFormatterForOp());
        } else {
            $orders = $orders->map(SocialSecurityOrderFormatter::getListFormatter());
        }

        return $this->responseSuccess('success', [
            'orders'                       => $orders,
            'social_security_official_url' => SocialSecurity::$socialSecurityOfficalUrl,
            'total'                        => $totalPage
        ]);

    }

    /**
     * Get the ping++ charge object.
     *
     * @param         $orderId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay($orderId)
    {
        $order = SocialSecurityOrder::find($orderId);

        if (!$order || ($order->creator_id != $this->request->input('user_id'))) {
            return $this->responseFail();
        }

        $charge = $order->createPingPPCharge($this->request);
        $order->update(['channel' => $this->request->input('channel')]);

        return response()->json(['ret' => 0, 'msg' => '成功', 'charge' => $charge, 'order_id' => $order->id]);
    }

    /**
     * @param $orderId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($orderId)
    {
        $order = SocialSecurityOrder::find($orderId);

        if (!$order || ($order->creator_id != $this->request->input('user_id'))) {
            return $this->responseFail();
        }

        $formatter = SocialSecurityOrderFormatter::getDetailFormatter();

        return $this->responseSuccess('success', ['order' => $formatter($order)]);
    }

    /**
     * The callback for app pay success.
     *
     * @param $orderId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($orderId)
    {
        static $supportMethods = ['pay', 'cancel'];
        $order = SocialSecurityOrder::find($orderId);

        if (!$order || ($order->creator_id != $this->request->input('user_id'))) {
            return $this->responseFail();
        }

        if (!in_array($action = $this->request->input('action'), $supportMethods)) {
            return $this->responseFail('Action' . $action . 'is not supported!');
        }

        $order->{$this->request->input('action')}();

        return $this->responseSuccess();
    }

    /**
     * @param $orderId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function continuePay($orderId)
    {
        try {
            $order = $this->repository->continuePay($orderId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        $formatter = SocialSecurityOrderFormatter::getCreatedFormatter();
        return $this->responseSuccess('success', ['order' => $formatter($order)]);
    }
}
