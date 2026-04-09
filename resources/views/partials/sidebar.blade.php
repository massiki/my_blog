{{-- Sidebar blog (kategori & info) --}}
{{-- Penggunaan: @include('partials.sidebar', ['categories' => $categories]) --}}

{{-- Widget: Pencarian --}}
<div class="sidebar-widget">
    <h5><i class="bi bi-search me-2"></i>Pencarian</h5>
    <form action="{{ route('search') }}" method="GET">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Cari artikel..." value="{{ request('q') }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
</div>

{{-- Widget: Kategori --}}
@if(isset($categories) && $categories->count() > 0)
<div class="sidebar-widget">
    <h5><i class="bi bi-bookmark me-2"></i>Kategori</h5>
    <ul class="list-group list-group-flush">
        @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="list-group-item list-group-item-action">
                {{ $category->name }}
                <span class="badge rounded-pill">{{ $category->posts_count ?? 0 }}</span>
            </a>
        @endforeach
    </ul>
</div>
@endif

{{-- Widget: Tentang Blog --}}
<div class="sidebar-widget">
    <h5><i class="bi bi-info-circle me-2"></i>Tentang</h5>
    <p class="mb-0" style="font-size: 0.9rem; color: var(--gray-700);">
        Blog pribadi untuk berbagi artikel, pengalaman, dan pengetahuan.
        Ditulis dengan ❤️ menggunakan Laravel & Bootstrap.
    </p>
</div>
