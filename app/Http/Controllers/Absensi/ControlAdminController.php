<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShiftSchedule;

class ControlAdminController extends Controller
{
    public function index()
    {
        $shiftSchedules = ShiftSchedule::with('user', 'shift')->get();
        return view('adminAbsensi.index', compact('shiftSchedules'));
        
    }

    public function storeSchedule(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'date' => 'required|date',
        ]);

        // Simpan jadwal shift
        ShiftSchedule::create([
            'user_id' => $request->user_id,
            'shift_id' => $request->shift_id,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Jadwal shift berhasil disimpan.');
    }
}
