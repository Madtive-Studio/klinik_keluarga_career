@if ($navbarType == 'candidate')
	<header id="topnav" class="position-relative shadow-sm">
		<div class="container">
			<div class="">
				<a href="{{ url('/') }}" class="logo">
					<img src="{{ asset('logo.png') }}" alt="" class="logo-light" height="30" />
				</a>
			</div>
			@if (!Auth::guard('candidate')->check())
				<div class="buy-button">
					<a href="{{ route('client.login') }}" class="btn btn-primary"><i class="mdi mdi-login-variant"></i> Masuk</a>
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
					<li><a href="{{ url('/') }}">Beranda</a></li>
					<li><a href="{{ url('/loker') }}">Lowongan Pekerjaan</a></li>
					<li>
						<a href="#footer">Kontak Kami</a>
					</li>
					@if (Auth::guard('candidate')->check())
						<li class="has-submenu"><a href="javascript:void(0)"> <i class="mdi mdi-account"></i> Selamat Datang, {{ auth()->guard('candidate')->user()->name }}</a><span class="submenu-arrow"></span>
							<ul class="submenu">
								<li><a href="{{ route('client.my.apply') }}">Profil Saya</a></li>
								<li><a href="{{ route('client.my.apply') }}">Lamaran Saya</a></li>
								<li><a href="{{ route('client.my.cv') }}">CV / Resume Saya</a></li>
								<li><a href="{{ route('client.logout') }}">Logout</a></li>
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
					<div class="phone">
						<i class="mdi mdi-phone-classic"></i> +62 823 129 876 68
					</div>
					<div class="email">
						<a href="#">
							<i class="mdi mdi-email"></i> madtive@gmail.com
						</a>
					</div>
				</div>
				@if (Auth::guard('candidate')->check())
					<div class="float-right">
						<ul class="topbar-list list-unstyled d-flex" style="margin: 11px 0px;">
							<li class="list-inline-item"><a href="javascript:void(0);"><i class="mdi mdi-account mr-2"></i>{{ auth()->guard('candidate')->user()->name }}</a></li>
						</ul>
					</div>
				@endif
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="container">
			<div>
				<a href="{{ url('/') }}" class="logo">
					<img src="{{ asset('logo-white.png') }}" alt="" class="logo-light" height="30" />
					<img src="{{ asset('logo.png') }}" alt="" class="logo-dark" height="30" />
				</a>
			</div>
			@if (!Auth::guard('candidate')->check())
				<div class="buy-button">
					<a href="{{ route('client.login') }}" class="btn btn-primary"><i class="mdi mdi-login-variant"></i> Masuk</a>
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
					<li><a href="{{ url('/') }}">Beranda</a></li>
					<li><a href="{{ url('/loker') }}">Lowongan Pekerjaan</a></li>
					<li>
						<a href="#footer">Kontak Kami</a>
					</li>
					@if (Auth::guard('candidate')->check())
						<li class="has-submenu"><a href="javascript:void(0)"> <i class="mdi mdi-account"></i> Selamat Datang, {{ auth()->guard('candidate')->user()->name }}</a><span class="submenu-arrow"></span>
							<ul class="submenu">
								<li><a href="{{ route('client.my.apply') }}">Profil Saya</a></li>
								<li><a href="{{ route('client.my.apply') }}">Lamaran Saya</a></li>
								<li><a href="{{ route('client.my.cv') }}">CV / Resume Saya</a></li>
								<li><a href="{{ route('client.logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</header>
@endif
