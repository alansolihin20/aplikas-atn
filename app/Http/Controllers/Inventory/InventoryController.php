<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $item = Inventory::all();
        return view('inventori.inventori', compact('item'));
    }
}
