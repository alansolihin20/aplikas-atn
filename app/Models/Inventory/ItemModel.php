<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'sku',
        'stock',
        'unit',
        'description',
    ];
}
