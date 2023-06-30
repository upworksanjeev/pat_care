<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChowhubRatingGallery extends Model
{
    protected $table = 'chowhub_rating_galleries';
    use HasFactory;
    protected $fillable = [
        'rating_id',
        'image_path',



    ];
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];

}
