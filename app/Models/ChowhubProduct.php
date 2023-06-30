<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChowhubProduct extends Model
{

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    protected $fillable = [
        'productName', 'type', 'feature_image', 'description', 'real_price', 'sale_price', 'category_id', 'status','food_type','age','pet_type','protein_type','sku','store_id','weight','quantity'
    ];
    protected $appends = array('availTags','availBackendTags','availBrands');



    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function store()
    {
        return $this->belongsTo(ChowhubStore::class, 'store_id');
    }

    public function productGallery()
    {
        return $this->hasMany(ChowhubProductGallery::class, 'product_id', 'id');
    }

    public function productVariation()
    {
        return $this->hasMany(ChowhubProductVariation::class, 'product_id', 'id');
    }

    public function variationAttributesValue()
    {
        return $this->hasMany(ChowhubVariationAttributeValue::class, 'product_id', 'id');
    }

    public function tags()
    {

        return $this->hasMany(ChowhubProductTag::class, 'product_id', 'id');
    }
    public function brands()
    {

        return $this->hasMany(ChowhubProductBrand::class, 'product_id', 'id');
    }
    public function backendtags()
    {

        return $this->hasMany(ChowhubProductBackendTag::class, 'product_id', 'id');
    }
    public function productDescriptionImage()
    {
        return $this->hasMany(ChowhubProductDescriptionImage::class, 'product_id', 'id');
    }

    public function productFeaturePageImage()
    {
        return $this->hasMany(ChowhubProductFeaturePageImage::class, 'product_id', 'id');
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
    public function getAvailBackendTagsAttribute()
    {
        $tags = $this->backendtags;
        $tagsData = [];
        foreach ($tags as $key => $tag)
        {
            $tagName = $tag->tagName;
            array_push($tagsData, $tagName->name);
        }
        $tagsData = implode(',', $tagsData);
        return $tagsData;
    }
    public function getAvailBrandsAttribute()
    {
        $tags = $this->brands;
        $tagsData = [];
        foreach ($tags as $key => $tag)
        {
            $tagName = $tag->brandName;
            array_push($tagsData, $tagName->name);
        }
        $tagsData = implode(',', $tagsData);
        return $tagsData;
    }
}
