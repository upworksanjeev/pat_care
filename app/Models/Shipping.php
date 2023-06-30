<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'sh_name',
        'sh_address',
        'sh_city',
        'sh_state',
        'sh_country',
        'sh_zip_code',
        'sh_phone',
        'sh_email',
    ];

}
