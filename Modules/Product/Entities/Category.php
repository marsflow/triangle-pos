<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\CategoryFactory::new();
    }

    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
