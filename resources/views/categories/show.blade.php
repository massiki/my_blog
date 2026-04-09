@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name)
@section('meta_description', 'Artikel dalam kategori ' . $category->name)

@section('content')
<section class="py-5">
    <div class="container">
        {{-- Page Header --}}
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
            <h2 class="section-title">
                <i class="bi bi-bookmark-fill me-2" style="color: var(--primary);"></i>{{ $category->name }}
            </h2>
            <p class="section-subtitle">{{ $posts->total() }} artikel dalam kategori ini</p>
        </div>

        <div class="row">
            {{-- Main Content --}}
            <div class="col-lg-8">
                @if($posts->count() > 0)
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-6">
                                @include('partials.post-card', ['post' => $post])
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x" style="font-size: 4rem; color: var(--gray-300);"></i>
                        <h4 class="mt-3" style="color: var(--gray-500);">Belum ada artikel</h4>
                        <p style="color: var(--gray-500);">Belum ada artikel di kategori ini.</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 mt-4 mt-lg-0">
                @include('partials.sidebar', ['categories' => $categories])
            </div>
        </div>
    </div>
</section>
@endsection
