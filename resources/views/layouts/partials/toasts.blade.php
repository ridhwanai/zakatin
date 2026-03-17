@php
    $toasts = [];

    $flashMessages = [
        ['key' => 'success', 'variant' => 'success', 'title' => 'Berhasil'],
        ['key' => 'info', 'variant' => 'primary', 'title' => 'Informasi'],
        ['key' => 'warning', 'variant' => 'warning', 'title' => 'Perhatian'],
        ['key' => 'error', 'variant' => 'danger', 'title' => 'Peringatan'],
    ];

    foreach ($flashMessages as $flashMessage) {
        if (session($flashMessage['key'])) {
            $toasts[] = [
                'variant' => $flashMessage['variant'],
                'title' => $flashMessage['title'],
                'message' => (string) session($flashMessage['key']),
            ];
        }
    }

    $status = session('status');
    if ($status) {
        $statusToast = match ($status) {
            'profile-updated' => ['variant' => 'success', 'title' => 'Profil', 'message' => 'Profil berhasil diperbarui.'],
            'password-updated' => ['variant' => 'success', 'title' => 'Keamanan', 'message' => 'Password berhasil diperbarui.'],
            'verification-link-sent' => ['variant' => 'primary', 'title' => 'Verifikasi', 'message' => 'Kode verifikasi baru berhasil dikirim.'],
            'verification-code-sent' => ['variant' => 'primary', 'title' => 'Verifikasi', 'message' => 'Kode verifikasi baru berhasil dikirim.'],
            default => ['variant' => 'success', 'title' => 'Informasi', 'message' => (string) $status],
        };

        $toasts[] = [
            'variant' => $statusToast['variant'],
            'title' => $statusToast['title'],
            'message' => $statusToast['message'],
        ];
    }

    $errorMessages = [];
    foreach ($errors->getBags() as $bag) {
        if ($bag->any()) {
            $errorMessages[] = $bag->first();
        }
    }

    foreach (array_unique($errorMessages) as $errorMessage) {
        $toasts[] = [
            'variant' => 'danger',
            'title' => 'Validasi',
            'message' => $errorMessage,
        ];
    }

    $toasts = collect($toasts)
        ->unique(fn (array $toast) => $toast['variant'].'|'.$toast['title'].'|'.$toast['message'])
        ->values()
        ->all();
@endphp

@if (! empty($toasts))
    <div class="toast-container position-fixed top-0 end-0 p-3">
        @foreach ($toasts as $toast)
            <div
                class="toast site-toast border-0 shadow-sm mb-2"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
                data-bs-delay="4200"
                data-bs-autohide="true"
            >
                <div class="toast-header border-0">
                    <span class="badge rounded-pill text-bg-{{ $toast['variant'] }} me-2">&nbsp;</span>
                    <strong class="me-auto">{{ $toast['title'] }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">{{ $toast['message'] }}</div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toast').forEach(function (element) {
                new bootstrap.Toast(element).show();
            });
        });
    </script>
@endif
