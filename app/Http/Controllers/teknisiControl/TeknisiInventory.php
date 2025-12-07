<?php

namespace App\Http\Controllers\teknisiControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ItemModel;
use App\Models\Inventory\ItemRequestModel;
use App\Models\Inventory\ItemEntryModel;
use App\Models\Inventory\ItemOutModel;

class TeknisiInventory extends Controller
{
       public function requestIndex()
    {
        return view('teknisi.inventory.request.index', [
            'items' => ItemModel::all(),
            'requests' => ItemRequestModel::with('item')
                            ->where('user_id', auth()->id())
                            ->latest()
                            ->get(),
        ]);
    }

    public function requestStore(Request $r)
    {
        $r->validate([
            'item_id' => 'required',
            'qty'     => 'required|numeric|min:1',
            'note'    => 'nullable'
        ]);

        ItemRequestModel::create([
            'user_id' => auth()->id(),
            'item_id' => $r->item_id,
            'qty'     => $r->qty,
            'note'    => $r->note,
            'status'  => 'pending'
        ]);

        return back()->with('success', 'Request barang dikirim!');
    }

    // =============================
    //  BARANG MASUK (READ ONLY)
    // =============================
    public function entryIndex()
    {
        return view('teknisi.inventory.entry.index', [
            'entries' => ItemEntryModel::with(['item','supplier'])
                        ->latest()
                        ->get()
        ]);
    }

    // =============================
    //  BARANG KELUAR
    // =============================
    public function outIndex()
    {
        return view('teknisi.inventory.out.index', [
            'items' => ItemModel::all(),
            'outs' => ItemOutModel::with(['item','user'])
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->get()
        ]);
    }

    public function outStore(Request $r)
    {
        $r->validate([
            'item_id' => 'required',
            'qty'     => 'required|numeric|min:1',
            'reason'  => 'required'
        ]);

        $item = ItemModel::findOrFail($r->item_id);

        if ($item->stock < $r->qty) {
            return back()->with('error', 'Stok tidak cukup!');
        }

        // kurangi stok
        $item->decrement('stock', $r->qty);

        ItemOutModel::create([
            'item_id' => $r->item_id,
            'qty'     => $r->qty,
            'reason'  => $r->reason,
            'used_by' => auth()->id(),
        ]);

        return back()->with('success', 'Barang keluar dicatat!');
    }
}
