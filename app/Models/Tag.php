<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
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

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // ===== RELATIONSHIPS =====

    /**
     * Tag bisa dimiliki banyak Post (Many-to-Many).
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
