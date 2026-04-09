<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot post_tag menghubungkan posts dan tags.
     * Relasi: Many-to-Many
     * - Satu post bisa punya banyak tag
     * - Satu tag bisa dimiliki banyak post
     */
    public function up(): void
    {
        Schema::create('post_tag', function (Blueprint $table) {
            // Composite primary key dari post_id + tag_id
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['post_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
