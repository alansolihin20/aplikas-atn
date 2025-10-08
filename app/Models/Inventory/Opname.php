<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opname extends Model
{
    use HasFactory;

    protected $table = 'stok_opname';

    protected $fillable = [
        'id',
        'item_id',
        'stok_sistem',
        'stok_fisik',
        'tanggal',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

}
