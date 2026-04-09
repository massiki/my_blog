@extends('layouts.admin')

@section('title', 'Daftar Kategori')
@section('page_title', 'Daftar Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Kelola kategori artikel blog Anda.</p>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
    </a>
</div>

<div class="admin-table">
    <table class="table table-hover">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Nama Kategori</th>
                <th>Slug</th>
                <th>Jumlah Artikel</th>
                <th style="width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $index => $category)
                <tr>
                    <td class="text-muted">{{ $index + 1 }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $category->posts_count }} artikel</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini? Artikel di kategori ini tidak akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-bookmark-x d-block mb-2" style="font-size: 2rem;"></i>
                        Belum ada kategori.
                        <a href="{{ route('admin.categories.create') }}">Tambah kategori pertama!</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
