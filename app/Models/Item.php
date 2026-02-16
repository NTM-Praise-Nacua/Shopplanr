<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'expected_quantity',
        'actual_quantity',
    ];

    protected $hidden = [
        'shop_plan_id'
    ];
}
