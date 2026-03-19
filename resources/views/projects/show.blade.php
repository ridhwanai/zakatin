<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2 gap-lg-3">
            <div>
                <h1 class="h4 fw-bold mb-1">{{ $project->title }}</h1>
                <p class="text-muted mb-0">Kelola rincian project, nominal zakat, dan daftar orang yang sudah zakat.</p>
            </div>
            <div class="project-header-actions d-flex flex-wrap justify-content-start justify-content-lg-end gap-2">
                <a href="{{ route('dashboard') }}" class="project-header-btn btn btn-outline-dark rounded-pill">
                    <i class="fa-solid fa-arrow-left"></i>Kembali
                </a>
                <a href="{{ route('projects.pdf', $project) }}" class="project-header-btn btn btn-dark rounded-pill">
                    <i class="fa-solid fa-file-pdf"></i>Download PDF
                </a>
                <a href="{{ route('projects.excel', $project) }}" class="project-header-btn btn btn-success rounded-pill">
                    <i class="fa-solid fa-file-excel"></i>Download Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container py-4 project-detail-shell">

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-4 col-xl-2">
                <div class="summary-stat-card h-100">
                    <div class="summary-icon"><i class="fa-solid fa-list"></i></div>
                    <p class="summary-label">Total Pendaftar</p>
                    <h2 class="summary-value">{{ number_format($summary['total_list_count']) }}</h2>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xl-2">
                <div class="summary-stat-card h-100">
                    <div class="summary-icon"><i class="fa-solid fa-users"></i></div>
                    <p class="summary-label">Total Muzakki</p>
                    <h2 class="summary-value">{{ number_format($summary['total_people']) }}</h2>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xl-2">
                <div class="summary-stat-card h-100">
                    <div class="summary-icon"><i class="fa-solid fa-wheat-awn"></i></div>
                    <p class="summary-label">Total Fitrah (Beras)</p>
                    <h2 class="summary-value">{{ number_format($summary['total_rice_kg'], 2, ',', '.') }} Kg</h2>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xl-2">
                <div class="summary-stat-card h-100">
                    <div class="summary-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                    <p class="summary-label">Total Fitrah (Uang)</p>
                    <h2 class="summary-value">Rp {{ number_format($summary['total_fitrah_money'], 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xl-2">
                <div class="summary-stat-card h-100">
                    <div class="summary-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <p class="summary-label">Total Wajib / Infaq</p>
                    <h2 class="summary-value">Rp {{ number_format($summary['total_wajib_money'], 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xl-2">
                <div class="summary-stat-card h-100">
                    <div class="summary-icon"><i class="fa-solid fa-coins"></i></div>
                    <p class="summary-label">Total Zakat Mal</p>
                    <h2 class="summary-value">Rp {{ number_format($summary['total_mal_money'], 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h2 class="h6 fw-semibold mb-1"><i class="fa-solid fa-sliders me-2 text-primary"></i>Set Nominal Zakat per Orang</h2>
                        <p class="text-muted small mb-0">Tentukan nominal default agar input transaksi lebih cepat.</p>
                    </div>
                    <div class="card-body pt-0 px-4 pb-4">
                        <form method="POST" action="{{ route('projects.rates.update', $project) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="rice_rate_per_person" class="form-label">Beras per Orang (Kg)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    id="rice_rate_per_person"
                                    name="rice_rate_per_person"
                                    value="{{ old('rice_rate_per_person', $project->rice_rate_per_person) }}"
                                    class="form-control @error('rice_rate_per_person') is-invalid @enderror"
                                    placeholder="Contoh: 2.50"
                                >
                                @error('rice_rate_per_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="money_rate_per_person" class="form-label">Uang per Orang (Rp)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    id="money_rate_per_person"
                                    name="money_rate_per_person"
                                    value="{{ old('money_rate_per_person', $project->money_rate_per_person) }}"
                                    class="form-control @error('money_rate_per_person') is-invalid @enderror"
                                    placeholder="Contoh: 35000"
                                >
                                @error('money_rate_per_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-dark rounded-pill px-4 w-100">Simpan Nominal</button>

                            <p class="text-muted small mt-3 mb-0">
                                Kosongkan nilai jika ingin input manual pada metode terkait.
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h2 class="h6 fw-semibold mb-1"><i class="fa-solid fa-file-pen me-2 text-primary"></i>Input Data Zakat</h2>
                        <p class="text-muted small mb-0">Isi data per nama/keluarga untuk dicatat ke project ini.</p>
                    </div>
                    <div class="card-body pt-0 px-4 pb-4">
                        <form method="POST" action="{{ route('records.store', $project) }}" id="zakat-form" novalidate>
                            @csrf
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">Nama Pembayar</label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        required
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="people_count" class="form-label">Jumlah Orang</label>
                                    <input
                                        type="number"
                                        id="people_count"
                                        name="people_count"
                                        min="1"
                                        value="{{ old('people_count', 1) }}"
                                        class="form-control @error('people_count') is-invalid @enderror"
                                        required
                                    >
                                    @error('people_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="method" class="form-label">Metode</label>
                                    <select id="method" name="method" class="form-select @error('method') is-invalid @enderror" required>
                                        <option value="rice" @selected(old('method', 'rice') === 'rice')>Beras</option>
                                        <option value="money" @selected(old('method') === 'money')>Tunai (Uang)</option>
                                    </select>
                                    @error('method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4" id="rice-group">
                                    <label for="rice_kg" class="form-label">Total Fitrah Beras (Kg)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="rice_kg"
                                        name="rice_kg"
                                        value="{{ old('rice_kg') }}"
                                        class="form-control @error('rice_kg') is-invalid @enderror"
                                    >
                                    <small id="rice-help" class="text-muted d-block mt-1"></small>
                                    @error('rice_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4" id="money-group">
                                    <label for="fitrah_money" class="form-label">Total Fitrah Uang (Rp)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="fitrah_money"
                                        name="fitrah_money"
                                        value="{{ old('fitrah_money') }}"
                                        class="form-control @error('fitrah_money') is-invalid @enderror"
                                    >
                                    <small id="money-help" class="text-muted d-block mt-1"></small>
                                    @error('fitrah_money')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="wajib_money" class="form-label">Uang Wajib / Infaq (Rp)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="wajib_money"
                                        name="wajib_money"
                                        value="{{ old('wajib_money') }}"
                                        class="form-control @error('wajib_money') is-invalid @enderror"
                                    >
                                    @error('wajib_money')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="mal_money" class="form-label">Total Zakat Mal (Rp)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        id="mal_money"
                                        name="mal_money"
                                        value="{{ old('mal_money') }}"
                                        class="form-control @error('mal_money') is-invalid @enderror"
                                    >
                                    @error('mal_money')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-dark rounded-pill px-4">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h2 class="h6 fw-semibold mb-1"><i class="fa-solid fa-table-list me-2 text-primary"></i>Daftar Orang Sudah Zakat</h2>
                        <p class="text-muted small mb-0">Gunakan pencarian dan filter untuk menemukan data lebih cepat.</p>
                    </div>
                </div>
            </div>
            <div class="card-body border-top border-bottom px-4 py-3">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-5">
                        <label for="table-search" class="form-label">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input
                                type="text"
                                id="table-search"
                                class="form-control"
                                placeholder="Cari nama, metode, atau angka..."
                            >
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="table-method-filter" class="form-label">Filter Metode</label>
                        <select id="table-method-filter" class="form-select">
                            <option value="">Semua Metode</option>
                            <option value="rice">Beras</option>
                            <option value="money">Tunai (Uang)</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="table-sort-order" class="form-label">Urutan</label>
                        <select id="table-sort-order" class="form-select">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 detail-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Orang</th>
                            <th>Metode</th>
                            <th>Beras (Kg)</th>
                            <th>UANG (RP)</th>
                            <th>WAJIB / INFAQ (RP)</th>
                            <th>ZAKAT MAL (RP)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="zakat-table-body">
                        @forelse ($project->zakatRecords as $record)
                            <tr
                                class="zakat-row"
                                data-record-id="{{ $record->id }}"
                                data-method="{{ $record->method }}"
                                data-name="{{ $record->name }}"
                                data-people-count="{{ $record->people_count }}"
                                data-rice-kg="{{ $record->rice_kg ?? '' }}"
                                data-fitrah-money="{{ $record->fitrah_money ?? '' }}"
                                data-wajib-money="{{ $record->wajib_money }}"
                                data-mal-money="{{ $record->mal_money }}"
                            >
                                <td class="row-number">{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $record->name }}</td>
                                <td>{{ number_format($record->people_count) }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $record->method === 'rice' ? 'text-bg-warning' : 'text-bg-primary' }}">
                                        {{ $record->method === 'rice' ? 'Beras' : 'Tunai' }}
                                    </span>
                                </td>
                                <td>{{ $record->rice_kg !== null ? number_format((float) $record->rice_kg, 2, ',', '.') : '-' }}</td>
                                <td>{{ $record->fitrah_money !== null ? number_format((float) $record->fitrah_money, 0, ',', '.') : '-' }}</td>
                                <td>{{ number_format((float) $record->wajib_money, 0, ',', '.') }}</td>
                                <td>{{ number_format((float) $record->mal_money, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <button
                                            type="button"
                                            class="btn btn-outline-dark btn-sm action-icon-btn js-edit-record"
                                            title="Edit"
                                            aria-label="Edit {{ $record->name }}"
                                        >
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form
                                            method="POST"
                                            action="{{ route('records.destroy', ['project' => $project, 'record' => $record]) }}"
                                            onsubmit="return confirm('Hapus data ini?');"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger btn-sm action-icon-btn"
                                                title="Hapus"
                                                aria-label="Hapus {{ $record->name }}"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-data-row">
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data orang zakat.</td>
                            </tr>
                        @endforelse
                        <tr id="no-result-row" style="display: none;">
                            <td colspan="9" class="text-center text-muted py-4">Tidak ada data yang cocok dengan filter/pencarian.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-sm">
                    <form method="POST" id="edit-record-form">
                        @csrf
                        @method('PATCH')

                        <div class="modal-header border-0 pb-0">
                            <h2 class="modal-title h5 mb-0" id="editRecordModalLabel">Edit Data Zakat</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body pt-3">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="edit_name" class="form-label">Nama Pembayar</label>
                                    <input type="text" id="edit_name" name="name" class="form-control" required>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="edit_people_count" class="form-label">Jumlah Orang</label>
                                    <input type="number" id="edit_people_count" name="people_count" min="1" class="form-control" required>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="edit_method" class="form-label">Metode</label>
                                    <select id="edit_method" name="method" class="form-select" required>
                                        <option value="rice">Beras</option>
                                        <option value="money">Tunai (Uang)</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4" id="edit-rice-group">
                                    <label for="edit_rice_kg" class="form-label">Total Fitrah Beras (Kg)</label>
                                    <input type="number" step="0.01" min="0" id="edit_rice_kg" name="rice_kg" class="form-control">
                                    <small id="edit-rice-help" class="text-muted d-block mt-1"></small>
                                </div>

                                <div class="col-12 col-md-4" id="edit-money-group">
                                    <label for="edit_fitrah_money" class="form-label">Total Fitrah Uang (Rp)</label>
                                    <input type="number" step="0.01" min="0" id="edit_fitrah_money" name="fitrah_money" class="form-control">
                                    <small id="edit-money-help" class="text-muted d-block mt-1"></small>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="edit_wajib_money" class="form-label">Uang Wajib / Infaq (Rp)</label>
                                    <input type="number" step="0.01" min="0" id="edit_wajib_money" name="wajib_money" class="form-control">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="edit_mal_money" class="form-label">Total Zakat Mal (Rp)</label>
                                    <input type="number" step="0.01" min="0" id="edit_mal_money" name="mal_money" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .project-header-actions .project-header-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.92rem;
            font-weight: 600;
            line-height: 1.2;
            padding: 0.5rem 0.95rem;
            border-width: 1px;
        }

        .project-header-actions .project-header-btn i {
            font-size: 0.78rem;
        }

        @media (max-width: 991.98px) {
            .project-header-actions .project-header-btn {
                padding: 0.45rem 0.8rem;
                font-size: 0.86rem;
            }
        }

        .project-detail-shell .summary-stat-card {
            border: 1px solid rgba(31, 91, 69, 0.2);
            background: linear-gradient(165deg, rgba(255, 255, 255, 0.97) 0%, rgba(249, 244, 232, 0.96) 100%);
            border-radius: 1rem;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            box-shadow: 0 12px 25px rgba(20, 66, 46, 0.08);
        }

        .project-detail-shell .summary-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(31, 91, 69, 0.16) 0%, rgba(217, 180, 93, 0.28) 100%);
            color: #1f5b45;
            font-size: 0.9rem;
        }

        .project-detail-shell .summary-label {
            color: #5f6f66;
            font-size: 0.78rem;
            margin-bottom: 0;
        }

        .project-detail-shell .summary-value {
            font-size: 1.05rem;
            margin: 0;
            font-weight: 700;
            color: #173227;
            line-height: 1.3;
        }

        .project-detail-shell .detail-table thead th {
            background: rgba(31, 91, 69, 0.08);
            color: #214535;
            border-bottom: 1px solid rgba(31, 91, 69, 0.24);
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }

        .project-detail-shell .detail-table tbody td {
            border-color: rgba(31, 91, 69, 0.11);
            color: #1f2f28;
            font-size: 0.92rem;
        }

        .project-detail-shell .action-icon-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const methodSelect = document.getElementById('method');
            const peopleCountInput = document.getElementById('people_count');
            const riceGroup = document.getElementById('rice-group');
            const moneyGroup = document.getElementById('money-group');
            const riceInput = document.getElementById('rice_kg');
            const moneyInput = document.getElementById('fitrah_money');
            const riceHelp = document.getElementById('rice-help');
            const moneyHelp = document.getElementById('money-help');
            const tableSearchInput = document.getElementById('table-search');
            const tableMethodFilter = document.getElementById('table-method-filter');
            const tableSortOrder = document.getElementById('table-sort-order');
            const tableBody = document.getElementById('zakat-table-body');
            const zakatRows = Array.from(document.querySelectorAll('tr.zakat-row'));
            const noResultRow = document.getElementById('no-result-row');
            const editRecordModalEl = document.getElementById('editRecordModal');
            const editRecordForm = document.getElementById('edit-record-form');
            const editNameInput = document.getElementById('edit_name');
            const editPeopleCountInput = document.getElementById('edit_people_count');
            const editMethodSelect = document.getElementById('edit_method');
            const editRiceGroup = document.getElementById('edit-rice-group');
            const editMoneyGroup = document.getElementById('edit-money-group');
            const editRiceInput = document.getElementById('edit_rice_kg');
            const editMoneyInput = document.getElementById('edit_fitrah_money');
            const editWajibInput = document.getElementById('edit_wajib_money');
            const editMalInput = document.getElementById('edit_mal_money');
            const editRiceHelp = document.getElementById('edit-rice-help');
            const editMoneyHelp = document.getElementById('edit-money-help');
            const editButtons = Array.from(document.querySelectorAll('.js-edit-record'));

            const riceRatePerPerson = Number.parseFloat(@json($project->rice_rate_per_person));
            const moneyRatePerPerson = Number.parseFloat(@json($project->money_rate_per_person));
            const updateRecordRouteTemplate = @json(route('records.update', ['project' => $project, 'record' => '__RECORD__']));

            const hasRiceRate = Number.isFinite(riceRatePerPerson);
            const hasMoneyRate = Number.isFinite(moneyRatePerPerson);
            const editModal = editRecordModalEl ? new bootstrap.Modal(editRecordModalEl) : null;

            function getPeopleCount() {
                const parsed = Number.parseInt(peopleCountInput.value, 10);

                if (Number.isNaN(parsed) || parsed < 1) {
                    return 1;
                }

                return parsed;
            }

            function calculatedRice(peopleCount) {
                return (peopleCount * riceRatePerPerson).toFixed(2);
            }

            function calculatedMoney(peopleCount) {
                return (peopleCount * moneyRatePerPerson).toFixed(2);
            }

            function parseRecordValue(value) {
                if (value === undefined || value === null || value === '') {
                    return '';
                }

                return String(value);
            }

            function refreshHelperText() {
                if (hasRiceRate) {
                    riceHelp.textContent = `Otomatis: jumlah orang x ${riceRatePerPerson} kg`;
                } else {
                    riceHelp.textContent = 'Nominal beras per orang belum diset. Isi manual.';
                }

                if (hasMoneyRate) {
                    moneyHelp.textContent = `Otomatis: jumlah orang x Rp ${moneyRatePerPerson}`;
                } else {
                    moneyHelp.textContent = 'Nominal uang per orang belum diset. Isi manual.';
                }
            }

            function refreshEditHelperText() {
                if (hasRiceRate) {
                    editRiceHelp.textContent = `Otomatis: jumlah orang x ${riceRatePerPerson} kg`;
                } else {
                    editRiceHelp.textContent = 'Nominal beras per orang belum diset. Isi manual.';
                }

                if (hasMoneyRate) {
                    editMoneyHelp.textContent = `Otomatis: jumlah orang x Rp ${moneyRatePerPerson}`;
                } else {
                    editMoneyHelp.textContent = 'Nominal uang per orang belum diset. Isi manual.';
                }
            }

            function syncPaymentFields(forceRecalculate) {
                const isRice = methodSelect.value === 'rice';
                const peopleCount = getPeopleCount();

                riceGroup.style.display = isRice ? '' : 'none';
                moneyGroup.style.display = isRice ? 'none' : '';

                riceInput.disabled = !isRice;
                moneyInput.disabled = isRice;

                if (isRice) {
                    moneyInput.value = '';
                    moneyInput.required = false;
                    moneyInput.readOnly = false;

                    if (hasRiceRate) {
                        riceInput.readOnly = true;
                        riceInput.required = false;

                        if (forceRecalculate || riceInput.value === '') {
                            riceInput.value = calculatedRice(peopleCount);
                        }
                    } else {
                        riceInput.readOnly = false;
                        riceInput.required = true;
                    }
                } else {
                    riceInput.value = '';
                    riceInput.required = false;
                    riceInput.readOnly = false;

                    if (hasMoneyRate) {
                        moneyInput.readOnly = true;
                        moneyInput.required = false;

                        if (forceRecalculate || moneyInput.value === '') {
                            moneyInput.value = calculatedMoney(peopleCount);
                        }
                    } else {
                        moneyInput.readOnly = false;
                        moneyInput.required = true;
                    }
                }
            }

            function getEditPeopleCount() {
                const parsed = Number.parseInt(editPeopleCountInput.value, 10);

                if (Number.isNaN(parsed) || parsed < 1) {
                    return 1;
                }

                return parsed;
            }

            function syncEditPaymentFields(forceRecalculate) {
                const isRice = editMethodSelect.value === 'rice';
                const peopleCount = getEditPeopleCount();

                editRiceGroup.style.display = isRice ? '' : 'none';
                editMoneyGroup.style.display = isRice ? 'none' : '';

                editRiceInput.disabled = !isRice;
                editMoneyInput.disabled = isRice;

                if (isRice) {
                    editMoneyInput.value = '';
                    editMoneyInput.required = false;
                    editMoneyInput.readOnly = false;

                    if (hasRiceRate) {
                        editRiceInput.readOnly = true;
                        editRiceInput.required = false;

                        if (forceRecalculate || editRiceInput.value === '') {
                            editRiceInput.value = calculatedRice(peopleCount);
                        }
                    } else {
                        editRiceInput.readOnly = false;
                        editRiceInput.required = true;
                    }
                } else {
                    editRiceInput.value = '';
                    editRiceInput.required = false;
                    editRiceInput.readOnly = false;

                    if (hasMoneyRate) {
                        editMoneyInput.readOnly = true;
                        editMoneyInput.required = false;

                        if (forceRecalculate || editMoneyInput.value === '') {
                            editMoneyInput.value = calculatedMoney(peopleCount);
                        }
                    } else {
                        editMoneyInput.readOnly = false;
                        editMoneyInput.required = true;
                    }
                }
            }

            function openEditRecordModal(row) {
                if (!row || !editRecordForm || !editModal) {
                    return;
                }

                const recordId = row.dataset.recordId;
                if (!recordId) {
                    return;
                }

                editRecordForm.action = updateRecordRouteTemplate.replace('__RECORD__', recordId);
                editNameInput.value = parseRecordValue(row.dataset.name);
                editPeopleCountInput.value = parseRecordValue(row.dataset.peopleCount || '1');
                editMethodSelect.value = parseRecordValue(row.dataset.method || 'rice');
                editRiceInput.value = parseRecordValue(row.dataset.riceKg);
                editMoneyInput.value = parseRecordValue(row.dataset.fitrahMoney);
                editWajibInput.value = parseRecordValue(row.dataset.wajibMoney);
                editMalInput.value = parseRecordValue(row.dataset.malMoney);

                refreshEditHelperText();
                syncEditPaymentFields(true);
                editModal.show();
            }

            function applyTableFilter() {
                if (zakatRows.length === 0) {
                    return;
                }

                const searchKeyword = tableSearchInput.value.trim().toLowerCase();
                const selectedMethod = tableMethodFilter.value;
                const selectedOrder = tableSortOrder.value;
                let visibleCount = 0;

                const orderedRows = [...zakatRows].sort((a, b) => {
                    const orderA = Number.parseInt(a.dataset.rowOrder, 10);
                    const orderB = Number.parseInt(b.dataset.rowOrder, 10);

                    return selectedOrder === 'oldest' ? orderB - orderA : orderA - orderB;
                });

                orderedRows.forEach((row) => {
                    const rowText = row.textContent.toLowerCase();
                    const rowMethod = row.dataset.method;
                    const matchSearch = searchKeyword === '' || rowText.includes(searchKeyword);
                    const matchMethod = selectedMethod === '' || rowMethod === selectedMethod;
                    const isVisible = matchSearch && matchMethod;

                    row.style.display = isVisible ? '' : 'none';

                    if (isVisible) {
                        visibleCount += 1;
                        const numberCell = row.querySelector('.row-number');

                        if (numberCell) {
                            numberCell.textContent = String(visibleCount);
                        }
                    }

                    if (tableBody) {
                        tableBody.appendChild(row);
                    }
                });

                if (noResultRow) {
                    noResultRow.style.display = visibleCount === 0 ? '' : 'none';

                    if (tableBody) {
                        tableBody.appendChild(noResultRow);
                    }
                }
            }

            methodSelect.addEventListener('change', function () {
                syncPaymentFields(true);
            });

            peopleCountInput.addEventListener('input', function () {
                syncPaymentFields(true);
            });

            if (editMethodSelect && editPeopleCountInput) {
                editMethodSelect.addEventListener('change', function () {
                    syncEditPaymentFields(true);
                });

                editPeopleCountInput.addEventListener('input', function () {
                    syncEditPaymentFields(true);
                });
            }

            editButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    const row = button.closest('tr.zakat-row');
                    openEditRecordModal(row);
                });
            });

            if (tableSearchInput && tableMethodFilter && tableSortOrder) {
                zakatRows.forEach((row, index) => {
                    row.dataset.rowOrder = String(index);
                });

                tableSearchInput.addEventListener('input', applyTableFilter);
                tableMethodFilter.addEventListener('change', applyTableFilter);
                tableSortOrder.addEventListener('change', applyTableFilter);
            }

            refreshHelperText();
            refreshEditHelperText();
            syncPaymentFields(true);
            applyTableFilter();
        });
    </script>
</x-app-layout>

