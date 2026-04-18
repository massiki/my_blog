<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — Admin Panel</title>
  <link rel="shortcut icon" href="{{ asset('img/logo-fikri.png') }}">

  {{-- Bootstrap 5 CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  {{-- Custom Admin CSS --}}
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

  @stack('styles')
</head>

<body class="admin-body">
  {{-- Sidebar Overlay (mobile) --}}
  <div class="sidebar-overlay"></div>

  {{-- Sidebar --}}
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <img src="{{ asset('img/logo-fikri.png') }}" alt="Logo" style="height: 1.6em; margin-right: 0.25em;">

      Blog<span>F</span>
    </div>
    <nav class="sidebar-nav">
      <div class="sidebar-heading">Menu Utama</div>

      <a href="{{ route('admin.dashboard') }}"
        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
      </a>

      <a href="{{ route('admin.posts.index') }}"
        class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text-fill"></i> Artikel
      </a>

      <a href="{{ route('admin.categories.index') }}"
        class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-bookmark-fill"></i> Kategori
      </a>

      <div class="sidebar-heading mt-3">Lainnya</div>

      <a href="{{ route('home') }}" class="nav-link" target="_blank">
        <i class="bi bi-box-arrow-up-right"></i> Lihat Blog
      </a>

      <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
          <i class="bi bi-box-arrow-left"></i> Logout
        </button>
      </form>
    </nav>
  </aside>

  {{-- Main Content --}}
  <main class="admin-main">
    {{-- Top Bar --}}
    <header class="admin-topbar">
      <div class="d-flex align-items-center">
        <button class="sidebar-toggle me-3">
          <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
      </div>
      <div class="d-flex align-items-center">
        <span class="text-muted me-2">
          <i class="bi bi-person-circle"></i>
        </span>
        <span class="fw-medium">{{ Auth::user()->name }}</span>
      </div>
    </header>

    {{-- Content Area --}}
    <div class="admin-content">
      {{-- Flash Messages --}}
      @if (session('success'))
        <div class="alert alert-success alert-flash alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-flash alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @yield('content')
    </div>
  </main>

  {{-- Bootstrap 5 JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Sidebar Toggle Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggle = document.querySelector('.sidebar-toggle');
      const sidebar = document.querySelector('.admin-sidebar');
      const overlay = document.querySelector('.sidebar-overlay');

      if (toggle) {
        toggle.addEventListener('click', function() {
          sidebar.classList.toggle('show');
          overlay.classList.toggle('show');
        });
      }

      if (overlay) {
        overlay.addEventListener('click', function() {
          sidebar.classList.remove('show');
          overlay.classList.remove('show');
        });
      }

      // Auto-dismiss flash messages
      document.querySelectorAll('.alert-flash').forEach(function(alert) {
        setTimeout(function() {
          alert.style.transition = 'opacity 0.5s ease';
          alert.style.opacity = '0';
          setTimeout(function() {
            alert.remove();
          }, 500);
        }, 4000);
      });
    });
  </script>

  @stack('scripts')
</body>

</html>
