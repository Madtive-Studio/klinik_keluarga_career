<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Karir | Madtive Studio - Login</title>
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
	<link rel="stylesheet" href="{{ asset('assets/candidate/css/bootstrap.min.css') }}" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/materialdesignicons.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/selectize.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/nice-select.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/style.css') }}" />
</head>

<body>
	<div id="preloader">
		<div id="status">
			<img src="{{ asset('logo.png') }}" width="250" class="d-block mx-auto" alt="">
			<p class="mt-3 text-center"><strong>Tunggu Sebentar...</strong></p>
		</div>
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
						<div class="col-lg-4 col-md-6">
							<div class="login-page bg-white shadow rounded p-4">
								<div class="text-center">
									<h4 class="mb-4">
										<strong>Login</strong>
									</h4>
								</div>
								@if ($message = Session::get('error'))
									<div class="alert alert-danger" role="alert">
										<strong>{{ $message }}</strong>
									</div>
								@endif
								@if (Session::has('must_login'))
									<div class="alert alert-danger" role="alert">
										<strong>{{ Session::get('must_login') }}</strong>
									</div>
								@endif
								@if (Session::has('success'))
									<div class="alert alert-success" role="alert">
										<strong>{{ Session::get('success') }}</strong>
									</div>
								@endif
								<form class="login-form" action="{{ route('candidate.login.process') }}" method="POST">
									@csrf
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group position-relative">
												<label>Email <span class="text-danger">*</span></label>
												<input type="email" class="form-control" placeholder="Email" name="email">
												@error('email')
													<span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
												@enderror
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group position-relative">
												<label>Password <span class="text-danger">*</span></label>
												<input type="password" name="password" class="form-control" placeholder="Password">
												@error('password')
													<span class="text-danger fw-bold"><strong>{{ $message }}</strong></span>
												@enderror
											</div>
										</div>
										<div class="col-lg-12 mb-0">
											<button class="btn btn-primary w-100">Login</button>
										</div>
										<div class="col-12 text-center">
											<p class="mb-0 mt-3"><small class="text-dark mr-2">Belum punya akun?</small>
												<a href="{{ route('candidate.register.form') }}" class="text-dark font-weight-bold">Daftar</a>
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
	<script src="{{ asset('assets/candidate/js/app.js') }}"></script>
</body>

</html>
