<div class="col-lg-4 col-md-7">
	<div class="job-detail border rounded p-2 bg-white shadow-sm mb-3">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column gap-1">
				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && empty(request('status')) ? 'active' : ''
				}}" aria-current="page" href="{{ route('candidate.my.applications.index', request()->except(['status', 'page'])) }}">{{ __('candidate.applications.tab_all') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'IN REVIEW' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', array_merge(request()->except('page'), ['status' => 'IN REVIEW'])) }}">{{ __('candidate.applications.tab_in_review') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'SHORTLISTED' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', array_merge(request()->except('page'), ['status' => 'SHORTLISTED'])) }}">{{ __('candidate.applications.tab_offering') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'NOT SUITABLE' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', array_merge(request()->except('page'), ['status' => 'NOT SUITABLE'])) }}">{{ __('candidate.applications.tab_not_suitable') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'HIRED' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', array_merge(request()->except('page'), ['status' => 'HIRED'])) }}">{{ __('candidate.applications.tab_hired') }}</a>
			</nav>
		</div>
	</div>
</div>
