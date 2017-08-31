<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class Product
 * @package App\Models
 * @property Collection Pictures
 * @property String     introduction
 * @property String     title
 * @property Collection pictures
 * @property Collection banners
 * @property int        id
 * @property Collection brands
 */
class Product extends Model
{
    const STATUS_SHOW     = 1;      //Product on shelf.
    const STATUS_NOT_SHOW = 0;  //Product not on shelf.

    /**
     * @var array
     */
    public $fillable = ['title', 'introduction', 'sort', 'is_show'];

    /**
     * @var array
     */
    public $appends = ['price'];

    /**
     * A product may have many pictures.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class, 'product_id', 'id');
    }

    /**
     * A product may have many banners.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banners()
    {
        return $this->hasMany(Banner::class, 'product_id', 'id');
    }

    /**
     * A product may belongs to many brands.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'product_brands');
    }

    /**
     * Get the product cover attribute.
     * @return string
     */
    public function getProductCoverAttribute()
    {
        return $this->banners->pluck('cover')->first() ?: '';
    }

    /**
     * A product may have many prices.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    /**
     * Get the short introduction.
     */
    public function getShortTitleAttribute()
    {
        return mb_substr($this->title, 0, 30);
    }

    /**
     * Get the products shown.
     *
     * @param $query
     *
     * @return
     */
    public function scopeShown($query)
    {
        return $query->where('is_show', static::STATUS_SHOW);
    }

    /**
     * Get the lowest price of all avaible prices.
     */
    public function getPriceAttribute()
    {
        if ($this->allPrices()->count() > 0) {
            return $this->allPrices()->sort()->first();
        }

        return 0;
    }

    /**
     * Get the orginal price of all avaible prices.
     */
    public function getOriginalPriceAttribute()
    {
        return 0;
    }

    /**
     * Get all avaible sizes.
     *
     * @param array $column
     *
     * @return Collection
     */
    public function allSizes($column = null)
    {
        $column = $column ? (array)$column : ['*'];

        return ProductSize::whereIn('id', $this->prices()->pluck('product_size_id'))->get($column);
    }

    /**
     *
     * Get all avaible sizes and corresponding price.
     *
     * @param bool $withDesc
     *
     * @return array
     */
    public function allSizesAndPrices($withDesc = true)
    {
        $priceArray = $this->prices;

        if (count($priceArray) == 0) {
            return [];
        }

        return array_map(function ($productPrice) {
            return [
                'product_price_id' => $productPrice->id,
                'desc'             => $productPrice->productSize->desc,
                'price'            => $productPrice->price,
                'product_id'       => $productPrice->product_id,
                'product_size_id'  => $productPrice->product_size_id
            ];
        }, $priceArray->all());
    }

    /**
     * Get all avabile prices.
     * @return Collection
     */
    public function allPrices()
    {
        return $this->prices()->pluck('price');
    }

    /**
     * Create the relative images.
     *
     * @param Request $request
     */
    public function createRelativeImages(Request $request)
    {
        $imagesUrlArray = explode(',', $request->input('img_url'));

        foreach ($imagesUrlArray as $imageUrl) {
            if (empty($imageUrl)) {
                continue;
            }
            Picture::create([
                'product_id' => $this->id,
                'url'        => $imageUrl
            ]);
        }
    }

    /**
     * Create the relative banners.
     *
     * @param Request $request
     */
    public function createBanners(Request $request)
    {
        $imagesArray = explode(',', $request->input('banners'));

        foreach ($imagesArray as $imageUrl) {
            if (empty($imageUrl)) {
                continue;
            }

            Banner::create([
                'product_id' => $this->id,
                'url'        => $imageUrl,
                'cover'      => $imageUrl,
                'title'      => $this->title,
                'content'    => $this->introduction,
                'type'       => Banner::TYPE_PRODUCT
            ]);
        }
    }

    /**
     * Get the detail page url for product sharing.
     * @return string
     */
    public function getShareDetailUrlAttribute()
    {
        return env('APP_URL') . '/mobile/malls/products/' . $this->id;
    }

    public function isMovieClothesProduct()
    {

        foreach ($this->brands as $brand) {
            if ($brand->isMovieClothesBrand()) {
                \Log::info('function isMovieClothesProduct');
                return true;
            }
        }
        return false;
    }


}
