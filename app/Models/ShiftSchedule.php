<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    protected $table = 'shift_schedules';
    protected $fillable = [
        'user_id',
        'shift_date',
        'shift_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
