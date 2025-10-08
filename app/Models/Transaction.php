<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'id',
        'category_id',
        'description',
        'amount',
        'transaction_type',
        'transaction_date',
    ];


    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryTransaction::class, 'category_id');
    }
}
