@extends('admin.layouts.main')
@section('content')
	<div class="container-fluid flex-grow-1 container-p-y">
		<div class="row g-6">
			@if (!empty($activeBatch))
				<div class="col-md-3">
					<div class="card h-100">
						<div class="card-header pb-3">
							<h5 class="card-title mb-1">Active Batch</h5>
							<p class="card-subtitle">Current batch active</p>
						</div>
						<div class="card-body">
							<div id="ordersLastWeek"></div>
							<div class="d-flex justify-content-between flex-column gap-1">
								<h5 class="mb-0">{{ $activeBatch->code }} - {{ $activeBatch->name }}</h5>
								<small>{{ \Carbon\Carbon::parse($activeBatch->start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($activeBatch->end_date)->translatedFormat('d M Y') }}</small>
							</div>
						</div>
					</div>
				</div>
			@endif
			<div class="col-md-3">
				<div class="card h-100">
					<div class="card-header pb-0">
						<h5 class="card-title mb-1">Job List</h5>
						<p class="card-subtitle">All of job list total</p>
					</div>
					<div id="salesLastYear"></div>
					<div class="card-body pt-0">
						<div class="d-flex justify-content-between align-items-center mt-3 gap-3">
							<h4 class="mb-0">{{ $jobList }}</h4>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card h-100">
					<div class="card-header pb-0">
						<h5 class="card-title mb-1">Applicants</h5>
						<p class="card-subtitle">All of applicants total</p>
					</div>
					<div id="salesLastYear"></div>
					<div class="card-body pt-0">
						<div class="d-flex justify-content-between align-items-center mt-3 gap-3">
							<h4 class="mb-0">{{ $applicants }}</h4>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card h-100">
					<div class="card-header pb-0">
						<h5 class="card-title mb-1">Hired</h5>
						<p class="card-subtitle">All of hired total</p>
					</div>
					<div id="salesLastYear"></div>
					<div class="card-body pt-0">
						<div class="d-flex justify-content-between align-items-center mt-3 gap-3">
							<h4 class="mb-0">{{ $hired }}</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
