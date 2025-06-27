<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // protected $fillable = [
    //     'name',
    //     'slug',
    //     'sort_description',
    //     'description',
    //     'price',
    //     'sale_price',
    //     'SKU',
    //     'stock_status',
    //     'featured',
    //     'quantity',
    //     'image',
    //     'images',
    //     'category_id',
    //     'brand_id'
    // ];



    public function createCategory(array $input)
    {

        return self::query()->create($input);
    }
}
