<section class="container py-4 calculator-shell">
    @if (! $isAuthenticated)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5 text-center">
                <h1 class="h4 fw-bold mb-2">Kalkulator Zakat Berbasis Project</h1>
                <p class="text-muted mb-4">
                    Silakan login dulu agar kalkulator bisa mengambil total zakat dari project Anda.
                </p>
                <a href="{{ route('login') }}" class="btn btn-dark rounded-pill px-4">Login</a>
            </div>
        </div>
    @elseif (count($projectPayload) === 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5 text-center">
                <h1 class="h4 fw-bold mb-2">Belum Ada Project Zakat</h1>
                <p class="text-muted mb-4">
                    Buat project dulu dari dashboard, lalu kembali ke menu ini untuk simulasi pembagian zakat.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-dark rounded-pill px-4">Ke Dashboard</a>
            </div>
        </div>
    @else
        <div
            id="zakatProjectCalculator"
            data-selected-project-id="{{ $selectedProjectId }}"
            data-project-url-template="{{ route('projects.show', ['project' => '__PROJECT__']) }}"
        >
            <div class="row g-4">
                <div class="col-12 col-xl-5">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                            <span class="badge rounded-pill border text-dark-emphasis fw-medium mb-2">Sumber Data</span>
                            <h1 class="h5 fw-bold mb-1">Project Zakat</h1>
                            <p class="text-muted small mb-0">Pilih project agar semua total terhubung otomatis.</p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="mb-3">
                                <label for="projectSelect" class="form-label">Pilih Project</label>
                                <select id="projectSelect" class="form-select">
                                    @foreach ($projectPayload as $projectItem)
                                        <option
                                            value="{{ $projectItem['id'] }}"
                                            @selected($projectItem['id'] === $selectedProjectId)
                                        >
                                            {{ $projectItem['title'] }} ({{ $projectItem['hijri_year'] }} H)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="project-info-box">
                                <p class="project-title mb-1" id="selectedProjectTitle">-</p>
                                <p class="project-meta mb-2" id="selectedProjectMeta">-</p>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <span class="badge rounded-pill text-bg-light border" id="selectedProjectStatus">-</span>
                                    <a id="selectedProjectLink" href="#" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                        Buka Detail Project
                                    </a>
                                </div>
                            </div>

                            <div class="summary-grid mt-3">
                                <div class="summary-grid-item">
                                    <span class="summary-grid-label">Total Pendaftar</span>
                                    <strong id="summaryListCount">0</strong>
                                    <small id="summaryListBreakdown">Beras 0 | Uang 0 | Custom 0 | Mal 0</small>
                                </div>
                                <div class="summary-grid-item">
                                    <span class="summary-grid-label">Total Muzakki</span>
                                    <strong id="summaryPeopleCount">0</strong>
                                    <small id="summaryPeopleBreakdown">Beras 0 | Uang 0 | Custom 0 | Mal 0</small>
                                </div>
                                <div class="summary-grid-item">
                                    <span class="summary-grid-label">Fitrah Beras</span>
                                    <strong id="summaryRiceKg">0 Kg</strong>
                                </div>
                                <div class="summary-grid-item">
                                    <span class="summary-grid-label">Fitrah Uang</span>
                                    <strong id="summaryFitrahMoney">Rp 0</strong>
                                </div>
                                <div class="summary-grid-item">
                                    <span class="summary-grid-label">Wajib / Infaq</span>
                                    <strong id="summaryWajibMoney">Rp 0</strong>
                                </div>
                                <div class="summary-grid-item">
                                    <span class="summary-grid-label">Zakat Mal</span>
                                    <strong id="summaryMalMoney">Rp 0</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                            <h2 class="h6 fw-semibold mb-1">Parameter Simulasi</h2>
                            <p class="text-muted small mb-0">Semua hasil di kanan akan berubah sesuai parameter ini.</p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="mb-3">
                                <label for="ricePricePerKg" class="form-label">Harga Beras per Kg (Custom)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input
                                        type="number"
                                        id="ricePricePerKg"
                                        class="form-control"
                                        min="1"
                                        step="100"
                                        value="15000"
                                    >
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="fitrahRecipients" class="form-label">Penerima Zakat Fitrah</label>
                                <input
                                    type="number"
                                    id="fitrahRecipients"
                                    class="form-control"
                                    min="1"
                                    step="1"
                                    value="8"
                                >
                            </div>

                            <div class="mb-3">
                                <label for="malRecipients" class="form-label">Penerima Zakat Mal</label>
                                <input
                                    type="number"
                                    id="malRecipients"
                                    class="form-control"
                                    min="1"
                                    step="1"
                                    value="8"
                                >
                            </div>

                            <div class="mb-3">
                                <label for="combinedRecipients" class="form-label">Penerima Dana Zakat Gabungan</label>
                                <input
                                    type="number"
                                    id="combinedRecipients"
                                    class="form-control"
                                    min="1"
                                    step="1"
                                    value="8"
                                >
                            </div>

                            <button type="button" id="resetCalculatorParams" class="btn btn-outline-danger rounded-pill px-4">
                                Reset Parameter
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                            <h2 class="h5 fw-bold mb-1">Hasil Kalkulasi Zakat</h2>
                            <p class="text-muted small mb-0">
                                Konversi dan simulasi pembagian otomatis berdasarkan total dari project terpilih.
                            </p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="calc-section">
                                <h3 class="calc-section-title">1) Konversi Fitrah Uang ke Beras</h3>
                                <div class="result-list">
                                    <div class="result-item">
                                        <span>Total Fitrah Uang</span>
                                        <strong id="resultFitrahMoney">Rp 0</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Harga Beras per Kg</span>
                                        <strong id="resultRicePrice">Rp 0</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Setara Beras dari Fitrah Uang</span>
                                        <strong id="resultFitrahMoneyToRice">0 Kg</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Total Fitrah Setara Beras (Beras + Konversi Uang)</span>
                                        <strong id="resultFitrahCombinedRice">0 Kg</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="calc-section mt-4">
                                <h3 class="calc-section-title">2) Simulasi Bagi Zakat Fitrah</h3>
                                <div class="result-list">
                                    <div class="result-item">
                                        <span>Jumlah Penerima</span>
                                        <strong id="resultFitrahRecipients">0 Orang</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Dapat Beras Langsung</span>
                                        <strong id="resultFitrahRicePerRecipient">0 Kg</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Dapat Uang Fitrah</span>
                                        <strong id="resultFitrahMoneyPerRecipient">Rp 0</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Setara Beras dari Uang</span>
                                        <strong id="resultFitrahMoneyRicePerRecipient">0 Kg</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Total Setara Beras</span>
                                        <strong id="resultFitrahCombinedRicePerRecipient">0 Kg</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="calc-section mt-4">
                                <h3 class="calc-section-title">3) Simulasi Bagi Zakat Mal</h3>
                                <div class="result-list">
                                    <div class="result-item">
                                        <span>Jumlah Penerima</span>
                                        <strong id="resultMalRecipients">0 Orang</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Dapat Zakat Mal (Uang)</span>
                                        <strong id="resultMalPerRecipientMoney">Rp 0</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Setara Beras dari Zakat Mal</span>
                                        <strong id="resultMalPerRecipientRice">0 Kg</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="calc-section mt-4">
                                <h3 class="calc-section-title">4) Dana Zakat Gabungan (Fitrah Uang + Wajib/Infaq + Mal)</h3>
                                <div class="result-list">
                                    <div class="result-item">
                                        <span>Total Dana Zakat Gabungan</span>
                                        <strong id="resultCombinedTotalMoney">Rp 0</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Jumlah Penerima</span>
                                        <strong id="resultCombinedRecipients">0 Orang</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Dapat Dana Gabungan</span>
                                        <strong id="resultCombinedPerRecipientMoney">Rp 0</strong>
                                    </div>
                                    <div class="result-item">
                                        <span>Per Orang Setara Beras dari Dana Gabungan</span>
                                        <strong id="resultCombinedPerRecipientRice">0 Kg</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="application/json" id="calculatorProjectData">@json($projectPayload)</script>
    @endif
