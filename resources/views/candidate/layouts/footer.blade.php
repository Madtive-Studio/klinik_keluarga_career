<footer class="footer" id="footer">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
				<a href="javascript:void(0)"><img src="{{ asset('assets/logo/letter-logo-white.png') }}" height="30" alt=""></a>
				<p class="mt-4">{{ __('candidate.footer.about') }}</p>
			</div>
			{{-- <div class="col-lg-1 col-md-5 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0"> --}}
			{{-- </div> --}}
			<div class="col-lg-4 col-md-5 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0 ps-3">
				<p class="text-white mb-4 footer-list-title">{{ __('candidate.footer.contact_title') }}</p>
				<p class="mt-4">Kampung Cigombong No. 64 Rt.01/09 <br> Kecamatan Pacet, Cianjur <br> Jawa Barat - Indonesia, 43253</p>

				<ul class="list-unstyled footer-list">
					<li><a href="#" class="text-foot"><i class="mdi mdi-phone"></i> 0263 513513</a></li>
					<li><a href="mailto:karirklinikkeluarga@gmail.com" class="text-foot"><i class="mdi mdi-email"></i> karirklinikkeluarga@gmail.com</a></li>
				</ul>
			</div>
			<div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
				<p class="text-white mb-4 footer-list-title f-17">{{ __('candidate.footer.hours_title') }}</p>
				<ul class="list-unstyled text-foot mt-4 mb-0">
					<li><i class="mdi mdi-clock-outline"></i> {{ __('candidate.footer.hours_days') }}</li>
					<li class="ms-3">{{ __('candidate.footer.hours_morning') }}</li>
					<li class="ms-3">{{ __('candidate.footer.hours_afternoon') }}</li>
				</ul>
			</div>
		</div>
	</div>
</footer>
<!-- footer end -->
<hr>
<footer class="footer footer-bar">
	<div class="container text-center">
		<div class="row justify-content-center">
			<div class="col-12">
				<div class="">
					<p class="mb-0">{{ __('candidate.footer.copyright', ['year' => date('Y')]) }}</p>
				</div>
			</div>
		</div>
	</div>
</footer>
<a href="#" class="back-to-top rounded text-center" id="back-to-top">
	<i class="mdi mdi-chevron-up d-block"> </i>
</a>
<script>window.appLocale = @json(app()->getLocale());</script>
<script src="{{ asset('assets/candidate/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/plugins.js') }}"></script>
<script src="{{ asset('assets/candidate/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/selectize.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/flatpickr-id.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/app.js') }}"></script>
<script src="{{ asset('assets/candidate/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/selectize.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/counter.int.js') }}"></script>
<script src="{{ asset('assets/candidate/js/home.js') }}"></script>
<script src="{{ asset('assets/candidate/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/candidate/js/ux-animations.js') }}"></script>
@if(session('error'))
    <div style="display:none" id="debug-session" data-error="{{ session('error') }}"></div>
    <script>
        console.log('Session error:', document.getElementById('debug-session').dataset.error);
    </script>
@endif

@yield('js')
