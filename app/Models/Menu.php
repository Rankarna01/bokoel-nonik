<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'photo',
        'category_id', 'is_available',
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
