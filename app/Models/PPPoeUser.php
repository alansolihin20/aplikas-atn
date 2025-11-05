<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPPoeUser extends Model
{
    use HasFactory;

    protected $table = 'pppoe_users';

   protected $fillable = [
    'name', 'password', 'profile', 'service',
    'caller_id', 'remote_address', 'local_address', 'uptime',
    'bytes_in', 'bytes_out', 'disabled', 'comment', 'mikrotik_id'
];
}
