@extends('layouts.admin')

@section('title', 'Tambah Varian')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">Tambah Varian</h1>
            <p class="mb-0 text-muted">Varian untuk {{ $menu->name }}.</p>
        </div>
        <a href="{{ route('admin.menus.variants.index', $menu) }}" class="btn btn-outline-secondary">Kembali</a>
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

            <form method="POST" action="{{ route('admin.menus.variants.store', $menu) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama Varian</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="price">Harga</label>
                        <input type="number" class="form-control" id="price" name="price"
                            value="{{ old('price') }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default"
                                value="1">
                            <label class="form-check-label" for="is_default">Jadikan Default</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" checked>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.menus.variants.index', $menu) }}"
                        class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
