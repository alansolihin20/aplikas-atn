<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\ItemModel;
use App\Models\Inventory\SupplierModel;
use App\Models\Inventory\ItemRequestModel;
use Telegram\Bot\Facades\Telegram;


class ItemRequestController extends Controller
{
    public function index()
    {
        return view('inventory.request.index', [
            'requests' => ItemRequestModel::with(['item', 'user'])->latest()->get(),
            'items'    => ItemModel::all(),
            'suppliers'=> SupplierModel::all()
        ]);
    }

    public function store(Request $request)
    {
        // Pastikan ada array 'items' yang dikirim
        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id', // Ganti 'item_models' dengan nama tabel barang Anda
            'items.*.qty' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string',
        ]);

        $requestsCreated = 0;

        foreach ($request->items as $itemData) {
            ItemRequestModel::create([
                'user_id'  => auth()->id(),
                'item_id'  => $itemData['item_id'],
                'qty'      => $itemData['qty'],
                'note'     => $itemData['note'] ?? null, // Gunakan null coalescing jika note opsional
                'status'   => 'pending',
            ]);
            $requestsCreated++;
        }

        if ($requestsCreated > 0) {
            return back()->with('success', "{$requestsCreated} Permintaan barang berhasil dibuat!");
        }

        return back()->with('error', 'Tidak ada permintaan barang yang dibuat.');
    }

    public function sendToSupplier($id)
    {
        $req = ItemRequestModel::with(['item'])->findOrFail($id);

        // Kirim Telegram ke supplier
        $text = "📦 Permintaan Barang Baru\n".
                "Item: {$req->item->name}\n".
                "Qty : {$req->qty}\n".
                "Note: {$req->note}";

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_SUPPLIER_CHAT_ID'),
            'text'    => $text
        ]);

        $req->update(['status' => 'sent_to_supplier']);

        return back()->with('success', 'Permintaan dikirim ke supplier!');
    }

    public function approveFromSupplier($id)
    {
        ItemRequestModel::find($id)->update([
            'status' => 'supplier_approved'
        ]);

        return back()->with('success', 'Supplier telah menyetujui!');
    }
}
