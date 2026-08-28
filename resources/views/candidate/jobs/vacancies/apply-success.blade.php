@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.apply.success_title'))
@section('content')
	<section class="section py-5">
		<div class="container py-4">
			<div class="row justify-content-center">
				<div class="col-lg-7 col-md-9 text-center">
					<div class="card border rounded shadow-sm p-4 p-md-5 bg-white">
						<img src="{{ asset('check.png') }}" class="d-block mx-auto mb-3" width="110" alt="Success">
						<h4 class="text-primary fw-bold mb-3">{{ __('candidate.apply.success_title') }}</h4>
						<p class="text-muted mb-3">{{ __('candidate.apply.success_message') }}</p>
						<div class="alert alert-light border mb-4 py-2 px-3">
							<span class="text-dark fw-bold">{{ $candidate->name }}</span>
							<span class="text-muted mx-2">|</span>
							<span class="badge bg-success">{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }}</span>
							<span class="text-muted mx-2">|</span>
							<span class="text-dark fw-semibold">{{ $job->title }}</span>
						</div>
						<div class="d-flex flex-wrap justify-content-center gap-2">
							<a href="{{ route('candidate.my.applications.index') }}" class="btn btn-primary px-4">
								<i class="mdi mdi-clipboard-text-search-outline me-1"></i> {{ __('candidate.apply.view_applications') }}
							</a>
							<a href="{{ route('candidate.jobs.vacancies.index') }}" class="btn btn-outline-primary px-4">
								<i class="mdi mdi-arrow-left me-1"></i> {{ __('candidate.apply.back_to_jobs') }}
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
