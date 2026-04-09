<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'meta_description',
        'status',
        'published_at',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Boot method: auto-generate slug dari title saat membuat post baru.
     * Jika slug sudah ada (duplikat), tambahkan angka di belakangnya.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });
    }

    /**
     * Generate slug unik. Jika sudah ada, tambahkan -1, -2, dst.
     */
    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // ===== RELATIONSHIPS =====

    /**
     * Post ditulis oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Post termasuk dalam satu Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Post bisa memiliki banyak Tag (Many-to-Many).
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: hanya ambil post yang sudah dipublish.
     * Penggunaan: Post::published()->get()
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: hanya ambil post draft.
     * Penggunaan: Post::draft()->get()
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: cari post berdasarkan keyword di title atau content.
     * Penggunaan: Post::search('laravel')->get()
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('title', 'like', '%' . $keyword . '%')
                     ->orWhere('content', 'like', '%' . $keyword . '%');
    }

    // ===== HELPERS =====

    /**
     * Ambil ringkasan konten (strip HTML tags, potong 150 karakter).
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Cek apakah post sudah dipublish.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
