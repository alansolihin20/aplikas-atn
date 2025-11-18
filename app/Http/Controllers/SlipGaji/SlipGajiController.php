<?php

namespace App\Http\Controllers\SlipGaji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SlipGaji;
use App\Models\User;

class SlipGajiController extends Controller
{
     public function index()
    {
        $slips = SlipGaji::with('user')->latest()->get();
        $users = User::where('role', 'teknisi')->get();
        return view('adminGaji.index', compact('slips', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periode' => 'required|date',
            'gaji_pokok' => 'required|numeric',
            'insentif_harian' => 'required|numeric',
            'hari_kerja' => 'required|integer|min:0|max:31',
            'bpjs_tk' => 'nullable|numeric',
            'bpjs_kes' => 'nullable|numeric',
            'pinjaman' => 'nullable|numeric',
        ]);

        // Hitung total otomatis
        $gaji_bruto = $request->gaji_pokok + ($request->insentif_harian * $request->hari_kerja);
        $potongan = ($request->bpjs_tk ?? 0) + ($request->bpjs_kes ?? 0) + ($request->pinjaman ?? 0);
        $gaji_bersih = $gaji_bruto - $potongan;

        SlipGaji::create([
            'user_id' => $request->user_id,
            'periode' => $request->periode,
            'gaji_pokok' => $request->gaji_pokok,
            'insentif_harian' => $request->insentif_harian,
            'hari_kerja' => $request->hari_kerja,
            'bpjs_tk' => $request->bpjs_tk,
            'bpjs_kes' => $request->bpjs_kes,
            'pinjaman' => $request->pinjaman,
            'gaji_bruto' => $gaji_bruto,
            'gaji_bersih' => $gaji_bersih,
        ]);

        return back()->with('success', 'Slip gaji berhasil disimpan.');
    }
}