</section>

<style>
    .calculator-shell .project-info-box {
        border: 1px solid rgba(31, 91, 69, 0.16);
        border-radius: 0.9rem;
        background: rgba(255, 255, 255, 0.92);
        padding: 0.85rem;
    }

    .calculator-shell .project-title {
        color: #173227;
        font-weight: 700;
        line-height: 1.3;
    }

    .calculator-shell .project-meta {
        color: #5f6f66;
        font-size: 0.86rem;
    }

    .calculator-shell .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.6rem;
    }

    .calculator-shell .summary-grid-item {
        border: 1px solid rgba(31, 91, 69, 0.16);
        border-radius: 0.8rem;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.65rem 0.72rem;
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
    }

    .calculator-shell .summary-grid-label {
        color: #5f6f66;
        font-size: 0.78rem;
    }

    .calculator-shell .summary-grid-item strong {
        color: #173227;
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .calculator-shell .summary-grid-item small {
        color: #6f7f76;
        font-size: 0.73rem;
        line-height: 1.25;
    }

    .calculator-shell .calc-section-title {
        color: #214535;
        font-size: 0.94rem;
        font-weight: 700;
        margin-bottom: 0.65rem;
    }

    .calculator-shell .result-list {
        display: grid;
        gap: 0.58rem;
    }

    .calculator-shell .result-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        border: 1px solid rgba(31, 91, 69, 0.16);
        border-radius: 0.82rem;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.7rem 0.78rem;
    }

    .calculator-shell .result-item span {
        color: #5f6f66;
        font-size: 0.84rem;
    }

    .calculator-shell .result-item strong {
        color: #173227;
        font-size: 0.89rem;
    }

    @media (max-width: 575.98px) {
        .calculator-shell .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
