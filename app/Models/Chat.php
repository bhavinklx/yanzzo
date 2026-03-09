<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'chat_id';

    protected $fillable = [
        'product_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    public function sender()
    {
        return $this->belongsTo(Customer::class, 'sender_id', 'customer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Customer::class, 'receiver_id', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
