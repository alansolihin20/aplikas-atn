<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Items;
use App\Models\Inventory\Supplier;
use App\Models\User;


class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'item_id',
        'supplier_id',
        'jumlah',
        'tanggal_masuk',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'harga' => 'decimal:2'
    ];

   public function supplier()
   {
       return $this->belongsTo(Supplier::class, 'supplier_id');
   }

   public function item()
   {
       return $this->belongsTo(Items::class, 'item_id');
   }
   public function user()
   {
       return $this->belongsTo(User::class, 'created_by');
   }
}
