<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'id',
        'nama',
        'kontak',
        'alamat',
    ];

}
