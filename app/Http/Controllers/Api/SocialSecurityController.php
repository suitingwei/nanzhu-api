<?php

namespace App\Http\Controllers\Api;

use App\Formatters\SocialSecurityOrderFormatter;
use App\Models\SocialSecurity;
use App\Models\SocialSecurityOrder;
use DB;
use Illuminate\Http\Request;

class SocialSecurityController extends BaseController
{
    /**
     * Create the social security record and the order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $attributes = collect($request->all());
            \Log::info('creating new social security' . json_encode($attributes));
            SocialSecurity::createOrFail($attributes);
            $order = SocialSecurityOrder::createOrFail($attributes);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFail($e->getMessage());
        }
        DB::commit();

        $formatter = SocialSecurityOrderFormatter::getCreatedFormatter();

        return $this->responseSuccess('成功', ['order' => $formatter($order)]);
    }

    /**
     * Get the service info for the app service info page.
     */
    public function serviceInfo()
    {
        return $this->responseSuccess('success', [
            'images'        => SocialSecurity::$serviceInfoBanners,
            'content'       => SocialSecurity::$serviceInfoContent,
            'title'         => SocialSecurity::$serviceInfoTitle,
            'service_phone' => SocialSecurity::$servicePhone
        ]);
    }

    /**
     * Get all options for creating a social security record and order.
     */
    public function options()
    {
        return $this->responseSuccess('success', SocialSecurity::getOptions());
    }
}


