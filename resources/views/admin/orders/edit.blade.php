@extends('layouts.admin')

@section('title', 'Edit Order')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">Edit Order</h1>
            <p class="mb-0 text-muted">Perbarui data order.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="order_code">Kode Order</label>
                        <input type="text" class="form-control" id="order_code" name="order_code"
                            value="{{ old('order_code', $order->order_code) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="customer_name">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                            value="{{ old('customer_name', $order->customer_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="customer_phone">No. Telepon</label>
                        <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                            value="{{ old('customer_phone', $order->customer_phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            @foreach (['pending', 'confirmed', 'paid', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $order->status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="payment_method">Metode Bayar</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">-</option>
                            @foreach (['cash', 'cashless'] as $method)
                                <option value="{{ $method }}"
                                    {{ old('payment_method', $order->payment_method) === $method ? 'selected' : '' }}>
                                    {{ ucfirst($method) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="payment_status">Status Bayar</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            @foreach (['unpaid', 'paid'] as $status)
                                <option value="{{ $status }}"
                                    {{ old('payment_status', $order->payment_status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="tax">Pajak</label>
                        <input type="number" class="form-control" id="tax" name="tax"
                            value="{{ old('tax', $order->tax) }}" min="0" step="0.01">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="notes">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
