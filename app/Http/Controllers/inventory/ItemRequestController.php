<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Items;
use App\Models\Inventory\Supplier;
use App\Models\Inventory\ItemRequestModel as ItemRequest;


class ItemRequestController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'nullable|exists:items,id',
            'item_name_temp' => 'nullable|string',
            'qty' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        ItemRequest::create($data);

        return back()->with('success', 'Request barang berhasil dibuat.');
    }

    public function approve($id)
    {
        $req = ItemRequest::findOrFail($id);
        $req->update(['status' => 'approved']);

        return back()->with('success', 'Request disetujui.');
    }

    public function reject($id)
    {
        $req = ItemRequest::findOrFail($id);
        $req->update(['status' => 'rejected']);

        return back()->with('success', 'Request ditolak.');
    }

    public function order($id)
    {
        $req = ItemRequest::findOrFail($id);
        $req->update(['status' => 'ordered']);

        // TODO: Kirim Telegram ke supplier

        return back()->with('success', 'Berhasil melakukan order ke supplier.');
    }
}
