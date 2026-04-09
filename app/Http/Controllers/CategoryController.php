<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    /**
     * Tampilkan artikel berdasarkan kategori.
     * Filter hanya post yang sudah dipublish.
     */
    public function show(string $slug)
    {
        // Cari kategori berdasarkan slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Ambil post di kategori tersebut
        $posts = Post::published()
                     ->where('category_id', $category->id)
                     ->with(['category', 'user'])
                     ->latest('published_at')
                     ->paginate(9);

        // Ambil semua kategori untuk sidebar
        $categories = Category::withCount(['posts' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        return view('categories.show', compact('category', 'posts', 'categories'));
    }
}
