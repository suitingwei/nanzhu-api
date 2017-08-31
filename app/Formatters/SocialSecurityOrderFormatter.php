<?php
/**
 * Created by PhpStorm.
 * User: sui
 * Date: 2017/1/17
 * Time: 下午3:49
 */

namespace App\Formatters;


/**
 * Class SocialSecurityOrderFormatter
 * @package App\Formatters
 */
class SocialSecurityOrderFormatter extends JsonFormatter
{
    /**
     * @return \Closure
     */
    public static function getListFormatter()
    {
        return function ($order) {
            return [
                'id'                 => $order->id,
                'serial_number'      => $order->serial_number,
                'start_date'         => $order->ui_repository->show_start_date,
                'cost_months'        => $order->ui_repository->show_cost_months,
                'total_price'        => $order->ui_repository->show_total_price,
                'status'             => $order->ui_repository->show_status,
                'can_pay'            => $order->can_pay,
                'can_continue_pay'   => $order->can_continue_pay,
                'social_security_id' => $order->social_security_id,
                'user_name'          => $order->user_name,
                'hukou_type'         => $order->hukou_type,
                'show_hukou_type'    => $order->ui_repository->show_hukou_type,
            ];
        };
    }

    /**
     * @return \Closure
     */
    public static function getListFormatterForOp()
    {
        return function ($order) {
            return [
                'hukou_type'            => $order->ui_repository->show_hukou_type,
                'show_is_first'         => $order->ui_repository->show_is_first,
                'social_security_price' => $order->ui_repository->show_social_security_price,
                'service_price'         => $order->ui_repository->show_service_price,
                'total_price'           => $order->ui_repository->show_total_price,
                'show_start_date'       => $order->ui_repository->show_start_date,
                'show_continue_date'    => $order->ui_repository->show_continue_date,
                'show_cost_months'      => $order->ui_repository->show_cost_months,
                'show_status'           => $order->ui_repository->show_status,
                'user_name'             => $order->user_name,
                'user_phone'            => $order->user_phone,
                'hukou_address'         => $order->hukou_address,
                'bank'                  => $order->bank,
                'bank_card_number'      => $order->bank_card_number,
                'minority'              => $order->minority,
                'id_card_number'        => $order->socialSecurity->id_card_number,
                'id_card_up_image'      => $order->socialSecurity->id_card_up_image,
                'id_card_down_image'    => $order->socialSecurity->id_card_down_image,
                'id_card_photo'         => $order->socialSecurity->id_card_photo,
                'serial_number'         => $order->serial_number,
                'id'                    => $order->id,
                'social_security_id'    => $order->social_security_id,
                'channel'               => $order->channel,
                'created_at'            => $order->created_at->toDateTimeString(),
            ];
        };
    }

    /**
     * @return \Closure
     */
    public static function getDetailFormatter()
    {
        return function ($order) {
            return [
                'id'                         => $order->id,
                'serial_number'              => $order->serial_number,
                'user_name'                  => $order->user_name,
                'show_create_date'           => $order->ui_repository->show_create_date,
                'show_hukou_type'            => $order->ui_repository->show_hukou_type,
                'show_start_date'            => $order->ui_repository->show_start_date,
                'show_continue_date'         => $order->ui_repository->show_continue_date,
                'show_channel'               => $order->ui_repository->show_channel,
                'show_social_security_price' => $order->ui_repository->show_social_security_price,
                'show_service_price'         => $order->ui_repository->show_service_price,
                'show_total_price'           => $order->ui_repository->show_total_price,
                'show_status'                => $order->ui_repository->show_status,
                'can_pay'                    => $order->can_pay,
                'can_continue_pay'           => $order->can_continue_pay,
                'social_security_id'         => $order->social_security_id,
                'status'                     => $order->ui_repository->show_status,
                'start_date'                 => $order->start_date,
                'hukou_type'                 => $order->hukou_type,
                'cost_months'                => $order->cost_months,
                'paid'                       => $order->paid,
                'canceled'                   => $order->canceled,
                'creatd_at'                  => $order->created_at,
                'user_phone'                 => $order->user_phone,
                'end_date'                   => $order->end_date,
                'social_security_price'      => $order->social_security_price,
                'service_price'              => $order->service_price,
                'total_price'                => $order->total_price,
                'base_number'                => $order->base_number
            ];
        };
    }

    public static function getCreatedFormatter()
    {
        return function ($order) {
            return [
                'show_continue_date'         => $order->ui_repository->show_continue_date,
                'show_social_security_price' => $order->ui_repository->show_social_security_price,
                'show_pension_price'         => $order->ui_repository->show_pension_price,
                'show_lost_job_price'        => $order->ui_repository->show_lost_job_price,
                'show_work_accident_price'   => $order->ui_repository->show_work_accident_price,
                'show_born_price'            => $order->ui_repository->show_born_price,
                'show_medical_price'         => $order->ui_repository->show_medical_price,
                'show_service_price'         => $order->ui_repository->show_service_price,
                'show_total_price'           => $order->ui_repository->show_total_price,
                'id'                         => $order->id
            ];
        };
    }
}