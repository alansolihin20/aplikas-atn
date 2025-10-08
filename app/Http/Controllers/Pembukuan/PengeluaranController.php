<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction; // Assuming you have a Transaction model
use App\Models\CategoryTransaction; // Assuming you have a CategoryTransaction model

class PengeluaranController extends Controller
{
    public function index()
    {

        $bulan = request('bulan', now()->format('m'));
        $tahun = request('tahun', now()->format('Y'));

        $transactions = Transaction::where('transaction_type', 'expense')
            ->whereMonth('transaction_date', $bulan)
            ->whereYear('transaction_date', $tahun)
            ->orderBy('transaction_date', 'desc')
            ->get();

        $categories = CategoryTransaction::where('type', 'expense')->get();

        return view('pembukuan.pengeluaran', compact('transactions', 'categories', 'bulan', 'tahun'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);

        Transaction::create([
            'category_id' => $request->category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'transaction_type' => 'expense',
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();


        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'category_id' => $request->category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }
}