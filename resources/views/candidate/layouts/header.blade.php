<meta charset="UTF-8">
<title>@yield('title', 'Karir') | {{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Karir | Klinik Keluarga" />
<meta property="og:description" content="Portal Rekrutmen dan Karir Resmi Klinik Keluarga - Bergabunglah bersama kami untuk memberikan pelayanan kesehatan terbaik dan profesional." />
<meta property="og:url" content="{{ url('/') }}" />
<meta property="og:site_name" content="Klinik Keluarga Career" />
<meta name="description" content="Portal Rekrutmen dan Karir Resmi Klinik Keluarga - Bergabunglah bersama kami untuk memberikan pelayanan kesehatan terbaik dan profesional.">
<meta name="keywords" content="Klinik Keluarga, Karir Klinik Keluarga, Lowongan Kerja Kesehatan, Rekrutmen Medis">
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
<link rel="stylesheet" href="{{ asset('assets/css/cursor-interactions.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/candidate/css/ux-animations.css') }}" />
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
