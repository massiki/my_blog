@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $stats['total_posts'] }}</div>
                    <div class="stat-label mt-1">Total Artikel</div>
                </div>
                <div class="stat-icon bg-primary-soft">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $stats['published'] }}</div>
                    <div class="stat-label mt-1">Published</div>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $stats['drafts'] }}</div>
                    <div class="stat-label mt-1">Draft</div>
                </div>
                <div class="stat-icon bg-warning-soft">
                    <i class="bi bi-pencil-square"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $stats['categories'] }}</div>
                    <div class="stat-label mt-1">Kategori</div>
                </div>
                <div class="stat-icon bg-info-soft">
                    <i class="bi bi-bookmark"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Posts --}}
<div class="row">
    <div class="col-12">
        <div class="admin-table">
            <div class="p-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Artikel Terbaru</h5>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-admin-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Tulis Artikel
                </a>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPosts as $post)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($post->title, 50) }}</strong>
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
                                    <span class="badge-published">Published</span>
                                @else
                                    <span class="badge-draft">Draft</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $post->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada artikel. <a href="{{ route('admin.posts.create') }}">Tulis artikel pertama!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
