<div class="col-lg-3 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				<a class="flex-sm-fill nav-link {{ request()->routeIs('candidate.my.documents.index') ? 'active' : '' }}" aria-current="page" href="{{ route('candidate.my.documents.index') }}">{{ __('candidate.documents.tab_cv') }}</a>
				<a class="flex-sm-fill nav-link {{ request()->routeIs('candidate.my.documents.create') ? 'active' : '' }}" aria-current="page" href="{{ route('candidate.my.documents.create') }}">{{ __('candidate.documents.tab_upload') }}</a>
			</nav>
		</div>
	</div>
</div>
