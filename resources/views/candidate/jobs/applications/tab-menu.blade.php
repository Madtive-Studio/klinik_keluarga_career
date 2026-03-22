<div class="col-lg-4 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				{{-- Semua --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applies') && empty(request('status')) ? 'active' : '' 
				}}" aria-current="page" href="{{ route('candidate.my.applies') }}">Semua</a>
				
				{{-- Dalam review --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applies') && request('status') === 'IN REVIEW' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applies', ['status' => 'IN REVIEW']) }}">Dalam review</a>
				
				{{-- Tahap offering --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applies') && request('status') === 'SHORTLISTED' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applies', ['status' => 'SHORTLISTED']) }}">Tahap offering</a>
				
				{{-- Belum sesuai --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applies') && request('status') === 'NOT SUITABLE' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applies', ['status' => 'NOT SUITABLE']) }}">Belum sesuai</a>
				
				{{-- Diterima --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.applies') && request('status') === 'HIRED' ? 'active' : '' 
				}}" href="{{ route('candidate.my.applies', ['status' => 'HIRED']) }}">Diterima</a>
				
				{{-- Wawancara --}}
				<a class="flex-sm-fill nav-link {{ 
					request()->routeIs('candidate.my.interview') ? 'active' : '' 
				}}" href="{{ route('candidate.my.interview') }}">Wawancara</a>
			</nav>
		</div>
	</div>
</div>
