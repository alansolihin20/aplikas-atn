<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Supplier;
use App\Models\Inventory\BarangMasuk;
use App\Models\Inventory\BarangKeluar;
use App\Models\Inventory\RequestBarang;
class Items extends Model
{
    use HasFactory;
    protected $table = 'items';

    protected $fillable = [
        'nama_item',
        'deskripsi',
        'satuan',
        'stok',
        'supplier_id',
    ];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'item_id');
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'item_id');
    }

    public function requests()
    {
        return $this->hasMany(RequestBarang::class, 'item_id');
    }
}
