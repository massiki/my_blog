@extends('layouts.app')

@section('title', 'Home')
@section('meta_description',
  'Blog pribadi - berbagi artikel, pengalaman, dan pengetahuan seputar teknologi dan
  kehidupan.')

@section('content')
  {{-- Hero Section --}}
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <h1 class="animate-fadeInUp">Selamat Datang di <span style="color: #4CC9F0;">BlogF</span></h1>
          <p class="animate-fadeInUp animate-delay-1">
            Tempat berbagi artikel, pengalaman, dan pengetahuan seputar teknologi, programming, dan kehidupan sehari-hari.
          </p>
          <a href="#articles" class="btn btn-light btn-lg mt-3 animate-fadeInUp animate-delay-2"
            style="border-radius: 25px; font-weight: 500;">
            <i class="bi bi-arrow-down me-2"></i>Mulai Membaca
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- Artikel Terbaru --}}
  <section id="articles" class="py-5">
    <div class="container">
      <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
          <h2 class="section-title">Artikel Terbaru</h2>
          <p class="section-subtitle">Tulisan terbaru dari blog ini</p>

          @if ($posts->count() > 0)
            <div class="row g-4">
              @foreach ($posts as $index => $post)
                <div class="col-md-6 animate-fadeInUp animate-delay-{{ ($index % 3) + 1 }}">
                  @include('partials.post-card', ['post' => $post])
                </div>
              @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-5">
              {{ $posts->links('pagination::bootstrap-4') }}
            </div>
          @else
            <div class="text-center py-5">
              <i class="bi bi-journal-x" style="font-size: 4rem; color: var(--gray-300);"></i>
              <h4 class="mt-3" style="color: var(--gray-500);">Belum ada artikel</h4>
              <p style="color: var(--gray-500);">Artikel akan segera ditambahkan. Stay tuned!</p>
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
