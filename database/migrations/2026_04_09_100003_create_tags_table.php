<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel tags menyimpan label/tag untuk artikel.
     * Relasi many-to-many dengan posts via tabel pivot post_tag.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Nama tag, misal: "Laravel"
            $table->string('slug')->unique(); // URL-friendly, misal: "laravel"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
