<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class DashboardController extends Controller
{

    // Chart data Pemasukan dan Pengeluaran untuk Superadmin
    public function superadminDashboard()
    {
        $tahun = now()->format('Y');

        $monthlyIncome = [];
        $monthlyExpense = [];

        for ($i = 1; $i <= 12; $i++) {
            $income = Transaction::where('transaction_type', 'income')
                ->whereYear('transaction_date', $tahun)
                ->whereMonth('transaction_date', $i)
                ->sum('amount');

            $expense = Transaction::where('transaction_type', 'expense')
                ->whereYear('transaction_date', $tahun)
                ->whereMonth('transaction_date', $i)
                ->sum('amount');

            $monthlyIncome[] = $income;
            $monthlyExpense[] = $expense;
        }

        return view('dashboard.superadmin', compact('monthlyIncome', 'monthlyExpense'));
    }
}
