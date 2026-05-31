<?php

namespace App\Models;

use App\Casts\AsJson;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    use HasFactory;

    protected $table = 'order_product';

    // public $incrementing = true;

    // public $timestamps = false;


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    protected function casts(): array
    {
        return [
            'options' => AsJson::class,
        ];
    }
}
