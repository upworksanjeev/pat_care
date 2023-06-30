<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    protected $appends = array('availTags');
    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    protected $fillable = [
        'productName','brand_id','matrixId', 'type', 'banner_image', 'about_description', 'feature_image','seo_title','meta_description', 'description', 'real_price', 'sale_price', 'category_id', 'status','store_id','weight','quantity','sku','description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function productGallery()
    {
        return $this->hasMany(ProductGallery::class, 'product_id', 'id');
    }

    public function productDescriptionDetail()
    {
        return $this->hasMany(ProductDescriptionDetail::class, 'product_id', 'id');
    }

    public function productVariation()
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'id');
    }

    public function variationAttributesValue()
    {
        return $this->hasMany(VariationAttributeValue::class, 'product_id', 'id');
    }

    public function tags()
    {

        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }

    public function getAvailTagsAttribute()
    {
        $tags = $this->tags;
        $tagsData = [];
        foreach ($tags as $key => $tag)
        {
            $tagName = $tag->tagName;
            array_push($tagsData, $tagName->name);
        }
        $tagsData = implode(',', $tagsData);
        return $tagsData;
    }

}
