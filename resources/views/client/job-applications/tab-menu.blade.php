<div class="col-lg-4 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				<a class="flex-sm-fill nav-link {{ request()->is('lamaran-saya') && empty(request('status')) ? 'active' : '' }}" aria-current="page" href="{{ url('lamaran-saya') }}">Semua</a>
				<a class="flex-sm-fill nav-link {{ !empty(request('status')) && request('status') === 'IN REVIEW' ? 'active' : '' }}" href="{{ url('lamaran-saya?status=IN REVIEW') }}">Dalam review</a>
				<a class="flex-sm-fill nav-link {{ !empty(request('status')) && request('status') === 'SHORTLISTED' ? 'active' : '' }}" href="{{ url('lamaran-saya?status=SHORTLISTED') }}">Tahap offering</a>
				<a class="flex-sm-fill nav-link {{ !empty(request('status')) && request('status') === 'NOT SUITABLE' ? 'active' : '' }}" href="{{ url('lamaran-saya?status=NOT SUITABLE') }}">Belum sesuai</a>
				<a class="flex-sm-fill nav-link {{ !empty(request('status')) && request('status') === 'HIRED' ? 'active' : '' }}" href="{{ url('lamaran-saya?status=HIRED') }}">Diterima</a>
				<a class="flex-sm-fill nav-link {{ request()->is('wawancara-saya') ? 'active' : '' }}" href="{{ url('wawancara-saya') }}">Wawancara</a>
			</nav>
		</div>
	</div>
</div>
