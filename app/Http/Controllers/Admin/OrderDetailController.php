<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuVariant;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'menu_variant_id' => ['nullable', 'exists:menu_variants,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $variant = null;
        if (!empty($validated['menu_variant_id'])) {
            $variant = MenuVariant::findOrFail($validated['menu_variant_id']);
            if ($variant->menu_id !== $menu->id) {
                return back()->withErrors(['menu_variant_id' => 'Varian tidak sesuai dengan menu.']);
            }
        }

        $price = $validated['price']
            ?? ($variant?->price ?? $menu->base_price);

        $order->details()->create([
            'menu_id' => $menu->id,
            'menu_variant_id' => $variant?->id,
            'menu_name' => $menu->name,
            'variant_name' => $variant?->name,
            'price' => $price,
            'quantity' => $validated['quantity'],
            'subtotal' => $price * $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->syncOrderTotals($order);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Detail order berhasil ditambahkan.');
    }

    public function update(Request $request, OrderDetail $detail)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'menu_variant_id' => ['nullable', 'exists:menu_variants,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $variant = null;
        if (!empty($validated['menu_variant_id'])) {
            $variant = MenuVariant::findOrFail($validated['menu_variant_id']);
            if ($variant->menu_id !== $menu->id) {
                return back()->withErrors(['menu_variant_id' => 'Varian tidak sesuai dengan menu.']);
            }
        }

        $price = $validated['price']
            ?? ($variant?->price ?? $menu->base_price);

        $detail->update([
            'menu_id' => $menu->id,
            'menu_variant_id' => $variant?->id,
            'menu_name' => $menu->name,
            'variant_name' => $variant?->name,
            'price' => $price,
            'quantity' => $validated['quantity'],
            'subtotal' => $price * $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->syncOrderTotals($detail->order);

        return redirect()->route('admin.orders.show', $detail->order)
            ->with('success', 'Detail order berhasil diperbarui.');
    }

    public function destroy(OrderDetail $detail)
    {
        $order = $detail->order;
        $detail->delete();

        $this->syncOrderTotals($order);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Detail order berhasil dihapus.');
    }

    private function syncOrderTotals(Order $order): void
    {
        $subtotal = $order->details()->sum('subtotal');

        $order->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $order->tax,
        ]);
    }
}
