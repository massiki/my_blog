{{-- Navbar utama blog --}}
<nav class="navbar navbar-expand-lg navbar-blog sticky-top">
    <div class="container">
        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-journal-richtext me-1"></i> My<span>Blog</span>
        </a>

        {{-- Toggle button (mobile) --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar content --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i>Home
                    </a>
                </li>

                {{-- Dropdown Kategori --}}
                @php
                    $navCategories = \App\Models\Category::orderBy('name')->get();
                @endphp
                @if($navCategories->count() > 0)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('category.show') ? 'active' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bookmark me-1"></i>Kategori
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($navCategories as $cat)
                            <li>
                                <a class="dropdown-item" href="{{ route('category.show', $cat->slug) }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                @endif
            </ul>

            {{-- Search Form --}}
            <form class="search-form d-flex" action="{{ route('search') }}" method="GET">
                <input class="form-control me-2" type="search" name="q"
                       placeholder="Cari artikel..." value="{{ request('q') }}">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
</nav>
