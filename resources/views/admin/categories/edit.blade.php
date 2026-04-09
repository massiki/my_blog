@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page_title', 'Edit Kategori')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="admin-form">
            <h5 class="fw-bold mb-4">
                <i class="bi bi-bookmark-fill me-2"></i>Edit Kategori
            </h5>

            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $category->name) }}"
                           placeholder="Masukkan nama kategori..." required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Slug saat ini: <code>{{ $category->slug }}</code></small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-1"></i>Perbarui Kategori
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
