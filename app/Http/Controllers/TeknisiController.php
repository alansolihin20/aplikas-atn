<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SlipGaji;

class TeknisiController extends Controller
{
    public function teknisiDashboard()
    {
        return view('dashboard.teknisi');
    }

   


}