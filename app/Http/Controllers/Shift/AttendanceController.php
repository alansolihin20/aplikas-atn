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
        $user = Auth::user();
        $today = Carbon::today();

        // Ganti created_at → check_in
        $absenHariIni = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $today)
            ->first();

        $riwayat = Attendance::where('user_id', $user->id)
            ->orderBy('check_in', 'desc') // ganti created_at → check_in
            ->take(10)
            ->get();

        return view('absensi.index', compact('absenHariIni', 'riwayat'));
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
            ->whereDate('check_in', $today) // Ganti created_at → check_in
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

        $attendance = Attendance::create([
            'user_id'      => $user->id,
            'schedule_id'  => 1,
            'check_in'     => now(),
            'check_in_lat' => $request->latitude,
            'check_in_lng' => $request->longitude,
        ]);

        if ($distance <= (int)$office->radius) {
            return response()->json([
                'message'       => 'Absen masuk berhasil. Anda berada di dalam area kantor.',
                'status'        => 'in_office',
                'attendance_id' => $attendance->id,
                'distance'      => round($distance, 2),
                'radius'        => (int)$office->radius,
            ]);
        }

        return response()->json([
            'message'       => 'Absen masuk tercatat, tapi Anda di luar area kantor. Silakan upload foto bukti.',
            'status'        => 'outside_office',
            'attendance_id' => $attendance->id,
            'distance'      => round($distance, 2),
            'radius'        => (int)$office->radius,
        ], 200);
    }

    public function confirmCheckInPhoto(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'photo'         => 'required|image|max:3072',
            'reason'        => 'nullable|string|max:500',
        ]);

        $attendance = Attendance::findOrFail($request->attendance_id);

        $path = $request->file('photo')->store('attendances', 'public');
        $attendance->photo_url = Storage::url($path);
        if ($request->filled('reason')) {
            $attendance->reason = $request->reason;
        }
        $attendance->save();

        return response()->json([
            'message' => 'Foto konfirmasi berhasil dikirim.',
            'data'    => $attendance,
        ]);
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $absen = Attendance::where('user_id', $user->id)
            ->whereNull('check_out')
            ->latest('check_in') // tambahkan kolom pembanding agar konsisten
            ->first();

        if (!$absen) {
            return response()->json(['message' => 'Anda belum absen masuk hari ini.'], 400);
        }

        $absen->update([
            'check_out'     => now(),
            'check_out_lat' => $request->latitude,
            'check_out_lng' => $request->longitude,
        ]);

        return response()->json(['message' => 'Absen keluar berhasil!', 'data' => $absen]);
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
