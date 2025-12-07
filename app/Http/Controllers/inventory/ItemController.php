<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemModel;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('inventory.item.index', [
            'items' => ItemModel::all()
        ]);
    }

    public function store(Request $request)
    {
        ItemModel::create($request->all());
        return back()->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        ItemModel::find($id)->update($request->all());
        return back()->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        ItemModel::destroy($id);
        return back()->with('success', 'Barang berhasil dihapus!');
    }
}
