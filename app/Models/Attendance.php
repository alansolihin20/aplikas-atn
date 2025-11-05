<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = [
        'user_id',
        'schedule_id',
        'shift_id',
        'check_in',
        'check_in_lat',
        'check_in_lng',
        'check_out',
        'check_out_lat',
        'check_out_lng',
        'reason',
        'photo_url'
    ];

   

    public function shiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'shift_id');
    }

   public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Relasi ke tabel shifts */
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    /** Relasi ke tabel shift_schedules */
    public function schedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'schedule_id');
    }


    public $timestamps = true;
    const UPDATED_AT = null;
}
