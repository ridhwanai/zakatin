<x-guest-layout>
    <h2 class="h5 mb-4">Masuk ke Akun</h2>

    <form method="POST" action="{{ route('login') }}">
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
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" id="remember_me" class="form-check-input">
            <label for="remember_me" class="form-check-label">Remember me</label>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Log in</button>
        </div>

        <div class="text-center text-muted my-3">atau</div>

        <div class="d-grid">
            <a href="{{ route('auth.google.redirect') }}" class="btn btn-outline-dark">
                <i class="fa-brands fa-google me-2"></i>Masuk dengan Google
            </a>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('register') }}" class="text-decoration-none">Belum punya akun? Daftar</a>
        </div>
    </form>
</x-guest-layout>

