<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i:s',
        'updated_at' => 'datetime:M d, Y h:i:s',
    ];
    protected $fillable = [
        'name', 'slug', 'lightspeed_category_id','parent', 'feature_image', 'status', 'type','order','tag_line','color','description'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y h:i:s', strtotime($value));
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\Category', 'parent')->with('childrens');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
