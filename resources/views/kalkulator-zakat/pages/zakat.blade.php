<section class="container py-4 calculator-shell">
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <span class="badge rounded-pill border text-dark-emphasis fw-medium mb-2">Fitur Tambahan</span>
                            <h1 class="h4 fw-bold mb-1">Kalkulator Zakat</h1>
                            <p class="text-muted mb-0 small">Hitung zakat emas, penghasilan, perusahaan, dan perdagangan dengan nisab 85 gram.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge text-bg-light border px-3 py-2">Nisab 85 gr</span>
                            <span class="badge text-bg-light border px-3 py-2">Tarif 2.5%</span>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0 px-4 pb-4">
                    <form id="zakatForm" class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="zakatType" class="form-label">Jenis Zakat</label>
                            <select id="zakatType" class="form-select" aria-label="Jenis zakat">
                                <option value="penghasilan">Zakat Penghasilan</option>
                                <option value="emas">Zakat Emas</option>
                                <option value="perusahaan">Zakat Perusahaan</option>
                                <option value="perdagangan">Zakat Perdagangan</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="hargaEmas" class="form-label">Harga Emas per Gram</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input
                                    type="number"
                                    id="hargaEmas"
                                    class="form-control"
                                    placeholder="1100000"
                                    min="0"
                                    step="1000"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                            <button type="button" class="btn btn-outline-dark rounded-pill px-3" id="refreshGoldPriceBtn">
                                Refresh Harga Emas
                            </button>
                            <p class="text-muted small mb-0 text-lg-end" id="goldPriceSource">Sumber: Memuat harga emas harian...</p>
                        </div>

                        <div id="formPenghasilan" class="dynamic-form visible col-12">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="gaji" class="form-label">Gaji Bulanan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="gaji"
                                            class="form-control"
                                            placeholder="5000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="penghasilanLain" class="form-label">Penghasilan Lain</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="penghasilanLain"
                                            class="form-control"
                                            placeholder="2000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="formEmas" class="dynamic-form hidden col-12">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="totalEmas" class="form-label">Total Emas Dimiliki</label>
                                    <div class="input-group">
                                        <input
                                            type="number"
                                            id="totalEmas"
                                            class="form-control"
                                            placeholder="100"
                                            min="0"
                                            step="0.1"
                                        >
                                        <span class="input-group-text">gram</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="formPerusahaan" class="dynamic-form hidden col-12">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="kasPerusahaan" class="form-label">Kas & Setara Kas</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="kasPerusahaan"
                                            class="form-control"
                                            placeholder="20000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="piutangPerusahaan" class="form-label">Piutang Lancar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="piutangPerusahaan"
                                            class="form-control"
                                            placeholder="10000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="persediaanPerusahaan" class="form-label">Nilai Persediaan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="persediaanPerusahaan"
                                            class="form-control"
                                            placeholder="15000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="utangPerusahaan" class="form-label">Utang Jatuh Tempo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="utangPerusahaan"
                                            class="form-control"
                                            placeholder="5000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="formPerdagangan" class="dynamic-form hidden col-12">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="modalDagang" class="form-label">Modal Diputar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="modalDagang"
                                            class="form-control"
                                            placeholder="10000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="labaDagang" class="form-label">Laba Bersih</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="labaDagang"
                                            class="form-control"
                                            placeholder="3000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="piutangDagang" class="form-label">Piutang Dagang</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="piutangDagang"
                                            class="form-control"
                                            placeholder="2000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="utangDagang" class="form-label">Utang Dagang</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="number"
                                            id="utangDagang"
                                            class="form-control"
                                            placeholder="1000000"
                                            min="0"
                                            step="1000"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 flex-wrap pt-1">
                            <button type="submit" class="btn btn-dark rounded-pill px-4" id="calculateBtn">Hitung Zakat</button>
                            <button type="button" class="btn btn-outline-danger rounded-pill px-4" id="resetBtn">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h6 fw-semibold mb-1">Ringkasan Hasil</h2>
                    <p class="text-muted small mb-0">Hasil otomatis setelah Anda menekan tombol hitung.</p>
                </div>
                <div class="card-body pt-3 px-4 pb-4">
                    <div class="result-list">
                        <div class="result-item">
                            <span id="resultTotalLabel">Total Penghasilan/Bulan</span>
                            <strong id="resultTotal">Rp 0</strong>
                        </div>
                        <div class="result-item">
                            <span id="resultNisabYearLabel">Nisab/Tahun</span>
                            <strong id="resultNisab">Rp 0</strong>
                        </div>
                        <div class="result-item" id="resultNisabBulananRow">
                            <span>Nisab/Bulan</span>
                            <strong id="resultNisabBulanan">Rp 0</strong>
                        </div>
                        <div class="result-item">
                            <span>Status</span>
                            <strong id="resultStatus" class="status status-idle">-</strong>
                        </div>
                        <div class="result-item">
                            <span>Zakat Dibayar</span>
                            <strong id="resultZakat">Rp 0</strong>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-3 mb-0 small">
                        Nisab: <strong>85 x harga emas</strong>.
                        Penghasilan menggunakan total bulanan, sedangkan emas/perusahaan/perdagangan menggunakan total aset bersih x <strong>2.5%</strong>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div
        class="toast site-toast border-0 shadow-sm"
        id="toast"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="2600"
        data-bs-autohide="true"
    >
        <div class="toast-header border-0">
            <span class="badge rounded-pill text-bg-primary me-2" id="toastBadge">&nbsp;</span>
            <strong class="me-auto" id="toastTitle">Informasi</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastBody">Siap</div>
    </div>
</div>

<style>
    .calculator-shell .dynamic-form.hidden {
        display: none;
    }

    .calculator-shell .dynamic-form.visible {
        display: block;
    }

    .calculator-shell .result-list {
        display: grid;
        gap: 0.7rem;
    }

    .calculator-shell .result-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        border: 1px solid rgba(31, 91, 69, 0.16);
        border-radius: 0.85rem;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.72rem 0.8rem;
    }

    .calculator-shell .result-item span {
        color: #5f6f66;
        font-size: 0.87rem;
    }

    .calculator-shell .result-item strong {
        color: #173227;
        font-size: 0.93rem;
    }

    .calculator-shell .status {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.25rem 0.62rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .calculator-shell .status-idle {
        background: rgba(95, 111, 102, 0.14);
        color: #4f5f57;
    }

    .calculator-shell .status-wajib {
        background: rgba(31, 91, 69, 0.16);
        color: #1f5b45;
    }

    .calculator-shell .status-belum {
        background: rgba(217, 180, 93, 0.24);
        color: #6b511b;
    }

</style>
