@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('og_type', 'article')
@if($post->thumbnail)
    @section('og_image', asset('storage/' . $post->thumbnail))
@endif

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            {{-- Main Content --}}
            <div class="col-lg-8">
                <article class="single-post">
                    {{-- Header --}}
                    <div class="post-header">
                        {{-- Kategori --}}
                        @if($post->category)
                            <a href="{{ route('category.show', $post->category->slug) }}"
                               class="badge badge-category mb-3 d-inline-block">
                                {{ $post->category->name }}
                            </a>
                        @endif

                        <h1>{{ $post->title }}</h1>

                        {{-- Meta --}}
                        <div class="post-meta">
                            <span>
                                <i class="bi bi-person-circle"></i>
                                {{ $post->user->name ?? 'Admin' }}
                            </span>
                            <span>
                                <i class="bi bi-calendar3"></i>
                                {{ $post->published_at ? $post->published_at->format('d F Y') : $post->created_at->format('d F Y') }}
                            </span>
                            <span>
                                <i class="bi bi-clock"></i>
                                {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read
                            </span>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    @if($post->thumbnail)
                        <img src="{{ asset('storage/' . $post->thumbnail) }}"
                             alt="{{ $post->title }}"
                             class="post-thumbnail">
                    @endif

                    {{-- Content --}}
                    <div class="post-content">
                        {!! $post->content !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags->count() > 0)
                        <div class="post-tags">
                            <i class="bi bi-tags me-2"></i>
                            @foreach($post->tags as $tag)
                                <span class="badge badge-tag me-1">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </article>

                {{-- Share Buttons --}}
                <div class="mt-4 p-3 bg-white rounded-3 border">
                    <strong class="me-3"><i class="bi bi-share me-1"></i>Bagikan:</strong>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                       target="_blank" class="btn btn-sm btn-outline-primary me-1">
                        <i class="bi bi-twitter-x"></i> Twitter
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       target="_blank" class="btn btn-sm btn-outline-primary me-1">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                       target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->count() > 0)
                    <div class="related-posts">
                        <h4><i class="bi bi-collection me-2"></i>Artikel Terkait</h4>
                        <div class="row g-4">
                            @foreach($relatedPosts as $related)
                                <div class="col-md-4">
                                    @include('partials.post-card', ['post' => $related])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 mt-4 mt-lg-0">
                @php
                    $categories = \App\Models\Category::withCount(['posts' => function ($q) {
                        $q->where('status', 'published');
                    }])->get();
                @endphp
                @include('partials.sidebar', ['categories' => $categories])
            </div>
        </div>
    </div>
</section>
@endsection
