<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Items;
use App\Models\User;

class RequestBarang extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'item_id',
        'jumlah',
        'alasan',
        'status',
        'requested_by',
        'approved_by',
        'tanggal_request',
        'tanggal_approve',
    ];

    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
