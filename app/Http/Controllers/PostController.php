<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Tampilkan detail artikel berdasarkan slug.
     * Juga menampilkan artikel terkait (dari kategori yang sama).
     */
    public function show(string $slug)
    {
        // Cari post berdasarkan slug, hanya yang sudah dipublish
        $post = Post::where('slug', $slug)
                    ->published()
                    ->with(['category', 'user', 'tags'])
                    ->firstOrFail();

        // Ambil artikel terkait (kategori sama, bukan artikel ini sendiri)
        $relatedPosts = Post::published()
                            ->where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->latest('published_at')
                            ->limit(3)
                            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
