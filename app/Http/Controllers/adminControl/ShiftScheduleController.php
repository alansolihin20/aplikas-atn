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
    $request->validate(['week_start' => 'required|date']);

    $startDate = Carbon::parse($request->week_start)->startOfWeek();
    $shifts = Shift::all();

    if ($shifts->count() < 2) {
        return back()->with('error', 'Minimal harus ada 2 shift untuk Auto Generate!');
    }

    $senior = User::where('role', 'teknisi')->whereIn('id', [2, 3])->get();
    $junior = User::where('role', 'teknisi')->whereIn('id', [4, 5])->get();

    if ($senior->count() < 2 || $junior->count() < 2) {
        return back()->with('error', 'Minimal harus ada 2 senior & 2 junior untuk rotasi otomatis!');
    }

    $weekNumber = $startDate->weekOfYear;
    $isEvenWeek = $weekNumber % 2 === 0;

    $pairShift1 = $isEvenWeek ? [$senior[1], $junior[1]] : [$senior[0], $junior[0]];
    $pairShift2 = $isEvenWeek ? [$senior[0], $junior[0]] : [$senior[1], $junior[1]];

    foreach (range(0,6) as $i) {
        $date = $startDate->copy()->addDays($i);

        if ($date->isSaturday()) {
            foreach ($pairShift1 as $user) {
                ShiftSchedule::updateOrCreate(
                    ['user_id'=> $user->id,'date'=>$date],
                    ['shift_id'=> $shifts[0]->id]
                );
            }
            continue;
        }

        if ($date->isSunday()) {
            foreach ($pairShift2 as $user) {
                ShiftSchedule::updateOrCreate(
                    ['user_id'=> $user->id,'date'=>$date],
                    ['shift_id'=> $shifts[0]->id]
                );
            }
            continue;
        }

        foreach ($pairShift1 as $user) {
            ShiftSchedule::updateOrCreate(
                ['user_id'=>$user->id,'date'=>$date],
                ['shift_id'=>$shifts[0]->id]
            );
        }
        
        foreach ($pairShift2 as $user) {
            ShiftSchedule::updateOrCreate(
                ['user_id'=>$user->id,'date'=>$date],
                ['shift_id'=>$shifts[1]->id] // shift kedua tersedia karena sudah divalidasi
            );
        }
    }

    return back()->with('success','Jadwal minggu ini berhasil dibuat!');
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

public function updateShift(Request $request)
{
    $request->validate([
        'schedule_id' => 'required|exists:shift_schedules,id',
        'shift_id' => 'required|exists:shifts,id',
    ]);

    $schedule = ShiftSchedule::findOrFail($request->schedule_id);
    $schedule->shift_id = $request->shift_id;
    $schedule->save();

    return response()->json(['success' => true, 'message' => 'Shift berhasil diubah.']);
}

    public function deleteByWeek(Request $request)
    {
        $request->validate([
            'week_start' => 'required|date',
        ]);

        $startDate = Carbon::parse($request->week_start)->startOfWeek();
        $endDate = $startDate->copy()->addDays(6);

        ShiftSchedule::whereBetween('date', [$startDate, $endDate])->delete();

        return back()->with('success', 'Jadwal shift untuk minggu tersebut berhasil dihapus.');
    }     

    public function destroy($id)
    {
        $schedule = ShiftSchedule::findOrFail($id);
        $schedule->delete();

        return back()->with('success', 'Jadwal shift berhasil dihapus.');   
    }
}
