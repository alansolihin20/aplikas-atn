<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTransaction extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'id',
        'name',
        'type',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'type' => 'string', // Assuming type is a string, adjust if it's an enum or other type
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }
}
