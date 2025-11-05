<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeknisiController extends Controller
{
    public function teknisiDashboard()
    {
        return view('dashboard.teknisi');
    }


    public function weeklyTeknisi()
{
    $user = Auth::user();
    $startOfWeek = Carbon::now()->startOfWeek(); // Senin
    $endOfWeek   = Carbon::now()->endOfWeek();   // Minggu

    // Ambil jadwal shift milik teknisi yang login
    $schedules = ShiftSchedule::with('shift')
        ->where('user_id', $user->id)
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->get()
        ->keyBy('date'); // Supaya gampang ambil berdasarkan tanggal

    return view('teknisi.dashboard', compact('user', 'schedules', 'startOfWeek', 'endOfWeek'));
}
}