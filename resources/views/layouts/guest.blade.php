<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Zakatin') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
            crossorigin="anonymous"
        >
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
            crossorigin="anonymous"
        >
        @include('layouts.partials.theme-styles')
    </head>
    <body class="site-body">
        @include('layouts.navigation')

        <main class="container guest-shell">
            <div class="row justify-content-end w-100 g-4">
                <div class="col-12 col-lg-6 d-none d-lg-flex align-items-center">
                    <div>
                        <span class="guest-intro-badge mb-3">Sistem Zakat Digital</span>
                        <h1 class="display-5 fw-bold mb-3">Kelola Zakat Lebih Tertata</h1>
                        <p class="text-muted mb-0">
                            Masuk ke Zakatin untuk mengelola project zakat per tahun Hijriah, validasi data, dan laporan PDF dalam satu dashboard.
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="guest-card p-4 p-md-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.partials.toasts')
        @include('layouts.footer')

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
