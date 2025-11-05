<?php

namespace App\Http\Controllers\Shift;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ShiftSchedule;
use App\Models\OfficeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();
        $today = now()->toDateString(); // Menggunakan now() untuk tanggal hari ini

        // 2. Cek absensi hari ini (Hanya perlu sekali)
        $absenHariIni = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $today)
            ->first();

        // 3. Ambil riwayat absensi (ambil semua, tidak dibatasi 10 seperti di kode awal yang diulang)
        $riwayat = Attendance::where('user_id', $user->id)
            ->orderBy('check_in', 'desc')
            ->get();
            
        // 4. Ambil data kantor
        $kantor = OfficeLocation::first();

        // 5. Cek jadwal shift hari ini
        $schedule = ShiftSchedule::with('shift')
            ->where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // 6. Handle jika tidak ada jadwal shift
        if (!$schedule) {
            return view('absensi.index', [
                'absenHariIni' => $absenHariIni ?? null,
                'riwayat' => $riwayat ?? collect(),
                'kantor' => $kantor ?? null,
                'schedule' => null,
                'shift' => null,
                'errorMessage' => '❌ Tidak ada jadwal shift untuk hari ini. Silakan hubungi admin atau cek tanggal shift di sistem.'
            ]);
        }


        // hitung batas waktu
        $canCheckIn = false;
        $canCheckOut = false;

        if ($schedule && $schedule->shift) {
            $shiftStart = Carbon::parse($schedule->shift->start_time);
            $shiftEnd   = Carbon::parse($schedule->shift->end_time);

            $now = now();

            // absen masuk: mulai 30 menit sebelum shift, tutup 1 jam setelahnya (menggunakan 10 menit sesuai kode checkIn)
            if ($now->between($shiftStart->copy()->subMinutes(10), $shiftStart->copy()->addHour())) {
                $canCheckIn = true;
            }

            // absen keluar: boleh mulai 30 menit sebelum jam selesai, tutup 1 jam setelahnya
            if ($now->between($shiftEnd->copy()->subMinutes(30), $shiftEnd->copy()->addHour())) {
                $canCheckOut = true;
            }
        }

        
        $shift = $schedule->shift;
        
        // 8. Tampilkan view
        return view('absensi.index', compact(
            'absenHariIni', 
            'riwayat', 
            'kantor', 
            'schedule', 
            'shift',
            'canCheckIn',
            'canCheckOut'
        ));
    }

    // ==============================
    // ABSEN MASUK (CHECK-IN)
    // ==============================
    public function checkIn(Request $request)
{
    $user = Auth::user();

    if (!$user || $user->role !== 'teknisi') {
        return response()->json(['message' => 'Hanya teknisi yang dapat absen.'], 403);
    }

    $request->validate([
        'latitude'  => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $office = OfficeLocation::first();
    if (!$office) {
        return response()->json(['message' => 'Lokasi kantor belum diset.'], 500);
    }

    $today = Carbon::today();
    $sudahAbsen = Attendance::where('user_id', $user->id)
        ->whereDate('check_in', $today)
        ->first();

    if ($sudahAbsen) {
        return response()->json(['message' => 'Anda sudah absen masuk hari ini.'], 400);
    }

    $distance = $this->getDistanceInMeters(
        (float)$office->latitude,
        (float)$office->longitude,
        (float)$request->latitude,
        (float)$request->longitude
    );

    $schedule = ShiftSchedule::where('user_id', $user->id)
        ->whereDate('date', now()->toDateString())
        ->first();

    if (!$schedule) {
        return response()->json(['message' => 'Anda belum memiliki jadwal shift untuk hari ini.'], 400);
    }

    $shift = $schedule->shift;
    $now = now();

    // Batasan waktu Check-In
    if ($now->lt(Carbon::parse($shift->start_time)->subMinutes(30)) ||
        $now->gt(Carbon::parse($shift->start_time)->addHour())) {
        return response()->json(['message' => 'Waktu absen masuk sudah ditutup atau belum dimulai.'], 403);
    }

    // ✅ Cek jarak dulu sebelum insert data
    if ($distance > (int)$office->radius) {
        return response()->json([
            'message'  => 'Anda berada di luar area kantor. Silakan Pergi ke Kantor untuk absen masuk.',
            'status'   => 'outside_office',
            'distance' => round($distance, 2),
            'radius'   => (int)$office->radius,
        ], 200);
    }

  

    // ✅ Baru simpan kalau dalam area kantor
    try {
        $attendance = Attendance::create([
            'user_id'       => $user->id,
            'shift_id'      => $schedule->shift_id,
            'schedule_id'   => $schedule->id,
            'check_in'      => now(),
            'check_in_lat'  => $request->latitude,
            'check_in_lng'  => $request->longitude,
        ]);

        return response()->json([
            'message'       => 'Absen masuk berhasil. Anda berada di dalam area kantor.',
            'status'        => 'in_office',
            'attendance_id' => $attendance->id,
            'distance'      => round($distance, 2),
            'radius'        => (int)$office->radius,
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Gagal menyimpan absen: '.$e->getMessage()], 500);
    }
}


    public function confirmCheckInPhoto(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'photo'         => 'required|image|max:3072',
            'reason'        => 'nullable|string|max:500',
        ]);

        $attendance = Attendance::findOrFail($request->attendance_id);

        $path = $request->file('photo')->store('attendances/in', 'public'); // Folder in
        $attendance->check_in_photo_url = Storage::url($path); // Ganti photo_url
        if ($request->filled('reason')) {
            $attendance->check_in_reason = $request->reason; // Ganti reason
        }
        $attendance->save();

        return response()->json([
            'message' => 'Foto konfirmasi absen masuk berhasil dikirim.',
            'data'    => $attendance,
        ]);
    }

    // ==============================
    // ABSEN KELUAR (CHECK-OUT)
    // ==============================
public function checkOut(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'latitude'  => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $absen = Attendance::where('user_id', $user->id)
        ->whereNull('check_out')
        ->latest('check_in')
        ->first();

    if (!$absen) {
        return response()->json(['message' => 'Anda belum absen masuk hari ini.'], 400);
    }

    if ($absen->check_out) {
    return response()->json(['message' => 'Anda sudah absen keluar.'], 400);
    }


    $office = OfficeLocation::first();
    if (!$office) {
        return response()->json(['message' => 'Lokasi kantor belum diset.'], 500);
    }

    $shift = $absen->schedule->shift ?? null;
    $now = now();

    // 1. Batasi waktu Check-Out
    if ($shift) {
        if ($now->lt(Carbon::parse($shift->end_time)->subMinutes(30)) ||
            $now->gt(Carbon::parse($shift->end_time)->addHour())) {
            return response()->json(['message' => 'Waktu absen keluar sudah ditutup atau belum dimulai.'], 403);
        }
    }

    // 2. Hitung jarak lokasi user ke kantor
    $distance = $this->getDistanceInMeters(
        (float)$office->latitude,
        (float)$office->longitude,
        (float)$request->latitude,
        (float)$request->longitude
    );

    // 3. Kalau di luar area kantor, jangan langsung simpan
    if ($distance > (int)$office->radius) {
           return response()->json([
            'message'       => 'Anda berada di luar area kantor. Silakan upload foto konfirmasi untuk absen keluar.',
            'status'        => 'outside_office',
            'attendance_id' => $absen->id,
            'requires_photo' => true,
            'distance'      => round($distance, 2),
            'radius'        => (int)$office->radius,
        ], 200);
    }

    // 4. Kalau di dalam area kantor, baru simpan check-out
    $absen->update([
        'check_out'     => now(),
        'check_out_lat' => $request->latitude,
        'check_out_lng' => $request->longitude,
    ]);

    return response()->json([
        'message'       => 'Absen keluar berhasil. Anda berada di dalam area kantor.',
        'status'        => 'in_office',
        'attendance_id' => $absen->id,
        'distance'      => round($distance, 2),
        'radius'        => (int)$office->radius,
    ]);
}

    
    // ==============================
    // KONFIRMASI ABSEN KELUAR DENGAN FOTO (BARU)
    // ==============================
    public function confirmCheckOutPhoto(Request $request)
{
    $request->validate([
        'attendance_id' => 'required|exists:attendances,id',
        'photo'         => 'required|image|max:3072',
        'reason'        => 'nullable|string|max:500',
    ]);

    // Ambil data attendance dulu
    $attendance = Attendance::findOrFail($request->attendance_id);

    // Simpan foto ke folder public/storage/attendances/out
    $path = $request->file('photo')->store('attendances/out', 'public');
    $photoPath = Storage::url($path);

    // Update kolom yang sesuai di database
    $attendance->update([
        'photo_url' => $photoPath,
        'reason'    => $request->reason,
        'check_out' => now(), // sekalian update waktu checkout
    ]);

    return response()->json([
        'message' => 'Foto konfirmasi absen keluar berhasil dikirim.',
        'data'    => $attendance,
    ]);
}


    private function getDistanceInMeters($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371000;
        $φ1 = deg2rad($lat1);
        $φ2 = deg2rad($lat2);
        $Δφ = deg2rad($lat2 - $lat1);
        $Δλ = deg2rad($lon2 - $lon1);

        $a = sin($Δφ / 2) * sin($Δφ / 2) +
              cos($φ1) * cos($φ2) *
              sin($Δλ / 2) * sin($Δλ / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }
}
