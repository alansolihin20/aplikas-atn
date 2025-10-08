<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangOut extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'id',
        'item_id',
        'jumlah',
        'tanggal',
        'tujuan',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

}
