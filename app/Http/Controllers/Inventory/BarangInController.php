<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\BarangIn;
use App\Models\Inventory\Inventory;

class BarangInController extends Controller
{
    public function index()
    {
        $barangIns = BarangIn::all();
        return view('inventori.barang-in', compact('barangIns'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:1',
            'taggal' => 'required|date',
            'supplier' => 'nullable|string',
            'keterangan' => 'nullable|string', 
        ]);

        $masuk = BarangIn::create($data);

         dd($masuk); 

        $item = Inventory::find($data['item_id']);
        $item->stok += $data['jumlah'];
        $item->save();

        return redirect()->route('barang-in.index')->with('succes', 'Barang Masuk Berhasil Ditambahkan');
    }
}
