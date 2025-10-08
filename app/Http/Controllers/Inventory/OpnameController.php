<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OpnameController extends Controller
{
    public function index(){
        return view('inventori.opname');
    }
}
