<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryTransaction;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CategoryTransaction::all();
        return view('pembukuan.category', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
        ]);

        CategoryTransaction::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
        ]);

        $category = CategoryTransaction::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = CategoryTransaction::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
