<?php

namespace App\Http\Controllers\Api;

use App\Models\PurchaseItem;

class PurchaseItemsController extends BaseController
{
    public function index()
    {
        return PurchaseItem::all();
    }

    public function store()
    {

    }

}
