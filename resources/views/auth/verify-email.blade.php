<x-guest-layout>
    <h2 class="h5 mb-3">Verify Email</h2>
    <p class="text-muted mb-4">
        Masukkan kode 6 digit yang dikirim ke email Anda. Jika belum masuk, kirim ulang kode baru.
    </p>

    <form method="POST" action="{{ route('verification.code') }}" class="mb-3">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Kode Verifikasi</label>
            <input
                id="code"
                type="text"
                name="code"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                value="{{ old('code') }}"
                class="form-control @error('code') is-invalid @enderror"
                placeholder="Contoh: 123456"
                required
                autofocus
            >
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Verifikasi Kode</button>
        </div>
    </form>

    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('verification.send') }}" class="flex-grow-1">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">Kirim Ulang Kode</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Log Out</button>
        </form>
    </div>
</x-guest-layout>

