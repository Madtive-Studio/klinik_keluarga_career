<footer class="footer" id="footer">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
				<a href="javascript:void(0)"><img src="{{ asset('assets/logo/letter-logo-white.png') }}" height="30" alt=""></a>
				<p class="mt-4">Kami memiliki komitmen ingin membuat Kota Cianjur sebagai ekosistem IT terbesar, kamu bisa wujudkan impian kamu bersama Madtive Studio!</p>
			</div>
			<div class="col-lg-3 col-md-5 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
				<p class="text-white mb-4 footer-list-title">Perusahaan</p>
				<ul class="list-unstyled footer-list">
					<li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Beranda</a></li>
					<li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Lowongan Pekerjaan</a></li>
					<li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Kontak Kami</a></li>
				</ul>
			</div>
			<div class="col-lg-3 col-md-5 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
				<p class="text-white mb-4 footer-list-title">Kontak Kami</p>
				<ul class="list-unstyled footer-list">
					<li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> +62 823 129 876 68</a></li>
					<li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> madtive@gmail.com</a></li>
				</ul>
			</div>
			<div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
				<p class="text-white mb-4 footer-list-title f-17">Jam Kerja</p>
				<ul class="list-unstyled text-foot mt-4 mb-0">
					<li>Senin - Jumat: 08:00 to 17:00</li>
					<li class="mt-2">Sabtu - Minggu : Libur (Holiday)</li>
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
					<p class="mb-0">© 2024 - {{ date('Y') }} Madtive Studio</p>
				</div>
			</div>
		</div>
	</div>
</footer>
<a href="#" class="back-to-top rounded text-center" id="back-to-top">
	<i class="mdi mdi-chevron-up d-block"> </i>
</a>
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
@yield('js')
