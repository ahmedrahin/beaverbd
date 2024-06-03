<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    //shipping
    public function shipping(){
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
