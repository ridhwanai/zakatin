<style>
    :root {
        --zk-bg-a: #f8f3e7;
        --zk-bg-b: #dceadf;
        --zk-bg-c: #eff7ef;
        --zk-surface: rgba(255, 255, 255, 0.8);
        --zk-surface-solid: #ffffff;
        --zk-border: rgba(31, 91, 69, 0.2);
        --zk-text: #143628;
        --zk-muted: #5f6f66;
        --zk-brand: #1f5b45;
        --zk-brand-dark: #163f32;
        --zk-accent: #d9b45d;
        --zk-accent-dark: #c79a3b;
    }

    body.site-body {
        font-family: 'Sora', sans-serif;
        color: var(--zk-text);
        min-height: 100vh;
        padding-top: 82px;
        background:
            radial-gradient(circle at 8% 8%, var(--zk-bg-c) 0%, transparent 42%),
            radial-gradient(circle at 92% 12%, var(--zk-bg-b) 0%, transparent 44%),
            linear-gradient(145deg, #f6f2e8 0%, var(--zk-bg-a) 42%, #f2ead7 100%);
        background-attachment: fixed;
    }

    .site-nav {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1040;
        border-bottom: 1px solid rgba(31, 91, 69, 0.14);
        background: rgba(246, 242, 232, 0.55);
        backdrop-filter: blur(7px);
    }

    .site-nav .brand-mark {
        letter-spacing: -0.02em;
    }

    .site-nav .nav-pill-shell {
        border: 1px solid var(--zk-border);
        background: var(--zk-surface-solid);
        border-radius: 999px;
        gap: 0.35rem;
    }

    .site-nav .nav-main-link {
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        color: #2f4f42;
        font-size: 0.88rem;
        font-weight: 500;
    }

    .site-nav .nav-main-link:hover {
        color: var(--zk-brand-dark);
    }

    .site-nav .nav-main-link.active {
        background: linear-gradient(135deg, var(--zk-brand) 0%, var(--zk-brand-dark) 100%);
        color: #fff7df;
    }

    .site-nav .auth-zone {
        border-left: 1px solid var(--zk-border);
        padding-left: 0.85rem;
        margin-left: 0.85rem;
    }

    .site-nav .nav-login {
        color: #274437;
        font-weight: 500;
    }

    .site-nav .nav-login:hover {
        color: var(--zk-brand-dark);
    }

    .avatar-trigger {
        border-radius: 999px;
    }

    .avatar-image,
    .avatar-fallback {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(17, 24, 39, 0.14);
    }

    .avatar-image {
        object-fit: cover;
        background: #f3f4f6;
    }

    .avatar-fallback {
        background: linear-gradient(140deg, var(--zk-brand) 0%, #2f8f66 100%);
        color: #f6f2e6;
        font-weight: 700;
        font-size: 0.86rem;
    }

    .page-header-shell {
        background: var(--zk-surface);
        border: 1px solid var(--zk-border);
        border-radius: 1.25rem;
        backdrop-filter: blur(10px);
    }

    .card,
    .alert {
        border-radius: 1rem;
    }

    .card {
        border-color: var(--zk-border) !important;
        background: rgba(255, 255, 255, 0.92);
    }

    .btn-dark {
        border-color: var(--zk-brand-dark);
        background: linear-gradient(135deg, var(--zk-brand) 0%, var(--zk-brand-dark) 100%);
    }

    .btn-dark:hover,
    .btn-dark:focus {
        border-color: #133a2e;
        background: linear-gradient(135deg, #1c513f 0%, #133a2e 100%);
    }

    .btn-outline-dark {
        color: var(--zk-brand-dark);
        border-color: rgba(31, 91, 69, 0.45);
    }

    .btn-outline-dark:hover,
    .btn-outline-dark:focus {
        color: #fff;
        border-color: var(--zk-brand);
        background: var(--zk-brand);
    }

    .btn-warning {
        color: #332200;
        border-color: var(--zk-accent-dark);
        background: linear-gradient(135deg, #ffd252 0%, var(--zk-accent) 100%);
    }

    .btn-warning:hover,
    .btn-warning:focus {
        color: #2b1d00;
        border-color: #cf9600;
        background: linear-gradient(135deg, #ffcf49 0%, #f1b20a 100%);
    }

    .text-muted {
        color: var(--zk-muted) !important;
    }

    .toast-container {
        z-index: 1080;
    }

    .site-toast {
        width: min(330px, calc(100vw - 1.5rem));
        border: 1px solid var(--zk-border);
        border-radius: 0.9rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
    }

    .site-toast .toast-header {
        border-radius: 0.9rem 0.9rem 0 0;
        background: rgba(249, 244, 232, 0.9);
        padding: 0.55rem 0.75rem;
    }

    .site-toast .toast-header strong {
        font-size: 0.82rem;
        letter-spacing: 0.01em;
    }

    .site-toast .toast-body {
        font-size: 0.86rem;
        padding: 0.62rem 0.75rem 0.72rem;
    }

    .guest-shell {
        min-height: calc(100vh - 160px);
        display: flex;
        align-items: center;
    }

    .guest-card {
        background: rgba(255, 255, 255, 0.93);
        border: 1px solid var(--zk-border);
        border-radius: 1.2rem;
        box-shadow: 0 12px 30px rgba(17, 24, 39, 0.08);
    }

    .guest-intro-badge {
        display: inline-flex;
        align-items: center;
        border: 1px solid var(--zk-border);
        background: rgba(255, 255, 255, 0.72);
        border-radius: 999px;
        padding: 0.4rem 0.9rem;
        font-size: 0.78rem;
    }

    .site-footer {
        background: linear-gradient(135deg, #1f5b45 0%, #163f32 100%);
    }

    .site-footer.border-top,
    .site-footer .border-top {
        border-color: rgba(255, 255, 255, 0.2) !important;
    }

    .site-footer-brand {
        color: #f4f8f5;
        font-size: 1.18rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .site-footer-heading {
        color: #e6f1eb;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
    }

    .site-footer-link {
        color: #dbe9e2;
        font-size: 0.92rem;
        text-decoration: none;
    }

    .site-footer-link:hover,
    .site-footer-link:focus {
        color: #ffffff;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .site-footer-social {
        width: 2.25rem;
        height: 2.25rem;
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        color: #f0f7f3;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: color 0.2s ease, background 0.2s ease, border-color 0.2s ease;
    }

    .site-footer-social:hover,
    .site-footer-social:focus {
        border-color: rgba(255, 255, 255, 0.7);
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    .site-footer-social.is-disabled {
        opacity: 0.55;
        pointer-events: none;
    }

    .site-footer .text-muted {
        color: rgba(232, 243, 237, 0.82) !important;
    }

    @media (max-width: 991.98px) {
        body.site-body {
            padding-top: 76px;
        }

        .site-nav .auth-zone {
            border-left: 0;
            padding-left: 0;
            margin-left: 0;
        }

        .guest-shell {
            min-height: auto;
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .site-footer {
            margin-top: 2.5rem !important;
        }
    }
</style>
