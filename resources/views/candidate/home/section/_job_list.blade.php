@forelse ($jobs as $job)
	<div class="col-lg-12">
		<div class="job-box bg-white overflow-hidden border rounded mt-4 position-relative overflow-hidden">
			<div class="p-4">
				<div class="row align-items-center">
					<div class="col-md-2">
						<div class="mo-mb-2">
							<img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="img-fluid mx-auto d-block rounded" style="max-width: 100px;">
						</div>
					</div>
					<div class="col-md-3">
						<div>
							<h5 class="f-18"><a href="{{ route('candidate.jobs.vacancies.show', $job->uuid) }}" class="text-dark">{{ $job->title ?? '-' }}</a></h5>
							<p class="text-muted mb-0">{{ $job->category->name ?? '-' }}</p>
						</div>
					</div>
					<div class="col-md-3">
						<div>
							<p class="text-muted mb-0"><i class="mdi mdi-map-marker text-primary mr-2"></i>Cianjur, Jawa Barat</p>
						</div>
					</div>
					@if ($job->is_show_salary)
						<div class="col-md-2">
							<div>
								<p class="text-muted mb-0 mo-mb-2">{{ $job->salary_display }}</p>
							</div>
						</div>
					@endif
					<div class="col-md-2">
						<div>
							<p class="text-muted mb-0">{{ $job->type ?? '-' }}</p>
						</div>
					</div>
				</div>
			</div>
			<div class="p-3 bg-light">
				<div class="row">
					<div class="col-md-10">
						<div>
							<p class="text-muted mb-0 mo-mb-2">{{ $job->experience ?? '-' }}</p>
						</div>
					</div>
					<div class="col-md-2">
						<div>
							<a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="text-primary"><strong>{{ __('candidate.jobs.apply_now') }}</strong> <i class="mdi mdi-chevron-double-right"></i></a>
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
