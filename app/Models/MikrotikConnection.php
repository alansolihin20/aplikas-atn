<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;


class MikrotikConnection extends Model
{
    use HasFactory;

    protected $table = 'mikrotik_connections';

    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
        'use_ssl',
        'notes'
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null; // atau tangani kesalahan sesuai kebutuhan Anda
        }
    }

    public function getEncryptedPasswordAttribute()
    {
        return $this->attributes['password'] ?? null;
    }
}
