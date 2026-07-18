@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', __('candidate.apply.success_title'))
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 text-center">
					<img src="{{ asset('check.png') }}" class="d-block mx-auto mb-4" width="125" alt="">
					<h4 class="text-primary mb-3">{{ __('candidate.apply.success_title') }}</h4>
					<p class="text-muted mb-2">{{ __('candidate.apply.success_message') }}</p>
					<p class="mb-4">
						<strong>{{ $candidate->name }}</strong>
						{{ __('common.dash') }}
						<strong>{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }}</strong>
						{{ __('common.dash') }}
						<strong>{{ $job->title }}</strong>
					</p>
					<div class="d-flex flex-wrap justify-content-center gap-2">
						<a href="{{ route('candidate.my.applications.index') }}" class="btn btn-primary m-1">
							{{ __('candidate.apply.view_applications') }}
						</a>
						<a href="{{ route('candidate.jobs.vacancies.index') }}" class="btn btn-outline-primary m-1">
							{{ __('candidate.apply.back_to_jobs') }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
