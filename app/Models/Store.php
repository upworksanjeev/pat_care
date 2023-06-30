<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    protected $fillable = [
        'name','lightspeed_vendor_id','address','description','city','state','country','zip_code','url','direction_link'
    ];
    public function storeGallery()
    {
        return $this->hasMany(StoreGallery::class,'store_id','id');
    }
}
