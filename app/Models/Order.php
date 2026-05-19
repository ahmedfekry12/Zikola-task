<?php

namespace App\Models;

use App\Casts\AsJson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'status',
        'options',
    ];

    protected $hidden = [
        'created_at' , 'updated_at' , 'deleted_at'
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'product_id' => 'integer',
            'quantity' => 'integer',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
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
