<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Items;
use App\Models\User;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'item_id',
        'jumlah',
        'tujuan',
        'tanggal_keluar',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
