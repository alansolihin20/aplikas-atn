<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ItemEntryModel as ItemEntry;
use App\Models\Inventory\ItemModel as Item;
use App\Models\Inventory\ItemRequestModel as ItemRequest;

class ItemEntryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'price_per_unit' => 'nullable|integer',
            'request_id' => 'nullable|exists:item_requests,id',
        ]);

        $data['received_by'] = auth()->id();
        $data['total_price'] = $data['price_per_unit'] * $data['qty'];

        ItemEntry::create($data);

        // Update stok
        $item = Item::find($data['item_id']);
        $item->increment('stock', $data['qty']);

        // Tandai request selesai
        if ($data['request_id']) {
            ItemRequest::find($data['request_id'])->update([
                'status' => 'received'
            ]);
        }

        return back()->with('success', 'Barang masuk berhasil ditambahkan.');
    }
}

