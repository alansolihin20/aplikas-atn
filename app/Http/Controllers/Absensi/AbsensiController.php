<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiTeknisi;
use App\Models\KaryawanModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user()->karyawan; // asumsi relasi user -> karyawan
        $today = Carbon::today();

        $absenHariIni = AbsensiTeknisi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayat = AbsensiTeknisi::where('karyawan_id', $karyawan->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('absensi.index', compact('absenHariIni', 'riwayat'));
    }

    public function absenMasuk(Request $request)
    {
        $karyawan = Auth::user()->karyawan;
        $today = Carbon::today();

        $sudahAbsen = AbsensiTeknisi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Kamu sudah absen hari ini.');
        }

        AbsensiTeknisi::create([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Absen masuk berhasil!');
    }

    public function absenPulang()
    {
        $karyawan = Auth::user()->karyawan;
        $today = Carbon::today();

        $absen = AbsensiTeknisi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absen) {
            return redirect()->back()->with('error', 'Belum absen masuk.');
        }

        if ($absen->jam_pulang) {
            return redirect()->back()->with('error', 'Kamu sudah absen pulang.');
        }

        $absen->update([
            'jam_pulang' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Absen pulang berhasil!');
    }
}
