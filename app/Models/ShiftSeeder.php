<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ShiftSchedule;
use Carbon\Carbon;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        $teknisi = User::where('role', 'teknisi')->get();

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        foreach ($teknisi as $user) {
            $date = $start->copy();
            while ($date <= $end) {
                ShiftSchedule::create([
                    'user_id' => $user->id,
                    'shift_date' => $date->format('Y-m-d'),
                    'shift_type' => rand(1, 2) // shift random
                ]);
                $date->addDay();
            }
        }
    }
}
