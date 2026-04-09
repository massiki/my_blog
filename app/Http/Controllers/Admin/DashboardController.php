<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin.
     * Menampilkan statistik: total post, published, draft, kategori.
     */
    public function index()
    {
        $stats = [
            'total_posts'    => Post::count(),
            'published'      => Post::published()->count(),
            'drafts'         => Post::draft()->count(),
            'categories'     => Category::count(),
        ];

        // 5 post terbaru untuk ditampilkan di dashboard
        $recentPosts = Post::with(['category', 'user'])
                          ->latest()
                          ->limit(5)
                          ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts'));
    }
}
