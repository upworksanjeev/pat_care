<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    protected $fillable = [
        'age',
        'user_id',
        'type',
        'name',
        'image',

       ];
   
       public function user()
       {
           return $this->belongsTo(User::class,'user_id','id');
       }

}
