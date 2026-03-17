@php
    $user = Auth::user();
    $initials = '';
    $isCalculatorRoute = request()->routeIs('zakat.calculator');

    if ($user) {
        $parts = preg_split('/\s+/', trim($user->name)) ?: [];
        $initials = collect($parts)
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
@endphp

<nav class="navbar navbar-expand-lg py-2 site-nav">
    <div class="container">
        <a class="navbar-brand fw-bold brand-mark" href="{{ route('dashboard') }}">Zakatin.</a>

        <button class="navbar-toggler border-0 shadow-none px-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <div class="mx-lg-auto nav-cluster my-3 my-lg-0">
                <ul class="navbar-nav align-items-center nav-pill-shell px-2 py-1">
                    <li class="nav-item">
                        <a class="nav-link nav-main-link {{ request()->routeIs('home', 'dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            Panel Zakat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-main-link {{ $isCalculatorRoute ? 'active' : '' }}" href="{{ route('zakat.calculator') }}">
                            Kalkulator Zakat
                        </a>
                    </li>
                </ul>
            </div>

            <div class="d-flex align-items-center gap-2 ms-lg-3 auth-zone pt-3 pt-lg-0">
                @auth
                    <div class="dropdown">
                        <button class="btn avatar-trigger p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if ($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-image">
                            @else
                                <span class="avatar-fallback">{{ $initials }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2">
                            <li class="dropdown-header small text-muted">
                                {{ $user->name }}
                            </li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="px-3 py-1">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link nav-login text-decoration-none">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-dark rounded-pill px-4">Sign Up</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
