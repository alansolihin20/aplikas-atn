<?php

namespace App\Http\Controllers\adminControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['user', 'shift', 'schedule'])
            ->orderByDesc('check_in');

        // Filter opsional
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('check_in', $request->date);
        }

        $attendances = $query->paginate(15);
        $users = User::where('role', 'teknisi')->get();

        return view('adminAbsensi.riwayat', compact('attendances', 'users'));
    }
}
