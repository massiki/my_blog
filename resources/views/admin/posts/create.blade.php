@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru')
@section('page_title', 'Tulis Artikel Baru')

@push('styles')
  {{-- Trix Editor CSS --}}
  <link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
@endpush

@section('content')
  <div class="row">
    <div class="col-lg-8">
      <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="admin-form mb-4">
          <h5 class="fw-bold mb-4">
            <i class="bi bi-pencil-square me-2"></i>Konten Artikel
          </h5>

          {{-- Judul --}}
          <div class="mb-3">
            <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
              value="{{ old('title') }}" placeholder="Masukkan judul artikel..." required>
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Slug akan di-generate otomatis dari judul.</small>
          </div>

          {{-- Konten (Trix Editor) --}}
          <div class="mb-3">
            <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>

            {{-- Hidden input yang menyimpan konten HTML dari Trix --}}
            <input id="content" type="hidden" name="content" value="{{ old('content') }}">

            {{-- Trix Editor element --}}
            <trix-editor input="content" class="@error('content') border-danger @enderror"
              placeholder="Tulis konten artikel di sini..."></trix-editor>

            @error('content')
              <div class="text-danger mt-1" style="font-size: 0.875rem;">{{ $message }}</div>
            @enderror
          </div>

          {{-- Meta Description --}}
          <div class="mb-3">
            <label for="meta_description" class="form-label">Meta Description (SEO)</label>
            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
              name="meta_description" rows="2" placeholder="Deskripsi singkat untuk SEO (maks. 160 karakter)" maxlength="160">{{ old('meta_description') }}</textarea>
            @error('meta_description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">
              <span id="metaCharCount">0</span>/160 karakter
            </small>
          </div>
        </div>

        <div class="admin-form mb-4">
          <h5 class="fw-bold mb-4">
            <i class="bi bi-gear me-2"></i>Pengaturan
          </h5>

          {{-- Kategori --}}
          <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
              <option value="">-- Pilih Kategori --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Tags --}}
          <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags"
              value="{{ old('tags') }}" placeholder="laravel, php, tutorial (pisahkan dengan koma)">
            @error('tags')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Pisahkan tag dengan koma. Tag baru akan dibuat jika belum ada.</small>
          </div>

          {{-- Thumbnail --}}
          <div class="mb-3">
            <label for="thumbnail" class="form-label">Gambar Thumbnail</label>
            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail"
              name="thumbnail" accept="image/*">
            @error('thumbnail')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Format: JPEG, PNG, JPG, GIF, WebP. Maks: 2MB.</small>
            {{-- Preview --}}
            <div id="thumbnailPreview" class="mt-2" style="display: none;">
              <img id="thumbnailImg" src="" class="thumbnail-preview">
            </div>
          </div>

          {{-- Status --}}
          <div class="mb-3">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
              <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                📝 Draft
              </option>
              <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>
                🚀 Published
              </option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-admin-primary">
            <i class="bi bi-save me-1"></i>Simpan Artikel
          </button>
          <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Batal
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  {{-- Trix Editor JS --}}
  <script src="https://unpkg.com/trix@2.1.8/dist/trix.umd.min.js"></script>

  <script>
    // ============================
    // Trix Editor: Upload Gambar
    // ============================
    // Saat user menyisipkan gambar di Trix Editor,
    // gambar akan di-upload ke server via AJAX.

    // event trix-attachment-add terjadi ketika ada file yang di upload
    document.addEventListener('trix-attachment-add', function(event) {
      // event.attachment berisi object attechment dari trix 
      var attachment = event.attachment;

      // apakah ada file?
      if (attachment.file) {
        // Buat FormData untuk upload
        var formData = new FormData();
        // tambahkan formData dengan key dan value
        formData.append('file', attachment.file);

        // Kirim via fetch API
        fetch('{{ route('admin.upload.image') }}', {
            // method post karena mengirim data file
            method: 'POST',
            // CSRF token, token diambil dari tag head pada layout
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            // body ini yang akan di kirim ke server
            body: formData
          })
          // respone dari server diubah menjadi json
          .then(response => response.json())
          // data berisi response json
          .then(data => {
            // apakah ada data url
            if (data.url) {
              // Set URL gambar ke Trix attachment
              attachment.setAttributes({
                url: data.url,
                href: data.url
              });
            }
          })
          .catch(error => {
            console.error('Upload gagal:', error);
            attachment.remove();
            alert('Gagal mengupload gambar. Silakan coba lagi.');
          });
      }
    });

    // ============================
    // Remove file image dari trix editor
    // ============================
    // event ini terjadi ketika mengklik tombol "X" untuk ngeremove gambar
    document.addEventListener("trix-attachment-remove", function(event) {
      // Ambil attachment dari event
      var attachment = event.attachment;
      // Dapatkan attachement file_path dari response upload
      var path = attachment.getAttributes().url;
      // Kalau ada file_path, kirim request ke server untuk hapus
      if (path) {
        fetch('{{ route('admin.remove.image') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              path: path
            })
          })
          .then(response => response.json())
          .then(data => {
            console.log(data);
          })
          .catch(error => {
            console.error('Gagal menghapus gambar:', error);
          });
      }
    });

    // ============================
    // Thumbnail Preview
    // ============================
    document.getElementById('thumbnail').addEventListener('change', function(e) {
      var file = e.target.files[0];
      var preview = document.getElementById('thumbnailPreview');
      var img = document.getElementById('thumbnailImg');

      if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
          img.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        preview.style.display = 'none';
      }
    });

    // ============================
    // Meta Description Counter
    // ============================
    var metaInput = document.getElementById('meta_description');
    var metaCount = document.getElementById('metaCharCount');

    metaInput.addEventListener('input', function() {
      metaCount.textContent = this.value.length;
    });

    // Init count
    metaCount.textContent = metaInput.value.length;
  </script>
@endpush
