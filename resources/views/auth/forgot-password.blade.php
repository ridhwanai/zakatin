<x-guest-layout>
    <h2 class="h5 mb-3">Forgot Password</h2>
    <p class="text-muted">Masukkan email akun Anda untuk menerima link reset password.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required
                autofocus
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
        </div>
    </form>
</x-guest-layout>

