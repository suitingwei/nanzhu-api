<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\MovieBasement;
use App\Models\TradeScript;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TradeResourcesController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::get()->map(function (Company $company) {
            return [
                'id'     => $company->id,
                'title'  => $company->title,
                'cover'  => $company->logo,
                'h5_url' => $company->getCompanyShowPageUrl(),
            ];
        });

        $scripts = TradeScript::get()->map(function (TradeScript $script) {
            return [
                'id'     => $script->id,
                'title'  => $script->title,
                'cover'  => '',
                'h5_url' => $script->getScriptShowPageUrl(),
            ];
        });

        $otherBasements = MovieBasement::get()->map(function (MovieBasement $basement) {
            return [
                'id'     => $basement->id,
                'title'  => $basement->title,
                'cover'  => $basement->cover,
                'h5_url' => $basement->getCompanyShowPageUrl(),
            ];
        });

        $searchResults = array_merge($companies->toArray(), $scripts->toArray(), $otherBasements->toArray());

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = collect($searchResults);

        //Define how many items we want to be visible in each page
        $perPage = 15;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice($currentPage * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);

        return $paginatedSearchResults;
    }
}
