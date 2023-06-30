<?php

namespace App\Models\Litterhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitterhubProduct extends Model
{

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    protected $fillable = [
        'productName', 'type', 'feature_image', 'description', 'real_price', 'sale_price', 'category_id', 'status','scented','clumping','cat_count','litter_material','sku','store_id','weight','quantity'
    ];
    protected $appends = array('availTags','availBackendTags','availBrands');


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function store()
    {
        return $this->belongsTo(LitterhubStore::class, 'store_id');
    }

    public function productGallery()
    {
        return $this->hasMany(LitterhubProductGallery::class, 'product_id', 'id');
    }

    public function productVariation()
    {
        return $this->hasMany(LitterhubProductVariation::class, 'product_id', 'id');
    }

    public function variationAttributesValue()
    {
        return $this->hasMany(LitterhubVariationAttributeValue::class, 'product_id', 'id');
    }
    public function brands()
    {

        return $this->hasMany(LitterhubProductBrand::class, 'product_id', 'id');
    }
    public function tags()
    {

        return $this->hasMany(LitterhubProductTag::class, 'product_id', 'id');
    }
    public function backendtags()
    {

        return $this->hasMany(LitterhubProductBackendTag::class, 'product_id', 'id');
    }
    public function productDescriptionImage()
    {
        return $this->hasMany(LitterhubProductDescriptionImage::class, 'product_id', 'id');
    }

    public function productFeaturePageImage()
    {
        return $this->hasMany(LitterhubProductFeaturePageImage::class, 'product_id', 'id');
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
