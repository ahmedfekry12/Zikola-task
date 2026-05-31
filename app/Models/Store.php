<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'address',
        'description',
        'logo_image',
        'cover_image',
        'status'
    ];

    public function sentNotifications()
    {
        return $this->morphMany(Notification::class, 'sender');
    }

    public function receivedNotifications()
    {
        return $this->morphMany(Notification::class, 'receiver');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => "The Store Is " . ucfirst($value),
        );
    }

    #[Scope]
    public static function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }
}
