@extends('layouts.admin')

@section('title', 'Daftar Artikel')
@section('page_title', 'Daftar Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        {{-- Filter Status --}}
        <div class="btn-group" role="group">
            <a href="{{ route('admin.posts.index') }}"
               class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua
            </a>
            <a href="{{ route('admin.posts.index', ['status' => 'published']) }}"
               class="btn btn-sm {{ request('status') === 'published' ? 'btn-primary' : 'btn-outline-primary' }}">
                Published
            </a>
            <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}"
               class="btn btn-sm {{ request('status') === 'draft' ? 'btn-primary' : 'btn-outline-primary' }}">
                Draft
            </a>
        </div>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-1"></i>Tulis Artikel Baru
    </a>
</div>

<div class="admin-table">
    <table class="table table-hover">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th style="width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $index => $post)
                <tr>
                    <td class="text-muted">{{ $posts->firstItem() + $index }}</td>
                    <td>
                        <div>
                            <strong>{{ Str::limit($post->title, 60) }}</strong>
                            <br>
                            <small class="text-muted">{{ url('/post/' . $post->slug) }}</small>
                        </div>
                    </td>
                    <td>
                        @if($post->category)
                            <span class="badge badge-category">{{ $post->category->name }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($post->status === 'published')
                            <span class="badge-published">
                                <i class="bi bi-check-circle me-1"></i>Published
                            </span>
                        @else
                            <span class="badge-draft">
                                <i class="bi bi-pencil me-1"></i>Draft
                            </span>
                        @endif
                    </td>
                    <td class="text-muted">
                        {{ $post->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            {{-- Lihat (jika published) --}}
                            @if($post->status === 'published')
                                <a href="{{ route('post.show', $post->slug) }}" target="_blank"
                                   class="btn btn-sm btn-outline-secondary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endif

                            {{-- Edit --}}
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
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
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-journal-x d-block mb-2" style="font-size: 2rem;"></i>
                        Belum ada artikel.
                        <a href="{{ route('admin.posts.create') }}">Tulis artikel pertama!</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($posts->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
@endif
@endsection
