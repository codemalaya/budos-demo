@extends('layouts.admin')

@section('title', 'Detail Order')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">Order {{ $order->order_code }}</h1>
            <p class="mb-0 text-muted">Kelola detail pesanan.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-outline-warning">Edit Order</a>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">
                        <div class="text-muted small">Pelanggan</div>
                        <div class="fw-semibold">{{ $order->customer_name ?: '-' }}</div>
                        <div class="text-muted small">{{ $order->customer_phone ?: '-' }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Status</div>
                        <div>{{ ucfirst($order->status) }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Pembayaran</div>
                        <div>{{ $order->payment_method ?: '-' }} / {{ $order->payment_status }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Subtotal</div>
                        <div>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Pajak</div>
                        <div>Rp {{ number_format($order->tax, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted small">Total</div>
                        <div class="fw-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Tambah Item</h5>
                    <form method="POST" action="{{ route('admin.orders.details.store', $order) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="menu_id">Menu</label>
                                <select class="form-select" id="menu_id" name="menu_id" required>
                                    <option value="">Pilih menu</option>
                                    @foreach ($menus as $menu)
                                        <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="menu_variant_id">Varian</label>
                                <select class="form-select" id="menu_variant_id" name="menu_variant_id">
                                    <option value="">Tanpa varian</option>
                                    @foreach ($menus as $menu)
                                        @foreach ($menu->variants as $variant)
                                            <option value="{{ $variant->id }}" data-menu="{{ $menu->id }}">
                                                {{ $menu->name }} - {{ $variant->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="quantity">Jumlah</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1"
                                    min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="price">Harga (opsional)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="notes">Catatan</label>
                                <input type="text" class="form-control" id="notes" name="notes">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Detail Item</h5>
                    <div class="table-responsive">
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
                                @forelse ($order->details as $detail)
                                    <tr>
                                        <td>{{ $detail->menu_name }}</td>
                                        <td>{{ $detail->variant_name ?: '-' }}</td>
                                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEditDetail{{ $detail->id }}">Edit</button>
                                            <form action="{{ route('admin.details.destroy', $detail) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus item ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada item.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($order->details as $detail)
        <div class="modal fade" id="modalEditDetail{{ $detail->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.details.update', $detail) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="menu_id_{{ $detail->id }}">Menu</label>
                                <select class="form-select" id="menu_id_{{ $detail->id }}" name="menu_id" required>
                                    @foreach ($menus as $menu)
                                        <option value="{{ $menu->id }}"
                                            {{ $detail->menu_id === $menu->id ? 'selected' : '' }}>
                                            {{ $menu->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="menu_variant_id_{{ $detail->id }}">Varian</label>
                                <select class="form-select" id="menu_variant_id_{{ $detail->id }}"
                                    name="menu_variant_id">
                                    <option value="">Tanpa varian</option>
                                    @foreach ($menus as $menu)
                                        @foreach ($menu->variants as $variant)
                                            <option value="{{ $variant->id }}" data-menu="{{ $menu->id }}"
                                                {{ $detail->menu_variant_id === $variant->id ? 'selected' : '' }}>
                                                {{ $menu->name }} - {{ $variant->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="price_{{ $detail->id }}">Harga</label>
                                <input type="number" class="form-control" id="price_{{ $detail->id }}" name="price"
                                    value="{{ $detail->price }}" min="0" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="quantity_{{ $detail->id }}">Jumlah</label>
                                <input type="number" class="form-control" id="quantity_{{ $detail->id }}"
                                    name="quantity" value="{{ $detail->quantity }}" min="1" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="notes_{{ $detail->id }}">Catatan</label>
                                <input type="text" class="form-control" id="notes_{{ $detail->id }}" name="notes"
                                    value="{{ $detail->notes }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @push('script')
        <script>
            const menuSelect = document.getElementById('menu_id');
            const variantSelect = document.getElementById('menu_variant_id');

            const filterVariants = (menuId, selectEl) => {
                const options = Array.from(selectEl.options);
                options.forEach((option) => {
                    if (!option.dataset.menu) return;
                    option.hidden = option.dataset.menu !== menuId;
                });
                if (selectEl.selectedOptions.length && selectEl.selectedOptions[0].hidden) {
                    selectEl.value = '';
                }
            };

            if (menuSelect && variantSelect) {
                menuSelect.addEventListener('change', () => {
                    filterVariants(menuSelect.value, variantSelect);
                });
            }
        </script>
    @endpush
@endsection
