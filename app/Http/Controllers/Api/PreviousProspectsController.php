<?php

namespace App\Http\Controllers\Api;

use App\Formatters\PreviousProspectFormatter;
use App\Models\Movie;
use App\Models\PreviousProspect;
use App\Models\PreviousProspectPower;
use App\User;
use Illuminate\Http\Request;

class PreviousProspectsController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user  = User::find($request->input('user_id'));
        $movie = Movie::find($movieId = $request->input('movie_id'));

        $previousProspects = PreviousProspect::where('movie_id', $movieId)->orderBy('created_at', 'desc');

        if ($q = $request->input('q')) {
            $previousProspects = $previousProspects->where('content', 'like', '%' . $q . '%');
        }

        $previousProspects = $previousProspects->paginate(20)->map(PreviousProspectFormatter::getListFormatter($user));

        return $this->responseSuccess('成功', [
            'previous_prospects' => $previousProspects,
            'can_create'         => $user->hadAssignedPowerInMovie($movieId, PreviousProspectPower::class)
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $previousProspect = PreviousProspect::create([
            'user_id'  => $request->input('user_id'),
            'movie_id' => $request->input('movie_id'),
            'content'  => $request->input('content')
        ]);

        $previousProspect->pushMessages();

        $previousProspect->record();

        return $this->responseSuccess();
    }

    /**
     * @param         $id
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        $userId           = $request->input('user_id');
        $previousProspect = PreviousProspect::find($id);

        //用户读取这个日报表
        PreviousProspect::userReadMessage($id, $userId);

        $formatter = PreviousProspectFormatter::getShowFormatter();

        return $this->responseSuccess('成功', ['previous_prospect' => $formatter($previousProspect)]);
    }

    /**
     * Update the previous prospect.
     *
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $user             = User::find($request->input('user_id'));
        $movie            = Movie::find($movieId = $request->input('movie_id'));
        $previousProspect = PreviousProspect::find($id);

        if (!$user->hadAssignedPowerInMovie($movieId, PreviousProspectPower::class)) {
            return $this->responseFail('无权限操作');
        }

        $previousProspect->update($request->only(['user_id', 'movie_id', 'content']));

        $previousProspect->refreshAllReceiversReadStatus();

        $previousProspect->record();

        return $this->responseSuccess();
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRecords($id)
    {
        $previousProspect = PreviousProspect::find($id);

        $records = $previousProspect->records()->orderBy('previous_prospect_records.created_at', 'desc')->paginate(20);

        return $this->responseSuccess('success', [
            'records' => $records->toArray()['data']
        ]);
    }

}
