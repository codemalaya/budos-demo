@extends('layouts.admin')

@section('title', 'Order')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">Order</h1>
            <p class="mb-0 text-muted">Kelola pesanan pelanggan.</p>
        </div>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Tambah Order</a>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $order->order_code }}</div>
                                    <div class="text-muted small">{{ $order->created_at->format('d M Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order->customer_name ?: '-' }}</div>
                                    <div class="text-muted small">{{ $order->customer_phone ?: '-' }}</div>
                                </td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>{{ $order->payment_method ?: '-' }} / {{ $order->payment_status }}</td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="btn btn-sm btn-outline-primary">Detail</a>
                                    <a href="{{ route('admin.orders.edit', $order) }}"
                                        class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus order ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
