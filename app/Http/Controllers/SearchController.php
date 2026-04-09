<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Cari artikel berdasarkan keyword.
     * Mencari di title dan content post yang sudah dipublish.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('q', '');

        $posts = Post::published()
                     ->when($keyword, function ($query) use ($keyword) {
                         $query->search($keyword);
                     })
                     ->with(['category', 'user'])
                     ->latest('published_at')
                     ->paginate(9)
                     ->appends(['q' => $keyword]); // Pertahankan keyword di URL pagination

        $categories = Category::withCount(['posts' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        return view('search', compact('posts', 'keyword', 'categories'));
    }
}
