<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	@include('client.layouts.header')
</head>

<body>
	<div id="preloader">
		<div id="status">
			<img src="{{ asset('logo.png') }}" width="250" class="d-block mx-auto" alt="">
			<p class="mt-3 text-center"><strong>Tunggu Sebentar...</strong></p>
		</div>
	</div>

	@include('client.layouts.navbar')

	@yield('content')

	@include('client.layouts.footer')
</body>

</html>
