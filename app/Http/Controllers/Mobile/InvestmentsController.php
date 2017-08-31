<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\ClientMovieRequirement;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class InvestmentsController
 * @package App\Http\Controllers\Mobile
 */
class InvestmentsController extends Controller
{
    /**
     * @var array
     */
    static $investTypes = ['植入', '赞助', '投资'];
    /**
     * @var array
     */
    static $movieTypes = ['电影', '网大', '电视剧'];
    /**
     * @var array
     */
    static $rewardTypes = ['片尾Logo', '片花使用', '冠名'];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clients()
    {
        return view('mobile.investments.clients', [
            'investTypes' => self::$investTypes,
            'movieTypes'  => self::$movieTypes,
            'rewardTypes' => self::$rewardTypes,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function movies()
    {
        return view('mobile.investments.movie', [
            'investTypes' => self::$investTypes,
            'movieTypes'  => self::$movieTypes,
            'rewardTypes' => self::$rewardTypes,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeClients(Request $request)
    {
        $this->validate($request, ClientMovieRequirement::$storeRules);

        ClientMovieRequirement::create([
            'invest_types'  => implode(',', $request->input('invest_types')),
            'movie_types'   => implode(',', $request->input('movie_types')),
            'reward_types'  => implode(',', $request->input('reward_types')),
            'start_date'    => $request->input('start_date'),
            'end_date'      => $request->input('end_date'),
            'budget_bottom' => $request->input('budget_bottom'),
            'budget_top'    => $request->input('budget_top'),
            'type'          => ClientMovieRequirement::TYPE_CLIENT
        ]);

        return redirect('/mobile/clients')->with(['success' => true]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMovie(Request $request)
    {
        $this->validate($request, ClientMovieRequirement::$storeRules);

        ClientMovieRequirement::create([
            'invest_types'  => implode(',', $request->input('invest_types')),
            'movie_types'   => implode(',', $request->input('movie_types')),
            'reward_types'  => implode(',', $request->input('reward_types')),
            'start_date'    => $request->input('start_date'),
            'end_date'      => $request->input('end_date'),
            'budget_bottom' => $request->input('budget_bottom'),
            'budget_top'    => $request->input('budget_top'),
            'type'          => ClientMovieRequirement::TYPE_MOVIE
        ]);

        return redirect('/mobile/clients')->with(['success' => true]);
    }
}
