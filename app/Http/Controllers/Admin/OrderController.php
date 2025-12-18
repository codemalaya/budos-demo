<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_code' => ['nullable', 'string', 'max:255'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['required', 'string', 'max:50'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $orderCode = $validated['order_code'] ?: $this->generateOrderCode();

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'] ?? null,
            'payment_status' => $validated['payment_status'],
            'tax' => $validated['tax'] ?? 0,
            'subtotal' => 0,
            'total' => 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        $order->update([
            'total' => $order->subtotal + $order->tax,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order berhasil dibuat.');
    }

    public function show(Order $order)
    {
        $order->load('details');
        $menus = Menu::with('variants')->where('is_active', true)->get();

        return view('admin.orders.show', compact('order', 'menus'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_code' => ['required', 'string', 'max:255'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['required', 'string', 'max:50'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $order->update([
            'order_code' => $validated['order_code'],
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'] ?? null,
            'payment_status' => $validated['payment_status'],
            'tax' => $validated['tax'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'total' => $order->subtotal + ($validated['tax'] ?? 0),
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }

    private function generateOrderCode(): string
    {
        $prefix = now()->format('Ymd');
        $random = Str::upper(Str::random(4));

        return 'ORD-' . $prefix . '-' . $random;
    }
}
