{{-- Card artikel (reusable component) --}}
{{-- Penggunaan: @include('partials.post-card', ['post' => $post]) --}}

<div class="post-card">
    {{-- Thumbnail --}}
    <div class="card-img-wrapper">
        @if($post->thumbnail)
            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}">
        @else
            <div class="no-thumbnail w-100 h-100">
                <i class="bi bi-journal-text"></i>
            </div>
        @endif

        {{-- Badge Kategori --}}
        @if($post->category)
            <a href="{{ route('category.show', $post->category->slug) }}" class="category-badge">
                <span class="badge badge-category">{{ $post->category->name }}</span>
            </a>
        @endif
    </div>

    {{-- Body --}}
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ route('post.show', $post->slug) }}">{{ $post->title }}</a>
        </h5>
        <p class="card-text">{{ $post->excerpt }}</p>

        {{-- Meta info --}}
        <div class="card-meta">
            <span><i class="bi bi-person"></i> {{ $post->user->name ?? 'Admin' }}</span>
            <span><i class="bi bi-calendar3"></i> {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
        </div>
    </div>
</div>
