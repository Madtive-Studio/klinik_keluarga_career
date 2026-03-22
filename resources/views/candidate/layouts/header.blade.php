<meta charset="UTF-8">
<title>@yield('title', 'Karir') | {{ config('app.name') }}</title>
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
<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/fontawesome.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/selectize.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/candidate/css/owl.carousel.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/candidate/css/owl.theme.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/candidate/css/owl.transitions.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/style.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/nice-select.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/selectize.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/flatpickr.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/candidate/css/sweetalert2.min.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<style>
	/* #topnav .navigation-menu>li>a {
		color: #3B4858 !important;
		font-family: 'Quicksand', sans-serif;
	} */

	#topnav .navigation-menu>li>a,
	#topnav .navigation-menu>li .submenu li a {
		font-family: 'Quicksand', sans-serif;
	}

	#topnav .navigation-menu>li>a:hover {
		color: #3B4858;
	}
</style>
