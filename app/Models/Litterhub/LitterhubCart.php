<?php

namespace App\Models\Litterhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitterhubCart extends Model
{

    protected $fillable = ['id', 'key', 'user_id'];

    public function items()
    {
        return $this->hasMany(LitterhubCartItem::class, 'cart_id', 'id');
    }

}
