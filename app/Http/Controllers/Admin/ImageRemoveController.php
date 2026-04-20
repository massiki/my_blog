<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageRemoveController extends Controller
{
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        // path yang ditangkatp "/storage/uploads/xxx.jpg"
        $path = $request->input('path');
        // mengubah path dengan menghilangkan /storage/
        $relativePath = ltrim(preg_replace('#^/?storage/#', '', $path), '/');

        // Cek apakah file dengan path relatif tersebut ada di storage publik
        if (Storage::disk('public')->exists($relativePath)) {
            // hapus file tersebut dari storage
            Storage::disk('public')->delete($relativePath);

            return response()->json(['message' => 'Gambar berhasil dihapus.'], 200);
        }

        return response()->json(['error' => 'Gambar tidak ditemukan atau path tidak valid.',], 404);
    }
}
