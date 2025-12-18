<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuVariant;
use Illuminate\Http\Request;

class MenuVariantController extends Controller
{
    public function index(Menu $menu)
    {
        $variants = $menu->variants()->latest()->get();

        return view('admin.menu-variants.index', compact('menu', 'variants'));
    }

    public function create(Menu $menu)
    {
        return view('admin.menu-variants.create', compact('menu'));
    }

    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isDefault = (bool) ($validated['is_default'] ?? false);
        if ($isDefault) {
            $menu->variants()->update(['is_default' => false]);
        }

        $menu->variants()->create([
            'name' => $validated['name'],
            'price' => $validated['price'] ?? 0,
            'is_default' => $isDefault,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('admin.menus.variants.index', $menu)
            ->with('success', 'Varian menu berhasil ditambahkan.');
    }

    public function edit(MenuVariant $variant)
    {
        $menu = $variant->menu;

        return view('admin.menu-variants.edit', compact('menu', 'variant'));
    }

    public function update(Request $request, MenuVariant $variant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isDefault = (bool) ($validated['is_default'] ?? false);
        if ($isDefault) {
            $variant->menu->variants()->where('id', '!=', $variant->id)
                ->update(['is_default' => false]);
        }

        $variant->update([
            'name' => $validated['name'],
            'price' => $validated['price'] ?? 0,
            'is_default' => $isDefault,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('admin.menus.variants.index', $variant->menu)
            ->with('success', 'Varian menu berhasil diperbarui.');
    }

    public function destroy(MenuVariant $variant)
    {
        $menu = $variant->menu;
        $variant->delete();

        return redirect()->route('admin.menus.variants.index', $menu)
            ->with('success', 'Varian menu berhasil dihapus.');
    }
}
