<div class="col-lg-4 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				<a class="flex-sm-fill nav-link {{ request()->is('cv-saya') ? 'active' : '' }}" aria-current="page" href="{{ url('cv-saya') }}">CV / Resume Saya</a>
				<a class="flex-sm-fill nav-link {{ request()->is('cv-saya/tambah') ? 'active' : '' }}" aria-current="page" href="{{ url('cv-saya/tambah') }}">Upload Baru</a>
			</nav>
		</div>
	</div>
</div>
