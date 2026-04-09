@extends('layouts.admin')

@section('title', 'Edit Artikel')
@section('page_title', 'Edit Artikel')

@push('styles')
    {{-- Trix Editor CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.8/dist/trix.css">
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="admin-form mb-4">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-pencil-square me-2"></i>Edit Konten
                </h5>

                {{-- Judul --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                           id="title" name="title" value="{{ old('title', $post->title) }}"
                           placeholder="Masukkan judul artikel..." required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        Slug saat ini: <code>{{ $post->slug }}</code>
                        (akan diperbarui jika judul berubah)
                    </small>
                </div>

                {{-- Konten (Trix Editor) --}}
                <div class="mb-3">
                    <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>

                    <input id="content" type="hidden" name="content"
                           value="{{ old('content', $post->content) }}">

                    <trix-editor input="content"
                                 class="@error('content') border-danger @enderror"></trix-editor>

                    @error('content')
                        <div class="text-danger mt-1" style="font-size: 0.875rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Meta Description --}}
                <div class="mb-3">
                    <label for="meta_description" class="form-label">Meta Description (SEO)</label>
                    <textarea class="form-control @error('meta_description') is-invalid @enderror"
                              id="meta_description" name="meta_description" rows="2"
                              placeholder="Deskripsi singkat untuk SEO (maks. 160 karakter)"
                              maxlength="160">{{ old('meta_description', $post->meta_description) }}</textarea>
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
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id" name="category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
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
                    <input type="text" class="form-control @error('tags') is-invalid @enderror"
                           id="tags" name="tags"
                           value="{{ old('tags', $tagNames) }}"
                           placeholder="laravel, php, tutorial (pisahkan dengan koma)">
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Pisahkan tag dengan koma.</small>
                </div>

                {{-- Thumbnail --}}
                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Gambar Thumbnail</label>

                    {{-- Thumbnail saat ini --}}
                    @if($post->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                 class="thumbnail-preview" alt="Current thumbnail">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox"
                                       name="remove_thumbnail" id="removeThumbnail" value="1">
                                <label class="form-check-label text-danger" for="removeThumbnail">
                                    <i class="bi bi-trash me-1"></i>Hapus thumbnail
                                </label>
                            </div>
                        </div>
                    @endif

                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                           id="thumbnail" name="thumbnail" accept="image/*">
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: JPEG, PNG, JPG, GIF, WebP. Maks: 2MB. Kosongkan jika tidak ingin mengubah.</small>

                    <div id="thumbnailPreview" class="mt-2" style="display: none;">
                        <img id="thumbnailImg" src="" class="thumbnail-preview">
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>
                            📝 Draft
                        </option>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>
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
                    <i class="bi bi-save me-1"></i>Perbarui Artikel
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
        // Upload gambar di Trix Editor
        document.addEventListener('trix-attachment-add', function(event) {
            var attachment = event.attachment;

            if (attachment.file) {
                var formData = new FormData();
                formData.append('file', attachment.file);

                fetch('{{ route("admin.upload.image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.url) {
                        attachment.setAttributes({ url: data.url, href: data.url });
                    }
                })
                .catch(error => {
                    console.error('Upload gagal:', error);
                    attachment.remove();
                    alert('Gagal mengupload gambar.');
                });
            }
        });

        // Thumbnail Preview
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

        // Meta Description Counter
        var metaInput = document.getElementById('meta_description');
        var metaCount = document.getElementById('metaCharCount');
        metaInput.addEventListener('input', function() {
            metaCount.textContent = this.value.length;
        });
        metaCount.textContent = metaInput.value.length;
    </script>
@endpush
