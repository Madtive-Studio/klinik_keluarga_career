<meta charset="UTF-8">
<title>@yield('title', 'Karir') | {{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0, user-scalable=yes">
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

	/* Mobile menu toggle styles */
	@media (max-width: 768px) {
		.navbar-toggle {
			display: block;
			cursor: pointer;
		}

		.lines {
			display: flex;
			flex-direction: column;
			gap: 5px;
			width: 24px;
		}

		.lines span {
			height: 3px;
			background-color: #3B4858;
			border-radius: 2px;
			transition: all 0.3s ease;
			display: block;
		}

		.navbar-toggle.active .lines span:nth-child(1) {
			transform: rotate(45deg) translate(10px, 10px);
		}

		.navbar-toggle.active .lines span:nth-child(2) {
			opacity: 0;
		}

		.navbar-toggle.active .lines span:nth-child(3) {
			transform: rotate(-45deg) translate(7px, -7px);
		}
	}

	@media (min-width: 769px) {
		.navbar-toggle {
			display: none;
		}
	}
</style>

<script>
	// Mobile menu toggle
	document.addEventListener('DOMContentLoaded', function() {
		const navbarToggle = document.querySelector('.navbar-toggle');
		const navigationMenu = document.getElementById('navigation');

		if (navbarToggle && navigationMenu) {
			navbarToggle.addEventListener('click', function(e) {
				e.preventDefault();
				this.classList.toggle('active');
				navigationMenu.classList.toggle('show');
			});

			// Close menu when clicking on a link
			const menuLinks = navigationMenu.querySelectorAll('a');
			menuLinks.forEach(link => {
				link.addEventListener('click', function() {
					navbarToggle.classList.remove('active');
					navigationMenu.classList.remove('show');
				});
			});

			// Handle submenu toggle on mobile
			const submenuItems = navigationMenu.querySelectorAll('.has-submenu > a');
			submenuItems.forEach(item => {
				item.addEventListener('click', function(e) {
					if (window.innerWidth <= 768) {
						e.preventDefault();
						const submenu = this.nextElementSibling;
						submenu.classList.toggle('show');
					}
				});
			});

			// Close menu on window resize
			window.addEventListener('resize', function() {
				if (window.innerWidth > 768) {
					navbarToggle.classList.remove('active');
					navigationMenu.classList.remove('show');
					navigationMenu.querySelectorAll('.submenu').forEach(m => m.classList.remove('show'));
				}
			});
		}
	});
</script>
