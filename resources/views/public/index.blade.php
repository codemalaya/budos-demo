@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    @php
        $menus = $menus ?? collect();
    @endphp

    <section class="py-5">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    <div>{{ session('success') }}</div>
                    @if (session('order_code'))
                        <div class="fw-semibold">Kode Pesanan: {{ session('order_code') }}</div>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h2 class="mb-2">Keranjang</h2>
                    <p class="text-muted mb-0">Tambah beberapa menu, lalu checkout untuk dapatkan kode pesanan.</p>
                </div>
                @if (!$cartItems->isEmpty())
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Kosongkan Keranjang</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="card mb-5">
                <div class="card-body">
                    @if ($cartItems->isEmpty())
                        <div class="text-muted">Keranjang masih kosong.</div>
                    @else
                        <div class="table-responsive mb-3">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>Varian</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr>
                                            <td>{{ $item['menu']?->name ?? '-' }}</td>
                                            <td>{{ $item['variant']?->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td style="max-width: 130px;">
                                                <input type="number" class="form-control form-control-sm"
                                                    name="quantity" min="1" value="{{ $item['quantity'] }}"
                                                    form="updateCart{{ $item['dom_key'] }}">
                                            </td>
                                            <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <form id="updateCart{{ $item['dom_key'] }}" method="POST"
                                                    action="{{ route('cart.update', $item['key']) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        Update
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('cart.remove', $item['key']) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label" for="customer_name">Nama (opsional)</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    form="checkoutForm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="customer_phone">No. Telepon (opsional)</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                                    form="checkoutForm">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="notes">Catatan (opsional)</label>
                                <input type="text" class="form-control" id="notes" name="notes" form="checkoutForm">
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Total</div>
                                <div class="h5 mb-0">Rp {{ number_format($cartTotal ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <form id="checkoutForm" method="POST" action="{{ route('orders.store.public') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Checkout</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h2 class="mb-2">Pesan Menu</h2>
                    <p class="text-muted mb-0">Pilih menu favorit, lalu tambahkan ke keranjang.</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($menus as $menu)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            @if ($menu->image_path)
                                <img src="{{ $menu->image_path }}" class="card-img-top" alt="{{ $menu->name }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $menu->name }}</h5>
                                <p class="text-muted small mb-3">{{ $menu->description ?: 'Menu favorit pelanggan.' }}</p>
                                <div class="mb-3">
                                    <div class="text-muted small">Harga mulai</div>
                                    <div class="fw-semibold">Rp {{ number_format($menu->base_price, 0, ',', '.') }}</div>
                                </div>
                                <form method="POST" action="{{ route('cart.add') }}" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <div class="mb-2">
                                        <label class="form-label small" for="variant_{{ $menu->id }}">Varian</label>
                                        <select class="form-select" id="variant_{{ $menu->id }}" name="menu_variant_id">
                                            <option value="">Tanpa Varian</option>
                                            @foreach ($menu->variants as $variant)
                                                <option value="{{ $variant->id }}">
                                                    {{ $variant->name }} - Rp {{ number_format($variant->price, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small" for="qty_{{ $menu->id }}">Jumlah</label>
                                        <input type="number" class="form-control" id="qty_{{ $menu->id }}" name="quantity"
                                            value="1" min="1">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Tambah ke Keranjang</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-muted">Menu belum tersedia.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @include('partials.web-footer')
@endsection
