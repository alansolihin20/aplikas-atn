<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOutModel extends Model
{
    use HasFactory;

    protected $table = 'item_outs';

    protected $fillable = [
        'item_id',
        'qty',
        'used_by',
        'purpose',
        'note',
    ];
}
