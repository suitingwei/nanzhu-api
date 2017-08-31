<?php
/**
 * Created by PhpStorm.
 * User: sui
 * Date: 2017/1/10
 * Time: 下午2:36
 */

namespace App\Repositories;

use App\Formatters\Malls\ProductFormatter;
use App\Models\Product;

class ProductRepository extends Repository
{
    public function fetchListForOp()
    {
        return Product::orderBy('sort', 'desc')->paginate(20)->map(
            ProductFormatter::getListFormatterForAdmin()
        );
    }

    public function fetchListForApp()
    {
        return Product::shown()->orderBy('sort', 'desc')->paginate(20)->map(
            ProductFormatter::getListFormatter()
        );
    }
}