<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    /**
     * Handle upload gambar dari Trix Editor.
     *
     * Trix Editor mengirim file gambar via AJAX saat user
     * menyisipkan gambar di editor. Controller ini:
     * 1. Menerima file gambar
     * 2. Menyimpan ke storage/app/public/uploads
     * 3. Mengembalikan URL gambar dalam format JSON
     *
     * Response JSON digunakan oleh Trix untuk menampilkan gambar di editor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('file')) {
            // Simpan file dengan nama unik ke folder uploads
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename, 'public');

            // Return URL yang bisa diakses publik
            return response()->json([
                'url' => Storage::url($path),
            ]);
        }

        return response()->json(['error' => 'Tidak ada file yang diupload.'], 422);
    }
}
