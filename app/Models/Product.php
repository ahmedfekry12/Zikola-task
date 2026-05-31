<?php

namespace App\Models;

use App\Casts\AsJson;
use App\Models\Category;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'image',
        'description',
        'quantity',
        'price',
        'compare_price',
        'options',
        'rate',
        'status'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_product'
        )
            ->withPivot([
                'price',
                'quantity',
                'options'
            ])
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'options' => AsJson::class,
        ];
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn($value) => "Product Description Is: " . ucfirst($value)
        );
    }

    #[Scope]
    protected function scopeExpensive(Builder $query): void
    {
        $query->where('price', '<', 1000);
    }
}
