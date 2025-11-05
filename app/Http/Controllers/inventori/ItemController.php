<?php

namespace App\Http\Controllers\inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Items;
use App\Models\Inventory\Supplier;


class ItemController extends Controller
{
    public function index()
    {
        $items = Items::with('supplier')->get();
        $suppliers = Supplier::all();
        return view('adminInven.index', compact('items', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('adminInven.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate(([
            'nama_item' => 'required|string|max:255',
            'stok' => 'required|integer',
        ]));

        Items::create($request->all());
        return redirect()->route('adminInven.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = Items::findOrFail($id);

        $request->validate(([
            'nama_item' => 'required|string|max:255',
            'stok' => 'required|integer',
        ]));

        $item->update($request->all());
        return redirect()->route('adminInven.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Items::findOrFail($id);
        $item->delete();
        return redirect()->route('adminInven.index')->with('success', 'Item berhasil dihapus.');
    }
}
