<div class="col-lg-4 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				<a class="flex-sm-fill nav-link {{ request()->routeIs('candidate.my.cv') ? 'active' : '' }}" aria-current="page" href="{{ route('candidate.my.cv') }}">CV / Resume Saya</a>
				<a class="flex-sm-fill nav-link {{ request()->routeIs('candidate.my.cv.create') ? 'active' : '' }}" aria-current="page" href="{{ route('candidate.my.cv.create') }}">Upload Baru</a>
			</nav>
		</div>
	</div>
</div>
