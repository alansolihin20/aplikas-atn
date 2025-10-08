<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardRedirectController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'superadmin':
                return redirect('/dashboard/superadmin');
            case 'admin':
                return redirect('/dashboard/admin');
            case 'teknisi':
                return redirect('/dashboard/teknisi');
            default:
             abort(404, 'Dashboard not found for this user role.');
        }
    }
}
