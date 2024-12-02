<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialOffer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product(){
        return $this->hasOne(Product::class, 'id','product_id')->select('id', 'name', 'slug', 'new_price','old_price','stock');
    }
}
