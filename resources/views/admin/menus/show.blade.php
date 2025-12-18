@extends('layouts.admin')

@section('title', 'Detail Menu')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">{{ $menu->name }}</h1>
            <p class="mb-0 text-muted">Kelola varian menu di sini.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahVarian">
                Tambah Varian
            </button>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small">Slug</div>
                    <div class="fw-semibold">{{ $menu->slug }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Harga Dasar</div>
                    <div class="fw-semibold">Rp {{ number_format($menu->base_price, 0, ',', '.') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Status</div>
                    <div>
                        @if ($menu->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Deskripsi</div>
                    <div>{{ $menu->description ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

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
                        @forelse ($menu->variants as $variant)
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
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditVarian{{ $variant->id }}">Edit</button>
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

    <div class="modal fade" id="modalTambahVarian" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.menus.variants.store', $menu) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Varian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="variant_name">Nama Varian</label>
                            <input type="text" class="form-control" id="variant_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="variant_price">Harga</label>
                            <input type="number" class="form-control" id="variant_price" name="price" min="0"
                                step="0.01">
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="variant_default" name="is_default"
                                value="1">
                            <label class="form-check-label" for="variant_default">Jadikan Default</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="variant_active" name="is_active"
                                value="1" checked>
                            <label class="form-check-label" for="variant_active">Aktif</label>
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

    @foreach ($menu->variants as $variant)
        <div class="modal fade" id="modalEditVarian{{ $variant->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.variants.update', $variant) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Varian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="variant_name_{{ $variant->id }}">Nama Varian</label>
                                <input type="text" class="form-control" id="variant_name_{{ $variant->id }}"
                                    name="name" value="{{ $variant->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="variant_price_{{ $variant->id }}">Harga</label>
                                <input type="number" class="form-control" id="variant_price_{{ $variant->id }}"
                                    name="price" value="{{ $variant->price }}" min="0" step="0.01">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                    id="variant_default_{{ $variant->id }}" name="is_default" value="1"
                                    {{ $variant->is_default ? 'checked' : '' }}>
                                <label class="form-check-label" for="variant_default_{{ $variant->id }}">Jadikan
                                    Default</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    id="variant_active_{{ $variant->id }}" name="is_active" value="1"
                                    {{ $variant->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="variant_active_{{ $variant->id }}">Aktif</label>
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
@endsection
