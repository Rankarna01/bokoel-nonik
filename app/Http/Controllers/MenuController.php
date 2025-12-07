<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Tampilkan semua menu
    public function index()
    {
        $menus = Menu::with('category')->get();
        return view('menus.index', compact('menus'));
    }

    // Tampilkan form tambah menu
    public function create()
    {
        $categories = MenuCategory::all();
        return view('menus.create', compact('categories'));
    }

    // Simpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:menu_categories,id',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->only('name', 'description', 'price', 'category_id', 'is_available');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('menus', 'public');
        }

        $data['is_available'] = $request->has('is_available');

        Menu::create($data);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    // Tampilkan form edit menu
    public function edit(Menu $menu)
    {
        $categories = MenuCategory::all();
        return view('menus.edit', compact('menu', 'categories'));
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:menu_categories,id',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->only('name', 'description', 'price', 'category_id', 'is_available');

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($menu->photo) {
                Storage::disk('public')->delete($menu->photo);
            }

            $data['photo'] = $request->file('photo')->store('menus', 'public');
        }

        $data['is_available'] = $request->has('is_available');

        $menu->update($data);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    // Hapus menu
    public function destroy(Menu $menu)
    {
        if ($menu->photo) {
            Storage::disk('public')->delete($menu->photo);
        }

        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
