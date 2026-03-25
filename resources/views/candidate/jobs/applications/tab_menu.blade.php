<div class="col-lg-4 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				{{-- Semua --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applications.index') && empty(request('status')) ? 'active' : '' 
				}}" aria-current="page" href="{{ route('candidate.my.applications.index') }}">Semua</a>
				
				{{-- Dalam review --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applications.index') && request('status') === 'IN REVIEW' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'IN REVIEW']) }}">Dalam review</a>
				
				{{-- Tahap offering --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applications.index') && request('status') === 'SHORTLISTED' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'SHORTLISTED']) }}">Tahap offering</a>
				
				{{-- Belum sesuai --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applications.index') && request('status') === 'NOT SUITABLE' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'NOT SUITABLE']) }}">Belum sesuai</a>
				
				{{-- Diterima --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applications.index') && request('status') === 'HIRED' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applications.index', ['status' => 'HIRED']) }}">Diterima</a>
				
				{{-- Wawancara --}}
				{{-- <a class="flex-sm-fill nav-link {{ request()->routeIs('candidate.my.interview') ? 'active' : '' 
				}}" href="{{ route('candidate.my.interview') }}">Wawancara</a> --}}
			</nav>
		</div>
	</div>
</div>
