<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChowhubCart extends Model
{

    protected $fillable = ['id', 'key', 'user_id'];

    public function items()
    {
        return $this->hasMany(ChowhubCartItem::class, 'cart_id', 'id');
    }

}
