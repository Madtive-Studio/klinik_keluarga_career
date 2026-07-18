@if ($navbarType == 'candidate')
	<header id="topnav" class="position-relative shadow-sm">
		<div class="container">
			<div class="">
				<a href="{{ route('candidate.home') }}" class="logo">
					<img src="{{ asset('assets/logo/letter-logo.png') }}" alt="" class="logo-light" height="30" />
				</a>
			</div>
			<div class="d-flex align-items-center buy-button gap-2">
				@include('layouts.locale-switcher', ['class' => 'me-2'])
				@if (!Auth::guard('candidate')->check())
					<a href="{{ route('candidate.login.form') }}" class="btn btn-primary"><i class="mdi mdi-login-variant"></i> {{ __('candidate.nav.login') }}</a>
				@endif
			</div>
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
					<li><a class="text-dark" href="{{ route('candidate.home') }}">{{ __('candidate.nav.home') }}</a></li>
					<li><a class="text-dark" href="{{ route('candidate.jobs.vacancies.index') }}">{{ __('candidate.nav.jobs') }}</a></li>
					@if (Auth::guard('candidate')->check())
						<li class="has-submenu"><a class="text-dark" href="javascript:void(0)"> <i class="mdi mdi-account"></i> {{ __('candidate.nav.welcome', ['name' => auth()->guard('candidate')->user()->name]) }}</a><span class="submenu-arrow"></span>
							<ul class="submenu">
								<li><a class="text-dark" href="{{ route('candidate.my.profile.edit') }}">{{ __('candidate.nav.my_profile') }}</a></li>
								<li><a class="text-dark" href="{{ route('candidate.my.applications.index') }}">{{ __('candidate.nav.my_applications') }}</a></li>
								<li><a class="text-dark" href="{{ route('candidate.my.documents.index') }}">{{ __('candidate.nav.my_documents') }}</a></li>
								<li><a class="text-dark" href="{{ route('candidate.logout') }}">{{ __('common.logout') }}</a></li>
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
						<i class="mdi mdi-calendar-blank"></i> {{ __('candidate.nav.schedule_open') }}
					</div>
					<div class="schedule-open-time">
						<i class="mdi mdi-clock-outline"></i> {{ __('candidate.nav.schedule_time') }}
					</div>
				</div>
				<div class="float-right">
					<div class="phone">
						<i class="mdi mdi-phone-classic"></i>0263 513513
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
			<div class="d-flex align-items-center buy-button gap-2">
				@include('layouts.locale-switcher', ['class' => 'me-2'])
				@if (!Auth::guard('candidate')->check())
					<a href="{{ route('candidate.login.form') }}" class="btn btn-primary"><i class="mdi mdi-login-variant"></i> {{ __('candidate.nav.login') }}</a>
				@endif
			</div>
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
					<li><a href="{{ route('candidate.home') }}">{{ __('candidate.nav.home') }}</a></li>
					<li><a href="{{ route('candidate.jobs.vacancies.index') }}">{{ __('candidate.nav.jobs') }}</a></li>
					@if (Auth::guard('candidate')->check())
						<li class="has-submenu"><a href="javascript:void(0)"> <i class="mdi mdi-account"></i> {{ __('candidate.nav.welcome', ['name' => auth()->guard('candidate')->user()->name]) }}</a><span class="submenu-arrow"></span>
							<ul class="submenu">
								<li><a href="{{ route('candidate.my.profile.edit') }}">{{ __('candidate.nav.my_profile') }}</a></li>
								<li><a class="text-dark" href="{{ route('candidate.my.applications.index') }}">{{ __('candidate.nav.my_applications') }}</a></li>
								<li><a href="{{ route('candidate.my.documents.index') }}">{{ __('candidate.nav.my_documents') }}</a></li>
								<li><a href="{{ route('candidate.logout') }}">{{ __('common.logout') }}</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</header>
@endif
