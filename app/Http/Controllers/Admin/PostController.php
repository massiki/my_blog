<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Tampilkan daftar semua artikel di admin.
     * Bisa difilter berdasarkan status (draft/published).
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'user'])->latest();

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Tampilkan form untuk membuat artikel baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Simpan artikel baru ke database.
     * Handle upload thumbnail dan attach tags.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_description' => 'nullable|string|max:160',
            'status'           => 'required|in:draft,published',
            'tags'             => 'nullable|string', // Tags dipisahkan koma
        ]);

        // Upload thumbnail jika ada
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        // Buat post baru
        $post = Post::create([
            'user_id'          => Auth::id(),
            'category_id'      => $validated['category_id'],
            'title'            => $validated['title'],
            'content'          => $validated['content'],
            'thumbnail'        => $thumbnailPath,
            'meta_description' => $validated['meta_description'],
            'status'           => $validated['status'],
            'published_at'     => $validated['status'] === 'published' ? now() : null,
        ]);

        // Handle tags (input: "laravel, php, tutorial")
        if ($request->filled('tags')) {
            $this->syncTags($post, $request->tags);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    /**
     * Tampilkan form edit artikel.
     */
    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $post->load('tags');

        // Gabungkan nama tag menjadi string untuk input form
        $tagNames = $post->tags->pluck('name')->implode(', ');

        return view('admin.posts.edit', compact('post', 'categories', 'tagNames'));
    }

    /**
     * Update artikel yang sudah ada.
     * Handle perubahan thumbnail dan tags.
     */
    public function update(Request $request, Post $post)
    {
        // Validasi input
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_description' => 'nullable|string|max:160',
            'status'           => 'required|in:draft,published',
            'tags'             => 'nullable|string',
        ]);

        // Upload thumbnail baru jika ada
        $thumbnailPath = $post->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        // Jika checkbox "hapus thumbnail" dicentang
        if ($request->boolean('remove_thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $thumbnailPath = null;
        }

        // Update slug jika title berubah
        $slug = $post->slug;
        if ($post->title !== $validated['title']) {
            $slug = Post::generateUniqueSlug($validated['title']);
        }

        // Set published_at jika status berubah ke published
        $publishedAt = $post->published_at;
        if ($validated['status'] === 'published' && !$post->isPublished()) {
            $publishedAt = now();
        }

        $post->update([
            'title'            => $validated['title'],
            'slug'             => $slug,
            'content'          => $validated['content'],
            'category_id'      => $validated['category_id'],
            'thumbnail'        => $thumbnailPath,
            'meta_description' => $validated['meta_description'],
            'status'           => $validated['status'],
            'published_at'     => $publishedAt,
        ]);

        // Sync tags
        if ($request->filled('tags')) {
            $this->syncTags($post, $request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Hapus artikel dari database.
     * Juga hapus thumbnail dari storage.
     */
    public function destroy(Post $post)
    {
        // Cari gambar dalam konten, hapus file gambar jika ada sebelum post dihapus
        $content = $post->content;

        // Ambil semua url image yang berbentuk <figure ... <img src="..." ...> ... </figure>
        // atau <img src="..."> langsung di html
        $imageUrls = [];

        // Ambil semua <img src="...">, regex mencari semua src
        if (preg_match_all('/<img[^>]+src="([^"]+)"/i', $content, $matches)) {
            $imageUrls = array_merge($imageUrls, $matches[1]);
        }

        // Selain <img>, juga cek jika ada Trix attachment dengan data-trix-attachment (misal dalam <figure>)
        if (preg_match_all('/data-trix-attachment=[\'"]([^\'"]+)[\'"]/i', $content, $matches)) {
            foreach ($matches[1] as $jsonStr) {
                $json = json_decode(htmlspecialchars_decode($jsonStr), true);
                if (is_array($json) && isset($json['url'])) {
                    $imageUrls[] = $json['url'];
                }
            }
        }

        // Hapus file gambar jika ditemukan di url yang terkait storage local
        foreach ($imageUrls as $url) {
            // Cek apakah url file storage lokal
            // Misal: /storage/uploads/xxx.jpeg
            if (preg_match('#^/storage/#', $url)) {
                // Ubah menjadi path relatif sesuai storage/app/public/
                $relativePath = ltrim(preg_replace('#^/?storage/#', '', $url), '/');
                // Hapus jika file ada
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            }
        }

        // Hapus thumbnail dari storage
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        // Hapus relasi tags (pivot table)
        $post->tags()->detach();

        // Hapus post
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Helper: Sync tags dari string input.
     * Input: "laravel, php, tutorial"
     * - Cari tag yang sudah ada, atau buat baru
     * - Sync ke post (hapus yang tidak ada, tambah yang baru)
     */
    private function syncTags(Post $post, string $tagsInput): void
    {
        $tagNames = array_map('trim', explode(',', $tagsInput));
        $tagIds = [];

        foreach ($tagNames as $name) {
            if (empty($name)) continue;

            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $tagIds[] = $tag->id;
        }

        $post->tags()->sync($tagIds);
    }
}
