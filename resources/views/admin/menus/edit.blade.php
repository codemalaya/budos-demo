@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('header')
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
            <h1 class="mb-1">Edit Menu</h1>
            <p class="mb-0 text-muted">Perbarui data menu.</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Kembali</a>
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

            <form method="POST" action="{{ route('admin.menus.update', $menu) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama Menu</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $menu->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="slug">Slug (opsional)</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="{{ old('slug', $menu->slug) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="base_price">Harga Dasar</label>
                        <input type="number" class="form-control" id="base_price" name="base_price"
                            value="{{ old('base_price', $menu->base_price) }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="image_path">URL Gambar (opsional)</label>
                        <input type="text" class="form-control" id="image_path" name="image_path"
                            value="{{ old('image_path', $menu->image_path) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $menu->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
