@extends('layouts.app')

@section('title', $keyword ? 'Hasil pencarian: ' . $keyword : 'Pencarian')
@section('meta_description', 'Cari artikel di blog')

@section('content')
  <section class="py-5">
    <div class="container">
      {{-- Page Header --}}
      <div class="mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Pencarian</li>
          </ol>
        </nav>
        <h2 class="section-title">
          <i class="bi bi-search me-2" style="color: var(--primary);"></i>
          @if ($keyword)
            Hasil untuk "{{ $keyword }}"
          @else
            Pencarian Artikel
          @endif
        </h2>
        @if ($keyword)
          <p class="section-subtitle">Ditemukan {{ $posts->total() }} artikel</p>
        @endif
      </div>

      <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
          {{-- Search Box (di halaman search) --}}
          <div class="mb-4">
            <form action="{{ route('search') }}" method="GET">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="q" placeholder="Ketik kata kunci..."
                  value="{{ $keyword }}" style="border-radius: 12px 0 0 12px; border: 2px solid var(--gray-200);">
                <button class="btn btn-primary" type="submit" style="border-radius: 0 12px 12px 0;">
                  <i class="bi bi-search me-1"></i> Cari
                </button>
              </div>
            </form>
          </div>

          @if ($posts->count() > 0)
            <div class="row g-4">
              @foreach ($posts as $post)
                <div class="col-md-6">
                  @include('partials.post-card', ['post' => $post])
                </div>
              @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-5">
              {{ $posts->links('pagination::bootstrap-4') }}
            </div>
          @elseif($keyword)
            <div class="text-center py-5">
              <i class="bi bi-search" style="font-size: 4rem; color: var(--gray-300);"></i>
              <h4 class="mt-3" style="color: var(--gray-500);">Tidak ditemukan</h4>
              <p style="color: var(--gray-500);">Tidak ada artikel yang cocok dengan
                "<strong>{{ $keyword }}</strong>". Coba kata kunci lain.</p>
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
