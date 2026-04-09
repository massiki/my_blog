<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Tampilkan homepage dengan daftar artikel terbaru.
     * Hanya menampilkan post yang sudah dipublish.
     * Pagination: 9 artikel per halaman.
     */
    public function index()
    {
        $posts = Post::published()
                     ->with(['category', 'user']) // Eager loading untuk performa
                     ->latest('published_at')     // Urutkan dari terbaru
                     ->paginate(9);

        $categories = Category::withCount(['posts' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        return view('home', compact('posts', 'categories'));
    }
}
