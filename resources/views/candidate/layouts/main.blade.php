<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	@include('candidate.layouts.header')
</head>

<body>
	<div id="preloader">
		<div id="status">
			<img src="{{ asset('assets/logo/letter-logo.png') }}" width="250" class="d-block mx-auto" alt="">
			<p class="mt-3 text-center"><strong>Tunggu Sebentar...</strong></p>
		</div>
	</div>

	@include('candidate.layouts.navbar')

	@yield('content')

	@include('candidate.layouts.footer')
</body>

</html>
