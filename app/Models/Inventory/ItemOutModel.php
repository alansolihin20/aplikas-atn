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
        'user_id',
    ];
    public function item()
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }

    // Opsional jika ingin tahu siapa user yang memakai
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'used_by');
    }
}
