<?php

namespace App\Http\Controllers\teknisiControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SlipGaji;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;

class MySallaryController extends Controller
{
    public function mySalary()
    {
        $slips = SlipGaji::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('teknisiAbsen.slip', compact('slips'));
    }

    public function downloadPdf($id)
    {
        $slip = SlipGaji::with(['karyawan', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $karyawan = $slip->karyawan;

        $pdf = Pdf::loadView('teknisiAbsen.slip_pdf', [
            'slip' => $slip,
            'karyawan' => $karyawan
        ]);

        return $pdf->download('Slip_Gaji_' . $slip->periode . '.pdf');
    }


    public function terimaGaji($id)
    {
        $slip = SlipGaji::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Cegah klik dua kali
        if ($slip->is_received) {
            return back()->with('info', 'Gaji ini sudah pernah diterima.');
        }

        // Update slip
        $slip->update([
            'is_received' => true,
            'received_at' => now(),
        ]);

        // Buat transaksi pengeluaran gaji
        Transaction::create([
            'category_id'       => 8, // sesuaikan ID kategori kamu
            'description'       => 'Pembayaran gaji teknisi ' . $slip->karyawan->name,
            'amount'            => $slip->gaji_bersih,
            'transaction_type'  => 'expense',
            'transaction_date'  => now(),
        ]);

        return back()->with('success', 'Gaji berhasil dikonfirmasi dan dicatat sebagai pengeluaran.');
    }


}
