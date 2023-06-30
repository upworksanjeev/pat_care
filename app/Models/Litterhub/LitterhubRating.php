<?php

namespace App\Models\Litterhub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LitterhubRating extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'description',
        'status',

    ];
    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function product()
    {
        return $this->belongsTo(LitterhubProduct::class,'product_id','id');
    }
}
