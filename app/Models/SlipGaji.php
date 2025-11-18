<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SlipGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'periode',
        'gaji_pokok',
        'insentif_harian',
        'hari_kerja',
        'bpjs_tk',
        'bpjs_kes',
        'pinjaman',
        'gaji_bruto',
        'gaji_bersih',
        'is_received',
        'received_at',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function karyawan()
    {
        return $this->hasOne(KaryawanModel::class, 'user_id', 'user_id');
    }


}
