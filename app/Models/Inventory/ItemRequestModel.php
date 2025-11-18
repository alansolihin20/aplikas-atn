<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequestModel extends Model
{
    use HasFactory;

    protected $table = 'item_requests';
    
    protected $fillable = [
        'user_id',
        'item_id',
        'item_name_temp',
        'qty',
        'status',
        'note',
    ];
}
