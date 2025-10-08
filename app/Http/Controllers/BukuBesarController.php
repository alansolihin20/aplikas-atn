<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Assuming you have a Transaction model

class BukuBesarController extends Controller
{
    public function index(Request $request)
{
    $bulan = $request->input('bulan', now()->format('m'));
    $tahun = $request->input('tahun', now()->format('Y'));



    $saldo_awal = Transaction::where('transaction_date', '<', "{$tahun}-{$bulan}-01")
       ->selectRaw("
                SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) -
                SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END)
                as saldo
            ")
        ->value('saldo');

    // Fetch all transactions of type 'income' and 'expense'
    $transactions = Transaction::whereIn('transaction_type', ['income', 'expense'])
        ->whereMonth('transaction_date', $bulan)
        ->whereYear('transaction_date', $tahun)
        ->orderBy('transaction_date', 'asc')
        ->get();


    // Hitung total pemasukan dan pengeluaran
    $total_income = $transactions->where('transaction_type', 'income')->sum('amount');
    $total_expense = $transactions->where('transaction_type', 'expense')->sum('amount');

    // Hitung saldo akhir
    $saldo_akhir = $saldo_awal + $total_income - $total_expense;

    return view('pembukuan.bukubesar', compact('transactions','bulan', 'tahun','saldo_awal', 'total_income', 'total_expense', 'saldo_akhir'));     
}




}
