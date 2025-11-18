<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemModel as Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        return view('inventory.item.index', compact('items'));
    }

    public function create()
    {
        return view('inventory.item.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kategori' => 'nullable',
            'stok' => 'required|numeric|min:0'
        ]);

        Item::create($request->all());

        return redirect()->route('item.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Item $item)
    {
        return view('inventory.item.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $item->update($request->all());
        return redirect()->route('item.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('item.index')->with('success', 'Barang berhasil dihapus');
    }
}
