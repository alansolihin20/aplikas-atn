<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ItemOutModel as ItemOut;
use App\Models\Inventory\ItemModel as Item;


class ItemOutController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'purpose' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $data['used_by'] = auth()->id();

        $item = Item::find($data['item_id']);

        if ($data['qty'] > $item->stock) {
            return back()->with('error', 'Stok tidak cukup.');
        }

        ItemOut::create($data);

        // Kurangi stok
        $item->decrement('stock', $data['qty']);

        return back()->with('success', 'Barang keluar berhasil dicatat.');
    }
}
