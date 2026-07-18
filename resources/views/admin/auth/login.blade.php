<!doctype html>

<html
	lang="{{ str_replace('_', '-', app()->getLocale()) }}"
	class="light-style layout-wide customizer-hide"
	dir="ltr"
	data-theme="theme-default"
	data-assets-path="{{ asset('assets/admin/assets') }}/"
	data-template="vertical-menu-template"
	data-style="light">

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
		<title>{{ config('app.name') }} | {{ __('admin.auth.login_title') }}</title>
		<meta name="description" content="" />
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />
   		@include('layouts.app-icon')
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/fonts/fontawesome.css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/fonts/tabler-icons.css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/fonts/flag-icons.css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/css/rtl/core.css" class="template-customizer-core-css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/css/demo.css" />
		<link rel="stylesheet" href="{{ asset('assets/css/cursor-interactions.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/libs/node-waves/node-waves.css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/libs/typeahead-js/typeahead.css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/libs/@form-validation/form-validation.css" />
		<link rel="stylesheet" href="{{ asset('assets/admin/assets') }}/vendor/css/pages/page-auth.css" />
		<script src="{{ asset('assets/admin/assets') }}/vendor/js/helpers.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/js/template-customizer.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/js/config.js"></script>
	</head>

<body>
  <div class="position-absolute" style="top: 20px; right: 20px; z-index: 1000">
    @include('layouts.locale-switcher')
  </div>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-6">
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
              <a href="#" class="app-brand-link">
                <img src="{{ asset("assets/logo/letter-logo.png") }}" width="225" alt="">
              </a>
            </div>

            @if ($message = Session::get("error"))
            <div class="alert alert-danger" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @endif

            <form class="mb-4" action="{{ route("admin.process") }}" method="POST">
              @csrf
              <div class="mb-6">
                <label for="email" class="form-label">{{ __('common.email') }}</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="{{ __('admin.auth.enter_email') }}" autofocus />
              </div>

              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password">{{ __('common.password') }}</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer">
                    <i class="ti ti-eye-off"></i>
                  </span>
                </div>
              </div>

              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" type="submit">{{ __('common.login') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/jquery/jquery.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/popper/popper.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/js/bootstrap.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/node-waves/node-waves.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/hammer/hammer.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/i18n/i18n.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/typeahead-js/typeahead.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/js/menu.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/@form-validation/popular.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/@form-validation/bootstrap5.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/vendor/libs/@form-validation/auto-focus.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/js/main.js"></script>
		<script src="{{ asset('assets/admin/assets') }}/js/pages-auth.js"></script>
    </body>

</html>
