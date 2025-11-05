<?php

namespace App\Http\Controllers\adminControl;
use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftScheduleController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'teknisi')->get();
        $shifts = Shift::all();
        $schedules = ShiftSchedule::with(['user', 'shift'])
            ->orderBy('date', 'desc')
            ->get();

        return view('adminAbsensi.index', compact('users', 'shifts', 'schedules'));
    }

    public function store(Request $request)
    {
       $request->validate([
        'user_id' => 'required|array|min:1',
        'shift_id' => 'required|exists:shifts,id',
        'week_start' => 'required|date',
    ]);

    $startDate = Carbon::parse($request->week_start);
    $endDate = $startDate->copy()->addDays(6);

    foreach ($request->user_ids as $userId) {
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Hindari duplikat
            $existing = ShiftSchedule::where('user_id', $userId)
                ->whereDate('date', $date)
                ->first();

            if (!$existing) {
                ShiftSchedule::create([
                    'user_id' => $userId,
                    'shift_id' => $request->shift_id,
                    'date' => $date->toDateString(),
                ]);
            }
        }
    }

    return back()->with('success', 'Jadwal shift berhasil dibuat untuk 1 minggu.');
    }

  public function autoGenerate(Request $request)
{
    $request->validate([
        'week_start' => 'required|date',
    ]);

    $startDate = Carbon::parse($request->week_start)->startOfWeek();
    $shifts = Shift::all();

    // Ambil teknisi (ubah ID sesuai data kamu)
    $senior = User::where('role', 'teknisi')->whereIn('id', [2, 3])->get();
    $junior = User::where('role', 'teknisi')->whereIn('id', [4, 5])->get();

    // Tentukan rotasi mingguan
    $weekNumber = $startDate->weekOfYear;
    $isEvenWeek = $weekNumber % 2 === 0;

    // Kalau minggu genap dan ganjil, tukar pasangan biar adil
    if ($isEvenWeek) {
        $pairShift1 = [$senior[1], $junior[1]]; // Pasangan A
        $pairShift2 = [$senior[0], $junior[0]]; // Pasangan B
    } else {
        $pairShift1 = [$senior[0], $junior[0]]; // Pasangan A
        $pairShift2 = [$senior[1], $junior[1]]; // Pasangan B
    }

    // Loop 7 hari (Senin–Minggu)
    foreach (range(0, 6) as $i) {
        $date = $startDate->copy()->addDays($i);

        // ✅ Sabtu (index 5) & Minggu (index 6)
        if ($date->isSaturday()) {
            // Sabtu: pasangan A
            foreach ($pairShift1 as $user) {
                ShiftSchedule::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date],
                    ['shift_id' => $shifts[0]->id] // shift 1
                );
            }
            continue;
        }

        if ($date->isSunday()) {
            // Minggu: pasangan B
            foreach ($pairShift2 as $user) {
                ShiftSchedule::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date],
                    ['shift_id' => $shifts[0]->id] // shift 1
                );
            }
            continue;
        }

        // ✅ Senin–Jumat: semua teknisi masuk
        foreach ($pairShift1 as $user) {
            ShiftSchedule::updateOrCreate(
                ['user_id' => $user->id, 'date' => $date],
                ['shift_id' => $shifts[0]->id] // shift 1
            );
        }

        foreach ($pairShift2 as $user) {
            ShiftSchedule::updateOrCreate(
                ['user_id' => $user->id, 'date' => $date],
                ['shift_id' => $shifts[1]->id] // shift 2
            );
        }
    }

    return back()->with('success', 'Jadwal minggu ini berhasil dibuat!');
}


public function weekly(Request $request)
{
    $startOfWeek = Carbon::now()->startOfWeek(); // Senin
    $endOfWeek   = Carbon::now()->endOfWeek();   // Minggu

    // Ambil jadwal shift 1 minggu
    $schedules = ShiftSchedule::with(['user', 'shift'])
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->get()
        ->groupBy('user_id');

    $users = User::where('role', 'teknisi')->get();

    return view('adminAbsensi.weekly', compact('schedules', 'users', 'startOfWeek', 'endOfWeek'));
}


}
