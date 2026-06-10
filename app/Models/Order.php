<?php

namespace App\Models;

use App\Casts\AsJson;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_id',
        'number',
        'payment_method',
        'status',
        'payment_status',
        'delivery',
        'tax',
        'discount',
        'total',
    ];

    protected $hidden = [
        'updated_at' , 'deleted_at'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')
            ->using(OrderProduct::class)
            ->withPivot(['price' , 'quantity', 'options']);
    }

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'store_id' => 'integer',
            'status' => 'string',
            'options' => AsJson::class,
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => "Status: " . strtoupper($value)
        );
    }

    #[Scope]
    public function scopePending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    #[Scope]
    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', 'completed');
    }
}
