<?php

namespace App\Models;

use Dom\Attr;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' , 'image' , 'slug'
    ];


    public function products()
    {
        return $this->hasMany(Product::class , 'category_id' , 'id');
    }

    // الأب
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "Category Name Is: " . ucfirst($value),
        );
    }
}
