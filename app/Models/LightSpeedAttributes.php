<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LightSpeedAttributes extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    protected $fillable = [
        'name','attributes_id'
    ];

}
