<?php

namespace App\Http\Controllers\adminControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftTime extends Controller
{
    function indexx()
    {
        $shifts = Shift::all();

        return view('adminAbsensi.index', compact('shifts'));
    }

    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Shift::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->back()->with('success', 'Shift time berhasil ditambahkan.');
    }

    function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return redirect()->back()->with('success', 'Shift time berhasil dihapus.');
    }

}
