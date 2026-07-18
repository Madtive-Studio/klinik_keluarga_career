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
            flex-shrink: 0;
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
            cursor: pointer;
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

        /* Candidate navbar — transparent / hero overlay */
        #topnav.defaultscroll:not(.scroll) .locale-switch {
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.14);
        }

        #topnav.defaultscroll:not(.scroll) .locale-switch__option {
            color: rgba(255, 255, 255, 0.9);
        }

        #topnav.defaultscroll:not(.scroll) .locale-switch__option:hover {
            color: #fff;
        }

        /* Candidate navbar — fixed white / scrolled / compact */
        #topnav.scroll .locale-switch,
        #topnav.position-relative .locale-switch,
        #topnav.defaultscroll.scroll .locale-switch {
            border-color: rgba(0, 0, 0, 0.12);
            background: rgba(0, 0, 0, 0.04);
        }

        #topnav.scroll .locale-switch__option,
        #topnav.position-relative .locale-switch__option,
        #topnav.defaultscroll.scroll .locale-switch__option {
            color: #6c757d;
        }

        #topnav .locale-switch__option.is-active,
        #topnav.scroll .locale-switch__option.is-active,
        #topnav.defaultscroll .locale-switch__option.is-active,
        #topnav.position-relative .locale-switch__option.is-active {
            background: #2f55d4;
            color: #fff !important;
        }

        #topnav .buy-button {
            display: flex;
            align-items: center;
            float: right;
            line-height: normal;
            min-height: 68px;
            padding: 3px 0;
        }

        #topnav .buy-button .locale-switch {
            margin-right: 0 !important;
        }

        #topnav .locale-menu-item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        #topnav .locale-menu-item__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
        }

        #topnav .locale-menu-item__label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #8492a6;
            white-space: nowrap;
        }

        @media (max-width: 991px) {
            #topnav .buy-button {
                min-height: 74px;
                padding-right: 0.15rem;
            }

            #topnav .buy-button:not(:has(.btn)) {
                display: none !important;
            }

            #topnav .buy-button .btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.8125rem;
                line-height: 1.2;
                white-space: nowrap;
            }

            #topnav .locale-switch,
            #topnav.defaultscroll:not(.scroll) .locale-switch {
                border-color: rgba(0, 0, 0, 0.12);
                background: rgba(0, 0, 0, 0.04);
            }

            #topnav .locale-switch__option,
            #topnav.defaultscroll:not(.scroll) .locale-switch__option {
                color: #6c757d;
            }

            #topnav.defaultscroll:not(.scroll) .locale-switch__option:hover {
                color: #495057;
            }
        }

        @media (max-width: 575px) {
            #topnav .buy-button .btn {
                padding: 0.4rem 0.55rem;
                font-size: 0.8125rem;
            }

            #topnav .locale-switch__option {
                min-width: 2rem;
                padding: 0.18rem 0.45rem;
                font-size: 0.68rem;
            }
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
