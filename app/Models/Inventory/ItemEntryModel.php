<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEntryModel extends Model
{
    use HasFactory;

    protected $table = 'item_entries';

    protected $fillable = [
        'supplier_id',
        'item_id',
        'qty',
        'price_per_unit',
        'total_price',
        'received_by',
        'request_id',
    ];
}
