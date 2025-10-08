<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'id',
        'kode_barang',
        'kategori',
        'satuan',
        'harga_beli',
        'stok',
        'lokasi'
    ];

    protected $casts = [
        'id' => 'integer',
        'kode_barang' => 'string',
        'satuan' => 'string',
        'harga_beli' => 'decimal:2',
    ];
}
