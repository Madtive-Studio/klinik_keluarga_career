@forelse ($jobs as $job)
	@php
		$jobTypeLabel = \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type;
		$appliedJobIds = $appliedJobIds ?? [];
		$hasApplied = in_array($job->id, $appliedJobIds);
	@endphp
	<div class="col-lg-12">
		<div class="job-box bg-white overflow-hidden border rounded mt-4 position-relative overflow-hidden">

			{{-- MOBILE LAYOUT --}}
			<div class="p-4 d-md-none">
				<div class="row">
					<div class="col-4">
						<img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="img-fluid mx-auto d-block rounded job-zoom-trigger" style="max-width: 100px; cursor: zoom-in;" data-images="{{ json_encode($job->image_urls) }}" data-title="{{ $job->title }}">
					</div>
					<div class="col-8">
						<h5 class="f-18 mb-1"><a href="{{ route('candidate.jobs.vacancies.show', $job->uuid) }}" class="text-dark">{{ $job->title ?? '-' }}</a></h5>
						<p class="text-muted mb-0 small">{{ $job->category->name ?? '-' }}</p>
					</div>
				</div>
				<div class="mt-3 border-top pt-3">
					<p class="text-muted mb-2 small"><i class="mdi mdi-map-marker text-primary me-1"></i>Cianjur, Jawa Barat</p>
					@if ($job->is_show_salary)
						<p class="text-muted mb-2 small"><i class="mdi mdi-currency-usd text-primary me-1"></i>{{ $job->salary_display }}</p>
					@endif
					<p class="text-muted mb-0 small"><i class="mdi mdi-briefcase text-primary me-1"></i>{{ $jobTypeLabel }}</p>
				</div>
			</div>
			<div class="p-3 bg-light d-md-none">
				<p class="text-muted mb-2 small"><i class="mdi mdi-school text-primary me-1"></i>{{ $job->experience ?? '-' }}</p>
				@if ($hasApplied)
					<a href="{{ route('candidate.my.applications.index') }}" class="btn btn-outline-success w-100 btn-sm">
						<i class="mdi mdi-check-circle me-1"></i> {{ __('candidate.jobs.already_applied') }}
					</a>
				@else
					<a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="btn btn-primary w-100 btn-sm">
						{{ __('candidate.jobs.apply_now') }}
					</a>
				@endif
			</div>

			{{-- DESKTOP LAYOUT --}}
			<div class="d-none d-md-block">
				<div class="p-4">
					<div class="row align-items-center">
						<div class="col-md-2">
							<img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="img-fluid mx-auto d-block rounded job-zoom-trigger" style="max-width: 100px; cursor: zoom-in;" data-images="{{ json_encode($job->image_urls) }}" data-title="{{ $job->title }}">
						</div>
						<div class="col-md-3">
							<h5 class="f-18 mb-1"><a href="{{ route('candidate.jobs.vacancies.show', $job->uuid) }}" class="text-dark">{{ $job->title ?? '-' }}</a></h5>
							<p class="text-muted mb-0">{{ $job->category->name ?? '-' }}</p>
						</div>
						<div class="col-md-3">
							<p class="text-muted mb-0"><i class="mdi mdi-map-marker text-primary me-1"></i>Cianjur, Jawa Barat</p>
						</div>
						@if ($job->is_show_salary)
							<div class="col-md-2">
								<p class="text-muted mb-0">{{ $job->salary_display }}</p>
							</div>
						@endif
						<div class="col-md-2">
							<p class="text-muted mb-0">{{ $jobTypeLabel }}</p>
						</div>
					</div>
				</div>
				<div class="p-3 bg-light">
					<div class="row align-items-center">
						<div class="col-md-10">
							<p class="text-muted mb-0">{{ $job->experience ?? '-' }}</p>
						</div>
						<div class="col-md-2 text-end">
							@if ($hasApplied)
								<a href="{{ route('candidate.my.applications.index') }}" class="btn btn-outline-success btn-sm">
									<i class="mdi mdi-check-circle me-1"></i> {{ __('candidate.jobs.already_applied') }}
								</a>
							@else
								<a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="btn btn-primary btn-sm">
									{{ __('candidate.jobs.apply_now') }}
								</a>
							@endif
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
@empty
	<div class="col-lg-12">
		<div class="text-center mt-3">
			<p>No data available</p>
		</div>
	</div>
@endforelse
