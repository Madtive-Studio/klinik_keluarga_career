<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">

<head>
	@include('candidate.layouts.header')
</head>

<body class="candidate-portal">
	<div id="preloader">
		<div id="status">
			<img src="{{ asset('assets/logo/letter-logo.png') }}" width="250" class="d-block mx-auto" alt="">
			<p class="mt-3 text-center"><strong>{{ __('common.loading') }}</strong></p>
		</div>
	</div>

	@include('candidate.layouts.announcement-ticker')
	@include('candidate.layouts.navbar')

	@yield('content')

	@include('candidate.layouts.footer')
</body>

</html>
