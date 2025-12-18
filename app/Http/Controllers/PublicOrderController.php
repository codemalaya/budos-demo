<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuVariant;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicOrderController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['variants' => fn ($query) => $query->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $cart = collect(session('cart', []));
        $menuMap = $menus->keyBy('id');
        $cartItems = $cart->map(function ($item, $key) use ($menuMap) {
            $menu = $menuMap->get($item['menu_id']);
            $variant = null;
            if (!empty($item['menu_variant_id']) && $menu) {
                $variant = $menu->variants->firstWhere('id', $item['menu_variant_id']);
            }

            $price = $variant?->price ?? $menu?->base_price ?? 0;

            $domKey = str_replace(':', '_', $key);

            return [
                'key' => $key,
                'dom_key' => $domKey,
                'menu' => $menu,
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'price' => $price,
                'subtotal' => $price * $item['quantity'],
            ];
        })->values();

        $cartTotal = $cartItems->sum('subtotal');

        return view('public.index', compact('menus', 'cartItems', 'cartTotal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'menu_variant_id' => ['nullable', 'exists:menu_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
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

        $price = $variant?->price ?? $menu->base_price;
        $quantity = (int) $validated['quantity'];
        $subtotal = $price * $quantity;

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

        $order->details()->create([
            'menu_id' => $menu->id,
            'menu_variant_id' => $variant?->id,
            'menu_name' => $menu->name,
            'variant_name' => $variant?->name,
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('landing')
            ->with('success', 'Pesanan dibuat. Tunjukkan kode ke kasir untuk konfirmasi.')
            ->with('order_code', $order->order_code);
    }

    private function generateOrderCode(): string
    {
        $prefix = now()->format('Ymd');
        $random = Str::upper(Str::random(4));

        return 'ORD-' . $prefix . '-' . $random;
    }
}
