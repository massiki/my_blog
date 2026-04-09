<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel posts menyimpan semua artikel blog.
     * Relasi:
     * - belongsTo User (penulis)
     * - belongsTo Category (kategori, nullable)
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users (penulis artikel)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Foreign key ke tabel categories (nullable jika belum dikategorikan)
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->string('title');                    // Judul artikel
            $table->string('slug')->unique();           // URL-friendly slug
            $table->longText('content');                 // Konten artikel (HTML dari Trix Editor)
            $table->string('thumbnail')->nullable();    // Path gambar thumbnail
            $table->string('meta_description')->nullable(); // Deskripsi SEO
            $table->enum('status', ['draft', 'published'])->default('draft'); // Status artikel
            $table->timestamp('published_at')->nullable(); // Tanggal publish
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
