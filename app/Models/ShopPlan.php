<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'address',
        'date_scheduled',
        'budget',
        'number_of_items',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
