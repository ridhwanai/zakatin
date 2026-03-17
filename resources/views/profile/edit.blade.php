<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Profile</h1>
    </x-slot>

    <div class="container py-4">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Profile Information</h2>
                        <p class="text-muted small">Update nama akun. Email bersifat tetap dan tidak bisa diubah.</p>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    autocomplete="name"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control"
                                    value="{{ $user->email }}"
                                    disabled
                                    readonly
                                >
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Update Password</h2>
                        <p class="text-muted small">Gunakan password yang kuat untuk keamanan akun.</p>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="current-password"
                                >
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="new-password"
                                >
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="form-control"
                                    autocomplete="new-password"
                                >
                            </div>

                            <button type="submit" class="btn btn-primary">Save Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm border-danger-subtle">
                    <div class="card-body">
                        <h2 class="h6 text-danger mb-3">Delete Account</h2>
                        <p class="text-muted small">Aksi ini permanen. Semua data Anda akan terhapus.</p>

                        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Hapus akun Anda secara permanen?');" class="row g-3 align-items-end">
                            @csrf
                            @method('DELETE')

                            <div class="col-12 col-md-5">
                                <label for="delete_password" class="form-label">Password</label>
                                <input
                                    id="delete_password"
                                    name="password"
                                    type="password"
                                    class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                    required
                                >
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-auto">
                                <button type="submit" class="btn btn-outline-danger">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

