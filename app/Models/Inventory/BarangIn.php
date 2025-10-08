<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BarangIn extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'item_id',
        'jumlah',
        'harga',
        'tanggal',
        'supplier',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'harga' => 'decimal:2'
    ];

    public function Inventory()
    {
        return $this->belongsTo(\App\Models\Inventory\Inventory::class, 'item_id');
    }
}
