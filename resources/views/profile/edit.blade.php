@extends('layouts.app')

@section('content')
<div class="profile-page-wrap">
    <div class="p-3 p-md-4 pb-5">
        <div class="profile-topbar mb-4">
            <a href="{{ route('dashboard', ['month' => request('month', now()->month), 'year' => request('year', now()->year), 'tab' => request('tab', 'all')]) }}" class="back-link-btn" aria-label="Voltar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </a>

            <div>
                <h1 class="profile-page-title">Perfil</h1>
                <p class="profile-page-subtitle">Gerencie seus dados e segurança</p>
            </div>
        </div>

        <div class="d-grid gap-4">
            <div class="card card-dark profile-card">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Informações do perfil</div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card card-dark profile-card">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Atualizar senha</div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card card-dark profile-card danger-card">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Excluir conta</div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-page-wrap {
        max-width: 720px;
        margin: 0 auto;
        min-height: 100vh;
        padding-bottom: 50px;
    }

    .profile-topbar {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .back-link-btn {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border-soft);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
        transition: .18s ease;
    }

    .back-link-btn:hover {
        color: var(--primary);
        background: rgba(255,255,255,.08);
        transform: translateY(-1px);
    }

    .back-link-btn svg {
        width: 24px;
        height: 24px;
    }

    .profile-page-title {
        margin: 0;
        font-size: 1.7rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--text-main);
    }

    .profile-page-subtitle {
        margin: 4px 0 0;
        color: var(--text-soft);
        font-size: .96rem;
    }

    .profile-card {
        border-radius: 28px;
    }

    .section-title {
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.02em;
    }

    .danger-card {
        border: 1px solid rgba(220, 76, 63, .18);
    }

    .profile-card h2,
    .profile-card h3,
    .profile-card p,
    .profile-card label,
    .profile-card .text-gray-900,
    .profile-card .text-gray-800,
    .profile-card .text-gray-700,
    .profile-card .text-gray-600,
    .profile-card .text-sm,
    .profile-card .text-base {
        color: var(--text-main) !important;
    }

    .profile-card .text-gray-600,
    .profile-card .text-gray-500,
    .profile-card .mt-1,
    .profile-card .text-sm.text-gray-600,
    .profile-card .mt-1.text-sm.text-gray-600 {
        color: var(--text-soft) !important;
    }

    .profile-card input,
    .profile-card select,
    .profile-card textarea {
        background: var(--input-bg) !important;
        color: var(--input-text) !important;
        border: 1px solid var(--border-soft) !important;
        border-radius: 18px !important;
        min-height: 52px;
        box-shadow: none !important;
    }

    .profile-card input::placeholder,
    .profile-card textarea::placeholder {
        color: var(--placeholder) !important;
    }

    .profile-card button,
    .profile-card .inline-flex.items-center.px-4.py-2 {
        border-radius: 16px !important;
        min-height: 48px;
        font-weight: 600;
        letter-spacing: -0.01em;
    }

    .profile-card .bg-gray-800,
    .profile-card .bg-black,
    .profile-card .inline-flex.items-center.px-4.py-2.bg-gray-800 {
        background: var(--primary) !important;
        color: #111 !important;
        border: none !important;
    }

    .profile-card .bg-gray-800:hover,
    .profile-card .bg-black:hover,
    .profile-card .inline-flex.items-center.px-4.py-2.bg-gray-800:hover {
        background: var(--primary-hover) !important;
        color: #111 !important;
    }

    .profile-card .text-red-600,
    .profile-card .text-red-500 {
        color: var(--expense) !important;
    }

    .profile-card .border-gray-300,
    .profile-card .border-gray-200 {
        border-color: var(--border-soft) !important;
    }

    .profile-card .rounded-md,
    .profile-card .rounded-lg {
        border-radius: 18px !important;
    }

    .profile-card .shadow-sm {
        box-shadow: none !important;
    }

    .profile-card .focus\:ring-indigo-500:focus,
    .profile-card .focus\:border-indigo-500:focus,
    .profile-card input:focus,
    .profile-card select:focus,
    .profile-card textarea:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(18, 203, 230, .12) !important;
        outline: none !important;
    }

    .theme-light .back-link-btn {
        background: rgba(15,23,42,.03);
    }

    .theme-light .profile-card {
        box-shadow: var(--shadow-main);
    }

    @media (max-width: 576px) {
        .profile-page-title {
            font-size: 1.45rem;
        }

        .back-link-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
        }

        .back-link-btn svg {
            width: 22px;
            height: 22px;
        }

        .section-title {
            font-size: 1rem;
        }
    }
</style>
@endsection