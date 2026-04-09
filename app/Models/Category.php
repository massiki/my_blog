<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Boot method: auto-generate slug dari name.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // ===== RELATIONSHIPS =====

    /**
     * Category memiliki banyak Post.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Hitung jumlah post yang sudah dipublish di kategori ini.
     */
    public function publishedPostsCount(): int
    {
        return $this->posts()->published()->count();
    }
}
