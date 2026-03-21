<div class="col-lg-3 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				<a class="flex-sm-fill nav-link {{ request()->routeIs('candidate.my.documents.index') ? 'active' : '' }}" aria-current="page" href="{{ route('candidate.my.documents.index') }}">CV / Resume Saya</a>
				<a class="flex-sm-fill nav-link {{ request()->routeIs('candidate.my.documents.create') ? 'active' : '' }}" aria-current="page" href="{{ route('candidate.my.documents.create') }}">Upload Baru</a>
			</nav>
		</div>
	</div>
</div>
