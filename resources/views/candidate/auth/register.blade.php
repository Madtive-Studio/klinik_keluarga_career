<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - {{ __('candidate.auth.register_title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('app.name') }}" />
    <meta property="og:description" content="{{ config('app.name') }} - {{ __('candidate.footer.about') }}" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta name="description" content="{{ config('app.name') }} - {{ __('candidate.footer.about') }}">
    <meta name="keywords" content="Madtive Studio">
    <!-- Favicon -->
   	@include('layouts.app-icon')
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/candidate/css/bootstrap.min.css') }}" type="text/css">
    <!--Material Icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/materialdesignicons.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/selectize.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/nice-select.css') }}" />
    <!-- Flatpickr -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/flatpickr.min.css') }}" />
    <!-- Custom  Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/cursor-interactions.css') }}" />
</head>

<body>
    <div id="preloader">
        <div id="status">
            <img src="{{ asset('logo.png') }}" width="250" class="d-block mx-auto" alt="">
            <p class="mt-3 text-center"><strong>{{ __('common.loading') }}</strong></p>
        </div>
    </div>
    <div class="position-absolute" style="top: 20px; right: 20px; z-index: 1000">
        @include('layouts.locale-switcher')
    </div>
    <div class="back-to-home rounded d-none d-sm-block">
        <a href="{{ route('candidate.home') }}" class="text-white rounded d-inline-block text-center"><i class="mdi mdi-home"></i></a>
    </div>
    <section class="min-vh-100 bg-home py-5 d-flex align-items-center justify-content-center">
        <div class="bg-overlay"></div>
        <div class="home-center my-auto">
            <div class="home-desc-center py-4">
                <div class="container">
                    <img src="{{ asset('logo-white.png') }}" width="240" class="mb-4 d-block mx-auto auth-logo-animated" alt="">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-10">
                            <div class="login_page bg-white shadow-lg p-4 p-md-5">
                                <div class="text-center">
                                    <h4 class="mb-4">
                                        <strong>{{ __('candidate.auth.register_title') }}</strong>
                                    </h4>
                                </div>
                                @if ($message = Session::get('error'))
                                <div class="alert alert-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endif
                                @if (Session::has('success'))
                                <div class="alert alert-success" role="alert">
                                    <strong>{{ Session::get('success') }}</strong>
                                </div>
                                @endif
                                <form class="login-form" action="{{ route('candidate.register.verify') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">{{ __('candidate.auth.full_name') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="name" value="{{ old('name') }}">
                                                @error('name') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">Username <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="john_doe" name="username" value="{{ old('username') }}">
                                                @error('username') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">{{ __('candidate.auth.phone') }} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-select bg-light text-dark fw-medium" name="country_code" style="max-width: 115px; font-size: 0.88rem;">
                                                        <optgroup label="Asia Tenggara (ASEAN)">
                                                            <option value="+62" {{ old('country_code', '+62') == '+62' ? 'selected' : '' }}>🇮🇩 +62 (ID)</option>
                                                            <option value="+60" {{ old('country_code') == '+60' ? 'selected' : '' }}>🇲🇾 +60 (MY)</option>
                                                            <option value="+65" {{ old('country_code') == '+65' ? 'selected' : '' }}>🇸🇬 +65 (SG)</option>
                                                            <option value="+66" {{ old('country_code') == '+66' ? 'selected' : '' }}>🇹🇭 +66 (TH)</option>
                                                            <option value="+63" {{ old('country_code') == '+63' ? 'selected' : '' }}>🇵🇭 +63 (PH)</option>
                                                            <option value="+84" {{ old('country_code') == '+84' ? 'selected' : '' }}>🇻🇳 +84 (VN)</option>
                                                            <option value="+95" {{ old('country_code') == '+95' ? 'selected' : '' }}>🇲🇲 +95 (MM)</option>
                                                            <option value="+855" {{ old('country_code') == '+855' ? 'selected' : '' }}>🇰🇭 +855 (KH)</option>
                                                            <option value="+856" {{ old('country_code') == '+856' ? 'selected' : '' }}>🇱🇦 +856 (LA)</option>
                                                            <option value="+673" {{ old('country_code') == '+673' ? 'selected' : '' }}>🇧🇳 +673 (BN)</option>
                                                            <option value="+670" {{ old('country_code') == '+670' ? 'selected' : '' }}>🇹🇱 +670 (TL)</option>
                                                        </optgroup>
                                                        <optgroup label="Asia & Timur Tengah">
                                                            <option value="+81" {{ old('country_code') == '+81' ? 'selected' : '' }}>🇯🇵 +81 (JP)</option>
                                                            <option value="+82" {{ old('country_code') == '+82' ? 'selected' : '' }}>🇰🇷 +82 (KR)</option>
                                                            <option value="+86" {{ old('country_code') == '+86' ? 'selected' : '' }}>🇨🇳 +86 (CN)</option>
                                                            <option value="+886" {{ old('country_code') == '+886' ? 'selected' : '' }}>🇹🇼 +886 (TW)</option>
                                                            <option value="+852" {{ old('country_code') == '+852' ? 'selected' : '' }}>🇭🇰 +852 (HK)</option>
                                                            <option value="+91" {{ old('country_code') == '+91' ? 'selected' : '' }}>🇮🇳 +91 (IN)</option>
                                                            <option value="+966" {{ old('country_code') == '+966' ? 'selected' : '' }}>🇸🇦 +966 (SA)</option>
                                                            <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>🇦🇪 +971 (AE)</option>
                                                            <option value="+974" {{ old('country_code') == '+974' ? 'selected' : '' }}>🇶🇦 +974 (QA)</option>
                                                            <option value="+965" {{ old('country_code') == '+965' ? 'selected' : '' }}>🇰🇼 +965 (KW)</option>
                                                            <option value="+90" {{ old('country_code') == '+90' ? 'selected' : '' }}>🇹🇷 +90 (TR)</option>
                                                        </optgroup>
                                                        <optgroup label="Global / Lainnya">
                                                            <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1 (US/CA)</option>
                                                            <option value="+61" {{ old('country_code') == '+61' ? 'selected' : '' }}>🇦🇺 +61 (AU)</option>
                                                            <option value="+64" {{ old('country_code') == '+64' ? 'selected' : '' }}>🇳🇿 +64 (NZ)</option>
                                                            <option value="+44" {{ old('country_code') == '+44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                                                            <option value="+49" {{ old('country_code') == '+49' ? 'selected' : '' }}>🇩🇪 +49 (DE)</option>
                                                            <option value="+33" {{ old('country_code') == '+33' ? 'selected' : '' }}>🇫🇷 +33 (FR)</option>
                                                            <option value="+31" {{ old('country_code') == '+31' ? 'selected' : '' }}>🇳🇱 +31 (NL)</option>
                                                            <option value="+39" {{ old('country_code') == '+39' ? 'selected' : '' }}>🇮🇹 +39 (IT)</option>
                                                            <option value="+34" {{ old('country_code') == '+34' ? 'selected' : '' }}>🇪🇸 +34 (ES)</option>
                                                            <option value="+7" {{ old('country_code') == '+7' ? 'selected' : '' }}>🇷🇺 +7 (RU)</option>
                                                            <option value="+55" {{ old('country_code') == '+55' ? 'selected' : '' }}>🇧🇷 +55 (BR)</option>
                                                            <option value="+27" {{ old('country_code') == '+27' ? 'selected' : '' }}>🇿🇦 +27 (ZA)</option>
                                                        </optgroup>
                                                    </select>
                                                    <input type="tel" inputmode="numeric" class="form-control" placeholder="08123456789 / 628123456789" name="phone" value="{{ old('phone') }}">
                                                </div>
                                                @error('phone') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">{{ __('candidate.auth.birth_date') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flatpickr" readonly placeholder="..." name="birth_date" value="{{ old('birth_date') }}">
                                                @error('birth_date') <span
                                                    class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">{{ __('common.email') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="email" value="{{ old('email') }}">
                                                @error('email') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">{{ __('candidate.auth.address') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="address" value="{{ old('address') }}">
                                                @error('address') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">{{ __('common.password') }} <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" placeholder="..." name="password">
                                                @error('password') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative">
                                                <label class="form-label text-dark fw-semibold">{{ __('candidate.auth.password_confirmation') }} <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" placeholder="..." name="password_confirmation">
                                                @error('password_confirmation') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary w-100 fw-bold py-2">{{ __('common.register') }}</button>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="mb-0 mt-3"><small class="text-secondary me-2">{{ __('candidate.auth.has_account') }}</small>
                                                <a href="{{ route('candidate.login.form') }}" class="text-primary fw-bold">{{ __('common.login') }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('assets/candidate/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/flatpickr-id.min.js') }}"></script>
    <script src="{{ asset('assets/candidate/js/app.js') }}"></script>
</body>

</html>