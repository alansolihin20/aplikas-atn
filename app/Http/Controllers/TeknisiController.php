<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function teknisiDashboard()
    {
        return view('dashboard.teknisi');
    }
}