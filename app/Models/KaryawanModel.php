<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KaryawanModel extends Model
{
    use HasFactory;

    protected $table = 'karyawan'; //

    protected $fillable = [
        'user_id',
        'name',
        'nik',
        'jabatan',
        'tanggal_masuk',
        'gaji_pokok',
        'tunjangan',
        'status',
        'foto',
    ];

    protected $primaryKey = 'id';

    protected $casts = [
        'user_id' => 'integer',
        'nik' => 'string',
        'jabatan' => 'string',
        'tanggal_masuk' => 'date',
        'gaji_pokok' => 'decimal:2',
        'tunjangan' => 'decimal:2',
        'status' => 'string',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
