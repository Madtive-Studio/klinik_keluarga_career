@php
    $currentLocale = app()->getLocale();
@endphp

@once
    <style>
        .locale-switch {
            display: inline-flex;
            align-items: center;
            padding: 2px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.04);
            gap: 2px;
        }

        .locale-switch__option {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.25rem;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            line-height: 1.2;
            text-decoration: none !important;
            color: #6c757d;
            transition: background-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        }

        .locale-switch__option:hover {
            color: #495057;
        }

        .locale-switch__option.is-active {
            background: #2f55d4;
            color: #fff !important;
            box-shadow: 0 1px 3px rgba(47, 85, 212, 0.35);
        }

        .layout-navbar .locale-switch {
            border-color: rgba(67, 89, 113, 0.2);
            background: rgba(67, 89, 113, 0.06);
        }

        .layout-navbar .locale-switch__option.is-active {
            background: var(--bs-primary, #7367f0);
            box-shadow: 0 1px 3px rgba(115, 103, 240, 0.35);
        }

        #topnav .locale-switch {
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.12);
        }

        #topnav.defaultscroll .locale-switch,
        #topnav.position-relative .locale-switch {
            border-color: rgba(0, 0, 0, 0.12);
            background: rgba(0, 0, 0, 0.04);
        }

        #topnav .locale-switch__option {
            color: rgba(255, 255, 255, 0.85);
        }

        #topnav.defaultscroll .locale-switch__option,
        #topnav.position-relative .locale-switch__option {
            color: #6c757d;
        }

        #topnav .locale-switch__option.is-active,
        #topnav.defaultscroll .locale-switch__option.is-active,
        #topnav.position-relative .locale-switch__option.is-active {
            background: #2f55d4;
            color: #fff !important;
        }
    </style>
@endonce

<div class="locale-switch {{ $class ?? '' }}" role="group" aria-label="{{ __('common.language') }}">
    <a href="{{ route('locale.switch', 'id') }}"
       class="locale-switch__option {{ $currentLocale === 'id' ? 'is-active' : '' }}"
       @if ($currentLocale === 'id') aria-current="true" @endif
       title="Bahasa Indonesia">ID</a>
    <a href="{{ route('locale.switch', 'en') }}"
       class="locale-switch__option {{ $currentLocale === 'en' ? 'is-active' : '' }}"
       @if ($currentLocale === 'en') aria-current="true" @endif
       title="English">EN</a>
</div>
