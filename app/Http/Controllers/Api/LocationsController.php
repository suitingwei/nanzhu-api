<?php

namespace App\Http\Controllers\Api;

use App\Consts\ResponseCodes;
use App\Models\UserLocation;
use App\User;
use Illuminate\Http\Request;

class LocationsController extends BaseController
{
    /**
     * @param $userId
     */
    public function getUserLocation($userId)
    {

    }


    /**
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserLocation($userId, Request $request)
    {
        $user = User::find($userId);

        UserLocation::create([
            'user_id'   => $userId,
            'longitude' => $request->input('longitude'),
            'latitude'  => $request->input('latitude'),
        ]);

        return response()->json([
            'ret'  => ResponseCodes::SUCCESS,
            'msg'  => ['is_in_black' => $user->is_in_black],
            'data' => [
                'is_in_black' => $user->is_in_black
            ]
        ]);
    }

    /**
     * Store the user's location per minutes.
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserLocationPerMinute($userId, Request $request)
    {
        $user      = User::find($userId);

        $deCode    = urldecode($request->input('locations'));
        $locations = json_decode($deCode);

        if(!$locations){
            $arrs = explode('|',$deCode);
            foreach ($arrs as $arr){
                $keyarrs = explode(',',$arr);
                //dd($keyarrs);
                $newArr= array('time'=>'','longitude'=>'','latitude'=>'');
                foreach ($keyarrs as $keyarr){
                    //dd($keyarr);
                    $key = explode(':',$keyarr);

                    switch ($key[0]){
                        case 'time':
                            $newArr['time']=$key[1];
                        case 'longitude':
                            $newArr['longitude']=$key[1];
                        case 'latitude':
                            $newArr['latitude']=$key[1];
                    }

                }
                UserLocation::create([
                    'user_id'    => $userId,
                    'longitude'  => $newArr['longitude'],
                    'latitude'   => $newArr['latitude'],
                    'created_at' => $newArr['time'],
                ]);
            }
            return response()->json([
                'ret'  => ResponseCodes::SUCCESS,
                'msg'  => ['is_in_black' => $user->is_in_black],
                'data' => [
                    'is_in_black' => $user->is_in_black
                ]
            ]);
        }
        else {
            foreach ($locations->locations as $location) {
                UserLocation::create([
                    'user_id'    => $userId,
                    'longitude'  => $location->longitude,
                    'latitude'   => $location->latitude,
                    'created_at' => $location->time,
                ]);
            }
        }
        return response()->json([
            'ret'  => ResponseCodes::SUCCESS,
            'msg'  => 'Success',
            'data' => [
                'is_in_black' => $user->is_in_black
            ]
        ]);
    }
}
