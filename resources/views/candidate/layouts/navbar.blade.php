@if ($navbarType == 'candidate')
	<header id="topnav" class="position-relative shadow-sm">
		<div class="container">
			<div class="">
				<a href="{{ route('candidate.home') }}" class="logo">
					<img src="{{ asset('assets/logo/letter-logo.png') }}" alt="" class="logo-light" height="30" />
				</a>
			</div>
			@if (!Auth::guard('candidate')->check())
				<div class="buy-button">
					<a href="{{ route('candidate.login.form') }}" class="btn btn-primary"><i class="mdi mdi-login-variant"></i> Masuk</a>
				</div>
			@endif
			<div class="menu-extras">
				<div class="menu-item">
					<a class="navbar-toggle">
						<div class="lines">
							<span></span>
							<span></span>
							<span></span>
						</div>
					</a>
				</div>
			</div>
			<div id="navigation">
				<ul class="navigation-menu justify-content-end">
					<li><a class="text-dark" href="{{ route('candidate.home') }}">Beranda</a></li>
					<li><a class="text-dark" href="{{ route('candidate.jobs.vacancies.index') }}">Lowongan Pekerjaan</a></li>
					{{-- <li>
						<a class="text-dark" href="#footer">Kontak Kami</a>
					</li> --}}
					@if (Auth::guard('candidate')->check())
						<li class="has-submenu"><a class="text-dark" href="javascript:void(0)"> <i class="mdi mdi-account"></i> Selamat Datang, {{ auth()->guard('candidate')->user()->name }}</a><span class="submenu-arrow"></span>
							<ul class="submenu">
								<li><a class="text-dark" href="">Profil Saya</a></li>
								<li><a class="text-dark" href="{{ route('candidate.my.applications.index') }}">Lamaran Saya</a></li>
								<li><a class="text-dark" href="{{ route('candidate.my.documents.index') }}">Dokumen Saya</a></li>
								<li><a class="text-dark" href="{{ route('candidate.logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</header>
@else
	<header id="topnav" class="defaultscroll scroll-active">
		<div class="tagline">
			<div class="container">
				<div class="float-left">
					<div class="schedule-open">
						<i class="mdi mdi-calendar-blank"></i>  Jadwal Buka : Setiap Hari
					</div>
					<div class="schedule-open-time">
						<i class="mdi mdi-clock-outline"></i>  Pagi: 06.00 - 13.00, Siang: 14.00 - 20.00
					</div>
				</div>
				<div class="float-right">
					<div class="phone">
						<i class="mdi mdi-phone-classic"></i> 0263 513513
					</div>
					<div class="email">
						<a href="#">
							<i class="mdi mdi-email"></i> info@klinikkeluarga.com
						</a>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="container">
			<div>
				<a href="{{ route('candidate.home') }}" class="logo">
					<img src="{{ asset('assets/logo/letter-logo-white.png') }}" alt="" class="logo-light" height="30" />
					<img src="{{ asset('assets/logo/letter-logo.png') }}" alt="" class="logo-dark" height="30" />
				</a>
			</div>
			@if (!Auth::guard('candidate')->check())
				<div class="buy-button">
					<a href="{{ route('candidate.login.form') }}" class="btn btn-primary"><i class="mdi mdi-login-variant"></i> Masuk</a>
				</div>
			@endif
			<div class="menu-extras">
				<div class="menu-item">
					<a class="navbar-toggle">
						<div class="lines">
							<span></span>
							<span></span>
							<span></span>
						</div>
					</a>
				</div>
			</div>
			<div id="navigation">
				<ul class="navigation-menu justify-content-end">
					<li><a href="{{ route('candidate.home') }}">Beranda</a></li>
					<li><a href="{{ route('candidate.jobs.vacancies.index') }}">Lowongan Pekerjaan</a></li>
					{{-- <li>
						<a href="#footer">Kontak Kami</a>
					</li> --}}
					@if (Auth::guard('candidate')->check())
						<li class="has-submenu"><a href="javascript:void(0)"> <i class="mdi mdi-account"></i> Selamat Datang, {{ auth()->guard('candidate')->user()->name }}</a><span class="submenu-arrow"></span>
							<ul class="submenu">
								<li><a href="">Profil Saya</a></li>
								<li><a class="text-dark" href="{{ route('candidate.my.applications.index') }}">Lamaran Saya</a></li>
								<li><a href="{{ route('candidate.my.documents.index') }}">Dokumen Saya</a></li>
								<li><a href="{{ route('candidate.logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</header>
@endif
