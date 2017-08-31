<?php

namespace App\Http\Controllers\Api;

use App\Models\UserAddress;
use Illuminate\Http\Request;

/**
 * Class UserAddressesController
 * @package App\Http\Controllers\Api
 */
class UserAddressesController extends BaseController
{
    /**
     * Get a user's all addresses.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $userAddresses = UserAddress::where('user_id', $request->input('user_id'))->orderBy('created_at',
            'desc')->paginate(10);

        return $this->responseSuccess('', ['address' => $userAddresses->toArray()['data']]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        UserAddress::create([
            'user_id'        => $request->input('user_id'),
            'receiver_name'  => $request->input('receiver_name'),
            'receiver_phone' => $request->input('receiver_phone'),
            'province'       => $request->input('province'),
            'city'           => $request->input('city'),
            'area'           => $request->input('area'),
            'detail'         => $request->input('detail'),
        ]);

        return $this->responseSuccess();
    }


    /**
     * @param         $userAddressId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($userAddressId, Request $request)
    {
        $userAddress   = UserAddress::find($userAddressId);
        $currentUserId = $this->current_user($request);

        if (!$userAddress) {
            return $this->responseFail('地址不存在');
        }

        if ($currentUserId != $userAddress->user_id) {
            return $this->responseFail('无权限编辑');
        }

        $updateData = collect($request->only([
            'receiver_name',
            'receiver_phone',
            'detail',
            'province',
            'city',
            'area'
        ]))->filter(function ($updateAttribute) {
            return !empty($updateAttribute);
        });

        $userAddress->update($updateData->all());

        return $this->responseSuccess();
    }

    /**
     * Delete a user address.
     *
     * @param         $userAddressId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($userAddressId, Request $request)
    {
        $userAddress   = UserAddress::find($userAddressId);
        $currentuserId = $this->current_user($request);

        if (!$userAddress) {
            return $this->responseFail('地址不存在');
        }
        if ($currentuserId != $userAddress->user_id) {
            return $this->responseFail('无权限编辑');
        }

        $userAddress->delete();

        return $this->responseSuccess();
    }

    /**
     * @param         $userAddressId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($userAddressId, Request $request)
    {
        $userAddress   = UserAddress::find($userAddressId);
        $currentUserId = $this->current_user($request);

        if (!$userAddress) {
            return $this->responseFail('地址不存在');
        }
        if ($currentUserId != $userAddress->user_id) {
            return $this->responseFail('无权限编辑');
        }

        return $this->responseSuccess('成功', ['address' => $userAddress]);
    }
}
