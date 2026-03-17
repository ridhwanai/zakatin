<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div>
                <span class="badge rounded-pill border text-dark-emphasis fw-medium mb-2">Zakat {{ $currentHijriYear }} H</span>
                <h1 class="h2 fw-bold mb-2">Panel Zakat</h1>
                <p class="text-muted mb-0">Lihat ringkasan project terlebih dahulu, lalu login saat siap menambahkan data.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if ($isAuthenticated)
                    <form method="POST" action="{{ route('projects.store') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-dark rounded-pill px-4">
                            Tambah Project
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark rounded-pill px-4">
                        Login untuk Tambah Project
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="container py-4 projects-index-page">

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Total Project</p>
                        <h2 class="h3 fw-bold mb-0">{{ number_format($projects->count()) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Mode Akses</p>
                        <h2 class="h6 fw-bold mb-0">{{ $isAuthenticated ? 'Panel Personal' : 'Preview Panel' }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Status Akun</p>
                        <h2 class="h6 fw-bold mb-0">
                            {{ $isAuthenticated ? 'Aktif' : 'Belum Login' }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Tahun Hijriah</p>
                        <h2 class="h3 fw-bold mb-0">{{ $currentHijriYear }}</h2>
                    </div>
                </div>
            </div>
        </div>

        @if (! $isAuthenticated)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-lg-8">
                            <h2 class="h4 fw-bold mb-2">Preview Panel Siap Digunakan</h2>
                            <p class="text-muted mb-0">
                                Kamu sudah bisa melihat struktur dashboard sekarang. Untuk membuat project, menginput data zakat, dan mengunduh PDF, cukup login terlebih dahulu.
                            </p>
                        </div>
                        <div class="col-12 col-lg-4 text-lg-end">
                            <div class="d-flex justify-content-lg-end gap-2">
                                <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4">Log in</a>
                                <a href="{{ route('register') }}" class="btn btn-dark rounded-pill px-4">Sign Up</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h2 class="h5 mb-1">{{ $isAuthenticated ? 'Daftar Project Zakat' : 'Tampilan Project (Preview)' }}</h2>
                <p class="text-muted mb-0 small">
                    {{ $isAuthenticated ? 'Project milik akun Anda.' : 'Login untuk mulai membuat dan mengelola project pribadi.' }}
                </p>
            </div>
            <div class="card-body p-4">
                @if ($projects->isEmpty())
                    <div class="text-center py-5">
                        <h3 class="h6 fw-bold mb-2">
                            {{ $isAuthenticated ? 'Belum ada project zakat' : 'Belum ada data project di mode preview' }}
                        </h3>
                        <p class="text-muted mb-4">
                            {{ $isAuthenticated ? 'Mulai dengan membuat project baru untuk tahun Hijriah saat ini.' : 'Silakan login untuk membuat project pertama Anda.' }}
                        </p>
                        @if ($isAuthenticated)
                            <form method="POST" action="{{ route('projects.store') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-dark rounded-pill px-4">Buat Project Pertama</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-dark rounded-pill px-4">Login untuk Mulai</a>
                        @endif
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($projects as $project)
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="card h-100 border-0 shadow-sm project-item-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h3 class="h6 fw-semibold mb-0">{{ $project->title }}</h3>
                                            <span class="badge rounded-pill text-bg-{{ $project->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </div>

                                        <p class="text-muted mb-1 small">Tahun Hijriah: <strong>{{ $project->hijri_year }} H</strong></p>
                                        <p class="text-muted mb-4 small">Jumlah Record: <strong>{{ $project->zakat_records_count }}</strong></p>

                                        @if ($isAuthenticated)
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Lihat Detail</a>
                                                <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Hapus project ini? Semua data transaksi akan ikut terhapus.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">Hapus</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@push('head')
    <style>
        .projects-index-page .project-item-card {
            background: linear-gradient(145deg, #dcefe2 0%, #c5e2ce 100%);
            border: 1px solid rgba(31, 91, 69, 0.24) !important;
            box-shadow: 0 10px 22px rgba(20, 54, 40, 0.08) !important;
        }

        .projects-index-page .project-item-card h3,
        .projects-index-page .project-item-card p,
        .projects-index-page .project-item-card strong {
            color: #234a3a;
        }

        .projects-index-page .project-item-card .text-muted {
            color: #2f5c49 !important;
        }
    </style>
@endpush
</x-app-layout>

