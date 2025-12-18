@extends('layouts.admin')

@section('title', 'Varian Menu')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">Varian Menu</h1>
            <p class="mb-0 text-muted">Kelola varian untuk {{ $menu->name }}.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <a href="{{ route('admin.menus.variants.create', $menu) }}" class="btn btn-primary">Tambah Varian</a>
        </div>
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
                            <th>Nama Varian</th>
                            <th>Harga</th>
                            <th>Default</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($variants as $variant)
                            <tr>
                                <td>{{ $variant->name }}</td>
                                <td>Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($variant->is_default)
                                        <span class="badge bg-primary">Default</span>
                                    @else
                                        <span class="badge bg-light text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($variant->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.variants.edit', $variant) }}"
                                        class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="{{ route('admin.variants.destroy', $variant) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus varian ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada varian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
