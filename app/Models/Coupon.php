<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    use HasFactory;

    protected $fillable = [
        'name', 'code', 'category_id','lifetime_coupon', 'product_id', 'type', 'apply_to', 'apply_for', 'value', 'count','started_at', 'expired_at', 'product_type'
    ];
    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];

    public function couponAssign()
    {
        return $this->hasMany(CouponAssign::class, 'coupon_id', 'id');
    }
}
