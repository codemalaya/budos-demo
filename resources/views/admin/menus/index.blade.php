@extends('layouts.admin')

@section('title', 'Menu')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">Menu</h1>
            <p class="mb-0 text-muted">Kelola daftar menu restoran.</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Tambah Menu</a>
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
                            <th>Nama</th>
                            <th>Harga Dasar</th>
                            <th>Status</th>
                            <th>Varian</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $menu)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $menu->name }}</div>
                                    <div class="text-muted small">{{ $menu->slug }}</div>
                                </td>
                                <td>Rp {{ number_format($menu->base_price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($menu->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $menu->variants_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.menus.show', $menu) }}"
                                        class="btn btn-sm btn-outline-primary">Varian</a>
                                    <a href="{{ route('admin.menus.edit', $menu) }}"
                                        class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus menu ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada menu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
