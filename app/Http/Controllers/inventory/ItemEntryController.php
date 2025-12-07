<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ItemEntryModel;
use App\Models\Inventory\ItemModel;
use App\Models\Inventory\ItemRequestModel;

class ItemEntryController extends Controller
{
   public function index()
    {
        return view('inventory.entry.index', [
            'entries'  => ItemEntryModel::with(['item', 'supplier'])->latest()->get(),
            'requests' => ItemRequestModel::where('status','supplier_approved')->get()
        ]);
    }

    public function store(Request $request)
    {
        ItemEntryModel::create([
            'request_id'   => $request->request_id,
            'supplier_id'  => $request->supplier_id,
            'item_id'      => $request->item_id,
            'qty'          => $request->qty,
            'price_per_item'=> $request->price_per_item,
            'total_price'  => $request->qty * $request->price_per_item,
            'received_by'  => auth()->id(),
        ]);

        // update stok
        ItemModel::find($request->item_id)
            ->increment('stock', $request->qty);

        return back()->with('success', 'Barang masuk berhasil ditambahkan!');
    }
}

