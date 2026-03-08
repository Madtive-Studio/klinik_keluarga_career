<footer class="content-footer footer bg-footer-theme">
	<div class="container-fluid">
		<div
			class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
			<div class="text-body">
				©
				2026 -
				<script>
					document.write(new Date().getFullYear());
				</script>
				, made with ❤️ by <a href="https://madtive.com" target="_blank" class="footer-link">Madtive Studio</a>
			</div>
		</div>
	</div>
</footer>

@section('scripts')
	<script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/hammer/hammer.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/i18n/i18n.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/swiper/swiper.js') }}"></script>
	<script src="{{ asset('admin/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
	<script src="{{ asset('admin/assets/js/main.js') }}"></script>
	<script src="{{ asset('admin/assets/js/dashboards-analytics.js') }}"></script>
	@yield('js')
@endsection
