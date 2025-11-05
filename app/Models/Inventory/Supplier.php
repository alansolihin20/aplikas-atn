<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'nama_supplier',
        'kontak',
        'alamat',
    ];

    public function items()
    {
        return $this->hasMany(Items::class, 'supplier_id');
    }

}
