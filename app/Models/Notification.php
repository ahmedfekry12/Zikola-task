<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'notifiable_id',
        'notifiable_type',
        'message',
    ];

    public function sender()
    {
        return $this->morphTo('sender');
    }

    public function receiver()
    {
        return $this->morphTo('receiver');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
