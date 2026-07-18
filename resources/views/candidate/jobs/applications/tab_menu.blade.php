<div class="col-lg-4 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && empty(request('status')) ? 'active' : ''
				}}" aria-current="page" href="{{ route('candidate.my.applications.index') }}">{{ __('candidate.applications.tab_all') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'IN REVIEW' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'IN REVIEW']) }}">{{ __('candidate.applications.tab_in_review') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'SHORTLISTED' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'SHORTLISTED']) }}">{{ __('candidate.applications.tab_offering') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'NOT SUITABLE' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'NOT SUITABLE']) }}">{{ __('candidate.applications.tab_not_suitable') }}</a>

				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.applications.index') && request('status') === 'HIRED' ? 'active' : ''
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'HIRED']) }}">{{ __('candidate.applications.tab_hired') }}</a>
			</nav>
		</div>
	</div>
</div>
