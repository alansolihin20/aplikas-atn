<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KaryawanModel;

class AbsensiTeknisi extends Model
{
    use HasFactory;


    protected $table = 'absensi_teknisi';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
    ];

    public function karyawan()
    {
        return $this->belongsTo(KaryawanModel::class, 'user_id');
    }
}
