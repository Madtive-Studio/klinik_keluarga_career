<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>Karir | Madtive Studio - Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Madtive Studio" />
    <meta property="og:description" content="Madtive Studio adalah studio atau software house yang bergerak di bidang Teknologi Informasi, Sistem dan juga Branding yang berdiri sejak tahun 2015" />
    <meta property="og:url" content="https://madtive.com/" />
    <meta property="og:site_name" content="Madtive Studio" />
    <meta name="description" content="Madtive Studio adalah studio atau software house yang bergerak di bidang Teknologi Informasi, Sistem dan juga Branding yang berdiri sejak tahun 2015">
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
    <section class="vh-100 bg-home">
        <div class="bg-overlay"></div>
        <div class="home-center">
            <div class="home-desc-center">
                <div class="container">
                    <img src="{{ asset('logo-white.png') }}" width="250" style="z-index: 999 !important; position: relative;" class="mb-5 d-block mx-auto" alt="">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="login_page bg-white shadow rounded p-4">
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
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>{{ __('candidate.auth.full_name') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="name" value="{{ old('name') }}">
                                                @error('name') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group position-relative">
                                                <label>{{ __('candidate.auth.phone') }} <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" placeholder="..." name="phone" value="{{ old('phone') }}">
                                                @error('phone') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group position-relative">
                                                <label>{{ __('candidate.auth.birth_date') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flatpickr" readonly placeholder="..." name="birth_date" value="{{ old('birth_date') }}">
                                                @error('birth_date') <span
                                                    class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>{{ __('common.email') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="email" value="{{ old('email') }}">
                                                @error('email') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>{{ __('candidate.auth.address') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="address" value="{{ old('address') }}">
                                                @error('address') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>{{ __('common.password') }} <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" placeholder="..." name="password">
                                                @error('password') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>{{ __('candidate.auth.password_confirmation') }} <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" placeholder="..." name="password_confirmation">
                                                @error('password_confirmation') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary w-100">{{ __('common.register') }}</button>
                                        </div>
                                        <div class="mx-auto">
                                            <p class="mb-0 mt-3"><small class="text-dark mr-2">{{ __('candidate.auth.has_account') }}</small>
                                                <a href="{{ route('candidate.login.form') }}" class="text-dark font-weight-bold">{{ __('common.login') }}</a>
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