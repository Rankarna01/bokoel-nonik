<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::all();
        return view('menu_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('menu_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:menu_categories,name',
        ]);

        MenuCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('menu-categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(MenuCategory $menu_category)
    {
        return view('menu_categories.edit', compact('menu_category'));
    }

    public function update(Request $request, MenuCategory $menu_category)
    {
        $request->validate([
            'name' => 'required|string|unique:menu_categories,name,' . $menu_category->id,
        ]);

        $menu_category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('menu-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(MenuCategory $menu_category)
    {
        $menu_category->delete();
        return redirect()->route('menu-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
