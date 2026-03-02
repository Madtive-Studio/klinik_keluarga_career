<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Karir | Madtive Studio - Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:locale" content="id_ID" />
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
    <link rel="stylesheet" href="{{ asset('client/css/bootstrap.min.css') }}" type="text/css">
    <!--Material Icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/css/materialdesignicons.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/css/selectize.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/css/nice-select.css') }}" />
    <!-- Flatpickr -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/css/flatpickr.min.css') }}" />
    <!-- Custom  Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/css/style.css') }}" />
</head>

<body>
    <div id="preloader">
        <div id="status">
            <img src="{{ asset('logo.png') }}" width="250" class="d-block mx-auto" alt="">
            <p class="mt-3 text-center"><strong>Tunggu Sebentar...</strong></p>
        </div>
    </div>
    <div class="back-to-home rounded d-none d-sm-block">
        <a href="{{ route('client.home') }}" class="text-white rounded d-inline-block text-center"><i class="mdi mdi-home"></i></a>
    </div>
    <section class="vh-100" style="background: url('https://img.freepik.com/premium-photo/workspace-wide-light-office_280538-7380.jpg?semt=ais_hybrid') no-repeat center center; background-size: cover;">
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
                                        <strong>Daftar</strong>
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
                                <form class="login-form" action="{{ route('client.verify') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>Nama lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="name" value="{{ old('name') }}">
                                                @error('name') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group position-relative">
                                                <label>Nomor Telepon (WA) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" placeholder="..." name="phone" value="{{ old('phone') }}">
                                                @error('phone') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group position-relative">
                                                <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control flatpickr" readonly placeholder="..." name="birth_date" value="{{ old('birth_date') }}">
                                                @error('birth_date') <span
                                                    class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="email" value="{{ old('email') }}">
                                                @error('email') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>Alamat Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" placeholder="..." name="address" value="{{ old('address') }}">
                                                @error('address') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" placeholder="..." name="password">
                                                @error('password') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group position-relative">
                                                <label>Konfirmasi Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" placeholder="..." name="password_confirmation">
                                                @error('password_confirmation') 
                                                    <span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary w-100">Register</button>
                                        </div>
                                        <div class="mx-auto">
                                            <p class="mb-0 mt-3"><small class="text-dark mr-2">Sudah punya akun?</small>
                                                <a href="{{ route('client.login') }}" class="text-dark font-weight-bold">Login</a>
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
    <script src="{{ asset('client/js/jquery.min.js') }}"></script>
    <script src="{{ asset('client/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('client/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('client/js/plugins.js') }}"></script>
    <script src="{{ asset('client/js/selectize.min.js') }}"></script>
    <script src="{{ asset('client/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('client/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('client/js/flatpickr-id.min.js') }}"></script>
    <script src="{{ asset('client/js/app.js') }}"></script>
</body>

</html>