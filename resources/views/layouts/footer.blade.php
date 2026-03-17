<footer class="site-footer mt-5 border-top">
    <div class="container py-4">
        <div class="row gy-4">
            <div class="col-md-5">
                <a href="{{ route('dashboard') }}" class="site-footer-brand text-decoration-none">Zakatin.</a>
                <p class="text-muted small mt-2 mb-0">
                    Platform manajemen zakat untuk pencatatan, kalkulasi, dan monitoring distribusi yang lebih rapi.
                </p>
            </div>
            <div class="col-6 col-md-3">
                <h6 class="site-footer-heading">Menu</h6>
                <ul class="list-unstyled mb-0 d-grid gap-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="site-footer-link">Panel Zakat</a>
                    </li>
                    <li>
                        <a href="{{ route('zakat.calculator') }}" class="site-footer-link">Kalkulator Zakat</a>
                    </li>
                </ul>
            </div>
            <div class="col-6 col-md-4">
                <h6 class="site-footer-heading">Kontak</h6>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a
                        class="site-footer-social"
                        href="https://www.instagram.com/ridwann_ai"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram Ridwann AI"
                    >
                        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                    </a>
                    <span class="site-footer-social is-disabled" aria-label="WhatsApp segera hadir" title="WhatsApp segera hadir">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    </span>
                    <span class="site-footer-social is-disabled" aria-label="Email segera hadir" title="Email segera hadir">
                        <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                    </span>
                </div>
                <p class="text-muted small mb-0">WhatsApp dan Email akan segera ditambahkan.</p>
            </div>
        </div>

        <div class="border-top mt-4 pt-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <p class="text-muted small mb-0">&copy; {{ now()->year }} Zakatin. All rights reserved.</p>
            <p class="text-muted small mb-0">Dibangun untuk pengelolaan zakat yang profesional.</p>
        </div>
    </div>
</footer>
