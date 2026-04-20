{{-- Footer blog --}}
<footer class="footer-blog">
  <div class="container">
    <div class="row">
      {{-- About --}}
      <div class="col-lg-4 mb-4">
        <div class="footer-brand mb-3">
          Blog<span>F</span>
        </div>
        <p class="mb-0">Blog pribadi untuk berbagi artikel, pengalaman, pengetahuan, teknologi maupun kehidupan sehari
          hari.</p>
      </div>

      {{-- Quick Links --}}
      <div class="col-lg-4 mb-4">
        <h5>Quick Links</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="{{ route('home') }}"><i class="bi bi-chevron-right me-1"></i>Home</a></li>
          @php $footerCategories = \App\Models\Category::orderBy('name')->limit(5)->get(); @endphp
          @foreach ($footerCategories as $cat)
            <li class="mb-2">
              <a href="{{ route('category.show', $cat->slug) }}">
                <i class="bi bi-chevron-right me-1"></i>{{ $cat->name }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Contact --}}
      <div class="col-lg-4 mb-4">
        <h5>Kontak</h5>
        <ul class="list-unstyled">
          <li class="mb-2">
            <i class="bi bi-globe me-2"></i>
            <a href="https://heyfikriamrullah.com" target="_blank">heyfikriamrullah.com</a>
          </li>
          <li class="mb-2">
            <i class="bi bi-github me-2"></i>
            <a href="https://github.com/massiki" target="_blank">GitHub</a>
          </li>
          <li class="mb-2">
            <i class="bi bi-linkedin me-2"></i>
            <a href="https://www.linkedin.com/in/fikri-amrullah-5583b52b6/" target="_blank">LinkedIn</a>
          </li>
          <li class="mb-2">
            <i class="bi bi-instagram me-2"></i>
            <a href="https://www.instagram.com/fikri.amrulloh.15/" target="_blank">Instagram</a>
          </li>
          <li class="mb-2">
            <i class="bi bi-whatsapp me-2"></i>
            <a href="https://wa.me/6285294532451" target="_blank">Whatsapp</a>
          </li>

        </ul>
      </div>
    </div>

    {{-- Bottom --}}
    <div class="footer-bottom text-center">
      <p class="mb-0">&copy; {{ date('Y') }} MyBlog. Made with <i class="bi bi-heart-fill text-danger"></i> by
        Fikri Amrullah</p>
    </div>
  </div>
</footer>
