<?php

namespace App\Models;

use App\Casts\AsJson;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory , SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'options',
    ];

    protected $hidden = [
        'created_at' , 'updated_at' , 'deleted_at'
    ];

     protected function casts(): array
    {
        // return [
        //     'name' => 'string',
        //     'description' => 'string',
        //     'price' => 'decimal:2',
        // ];
        
        return [
            'options' => AsJson::class,
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) =>"The Price Of This PC Is $" .  number_format($value, 2)
        );
    }

    #[Scope]
    public function scopeExpensive(Builder $query): void
    {
        $query->where('price', '<', 1000);
    }
}
