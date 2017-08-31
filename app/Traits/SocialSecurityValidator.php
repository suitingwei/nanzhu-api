<?php
namespace App\Traits;

use Illuminate\Support\Collection;

trait SocialSecurityValidator
{
    /**
     * @param Collection $attributes
     *
     * @return array
     * @throws \Exception
     */
    public static function validateStoreData(Collection $attributes)
    {
        if ($userPhone = $attributes->get('user_phone', null)) {
            if (strlen($userPhone) != 11) {
                throw new \Exception('手机号位数不合法');
            }
        }

        if ($idNumber = $attributes->get('id_card_number', null)) {
            if (strlen($idNumber) != 18) {
                throw new \Exception('身份证号位数不合法');
            }
        }

        $validator = \Validator::make(
            $storeData = $attributes->only(array_keys(static::$storeRules))->all(),
            static::$storeRules,
            ['required' => 'The :attribute field is required.',]
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $storeData;
    }
}
