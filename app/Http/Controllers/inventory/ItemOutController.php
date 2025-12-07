<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemOutModel;
use App\Models\Inventory\ItemModel;
use Illuminate\Http\Request;

class ItemOutController extends Controller
{
    public function index()
    {
        return view('inventory.out.index', [
            'items' => ItemModel::all(),
            'outs'  => ItemOutModel::with('item')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        ItemOutModel::create([
            'item_id' => $request->item_id,
            'qty'     => $request->qty,
            'purpose' => $request->purpose,
            'used_by' => auth()->id(),
        ]);

        // kurangi stok
        ItemModel::find($request->item_id)
            ->decrement('stock', $request->qty);

        return back()->with('success', 'Barang keluar dicatat!');
    }
}
