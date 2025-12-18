<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuVariant;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicCartController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'menu_variant_id' => ['nullable', 'exists:menu_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $variantId = $validated['menu_variant_id'] ?? null;
        if ($variantId) {
            $variant = MenuVariant::findOrFail($variantId);
            if ($variant->menu_id !== $menu->id) {
                return back()->withErrors(['menu_variant_id' => 'Varian tidak sesuai dengan menu.']);
            }
        }

        $key = $this->makeKey($menu->id, $variantId);
        $cart = session('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += (int) $validated['quantity'];
        } else {
            $cart[$key] = [
                'menu_id' => $menu->id,
                'menu_variant_id' => $variantId,
                'quantity' => (int) $validated['quantity'],
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('landing')
            ->with('success', 'Menu ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $key)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session('cart', []);
        if (!isset($cart[$key])) {
            return redirect()->route('landing')->withErrors(['cart' => 'Item tidak ditemukan.']);
        }

        $cart[$key]['quantity'] = (int) $validated['quantity'];
        session(['cart' => $cart]);

        return redirect()->route('landing')
            ->with('success', 'Keranjang diperbarui.');
    }

    public function remove(string $key)
    {
        $cart = session('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session(['cart' => $cart]);
        }

        return redirect()->route('landing')
            ->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('landing')
            ->with('success', 'Keranjang dikosongkan.');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('landing')
                ->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $menuIds = collect($cart)->pluck('menu_id')->unique()->values();
        $menus = Menu::with(['variants' => fn ($query) => $query->where('is_active', true)])
            ->whereIn('id', $menuIds)
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        $detailsPayload = [];

        foreach ($cart as $item) {
            $menu = $menus->get($item['menu_id']);
            if (!$menu) {
                continue;
            }

            $variant = null;
            if (!empty($item['menu_variant_id'])) {
                $variant = $menu->variants->firstWhere('id', $item['menu_variant_id']);
            }

            $price = $variant?->price ?? $menu->base_price;
            $quantity = (int) $item['quantity'];
            $lineSubtotal = $price * $quantity;
            $subtotal += $lineSubtotal;

            $detailsPayload[] = [
                'menu_id' => $menu->id,
                'menu_variant_id' => $variant?->id,
                'menu_name' => $menu->name,
                'variant_name' => $variant?->name,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
                'notes' => null,
            ];
        }

        if (empty($detailsPayload)) {
            return redirect()->route('landing')
                ->withErrors(['cart' => 'Menu di keranjang tidak valid.']);
        }

        $order = Order::create([
            'order_code' => $this->generateOrderCode(),
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'status' => 'pending',
            'payment_method' => null,
            'payment_status' => 'unpaid',
            'subtotal' => $subtotal,
            'tax' => 0,
            'total' => $subtotal,
            'notes' => $validated['notes'] ?? null,
        ]);

        $order->details()->createMany($detailsPayload);

        session()->forget('cart');

        return redirect()->route('landing')
            ->with('success', 'Pesanan dibuat. Tunjukkan kode ke kasir untuk konfirmasi.')
            ->with('order_code', $order->order_code);
    }

    private function makeKey(int $menuId, ?int $variantId): string
    {
        return $menuId . '-' . ($variantId ?: 0);
    }

    private function generateOrderCode(): string
    {
        $prefix = now()->format('Ymd');
        $random = Str::upper(Str::random(4));

        return 'ORD-' . $prefix . '-' . $random;
    }
}
