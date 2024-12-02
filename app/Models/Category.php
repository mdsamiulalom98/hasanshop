<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id')->select('id');
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id')->where('status', 1);
    }

}
