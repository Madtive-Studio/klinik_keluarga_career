@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', __('candidate.jobs.detail_title'))
@section('content')
	<section class="bg-half page-next-level">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<div class="text-center text-white">
						<h4 class="text-uppercase title mb-4">{{ $job->code }} - {{ $job->title }}</h4>
						<ul class="page-next d-inline-block mb-0">
							<li><a href="#" class="text-uppercase font-weight-bold">{{ __('candidate.nav.home') }}</a></li>
							<li>
								<span class="text-uppercase text-white">{{ __('candidate.nav.jobs') }}</span>
							</li>
							<li>
								<span class="text-uppercase text-white font-weight-bold">{{ $job->code }} - {{ $job->title }}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section pt-5">
		<div class="container">
			@include('layouts.alert-section')
			<div class="row">
				<div class="col-lg-8 col-md-7">
					<div class="job-detail border rounded p-4">
						<div class="job-detail-content">
							@if (count($job->image_urls) > 1)
								<div class="d-flex flex-wrap gap-2 mb-3">
									@foreach ($job->image_urls as $imageUrl)
										<img src="{{ $imageUrl }}" alt="{{ $job->title }}" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
									@endforeach
								</div>
							@else
								<img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="img-fluid float-left mr-md-3 mr-2 mx-auto d-block">
							@endif
							<div class="job-detail-com-desc overflow-hidden d-block">
								<h4 class="mb-2"><a href="#" class="text-dark">{{ $job->code }} - {{ $job->title }}</a></h4>
								<p class="text-muted mb-0"><i class="mdi mdi-link-variant mr-2"></i>{{ $job->category->name }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-laptop mr-2"></i>{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }} | {{ $job->experience }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-account mr-2"></i>{{ __('candidate.jobs.applicants_count', ['count' => $formattedAppliesTotal]) }}</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<h5 class="text-dark mt-4">{{ __('candidate.jobs.description') }}</h5>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="job-detail border rounded mt-2 p-4">
								<div class="job-detail-desc">
									{!! $job->description !!}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<h5 class="text-dark mt-4">{{ __('candidate.jobs.qualification') }}</h5>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="job-detail border rounded mt-2 p-4">
								<div class="job-detail-desc">
									{!! $job->qualification !!}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-5 mt-4 mt-sm-0">
					<div class="job-detail border rounded p-4">
						<h5 class="text-muted text-center pb-2"><i class="mdi mdi-info mr-2"></i>{{ __('candidate.jobs.information') }}</h5>
						<div class="job-detail-location pt-4 border-top">
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-clock-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ $activeBatch->name }} | {{ date('d M Y', strtotime($activeBatch->start_date)) }} - {{ date('d M Y', strtotime($activeBatch->end_date)) }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-laptop text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-information-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ $job->experience }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-account text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ __('candidate.apply.quota_people', ['count' => $job->quota]) }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-currency-usd text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ $job->is_show_salary ? $job->salary_display : __('candidate.apply.salary_not_stated') }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-clock-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ __('candidate.apply.weekdays') }}</p>
							</div>
						</div>
					</div>
					@php
						$eligibility = $applyEligibility ?? [
							'can_apply' => true,
							'already_applied' => false,
							'education_not_met' => false,
							'profile_incomplete' => false,
							'min_education_label' => null,
							'candidate_education_label' => null,
						];
						$isLoggedIn = auth('candidate')->check();
					@endphp
					<div class="job-detail border rounded mt-4 p-3">
						@if (!$isLoggedIn)
							<a href="{{ route('candidate.login.form') }}" class="btn btn-primary btn-block">
								{{ __('candidate.jobs.login_to_apply') }}
							</a>
						@elseif ($eligibility['already_applied'])
							<button type="button" class="btn btn-secondary btn-block" disabled>
								{{ __('candidate.jobs.already_applied') }}
							</button>
							<small class="text-muted d-block mt-2 text-center">{!! __('messages.application.already_applied_html', ['url' => route('candidate.my.applications.index')]) !!}</small>
						@elseif ($eligibility['profile_incomplete'])
							<a href="{{ route('candidate.my.profile.edit') }}" class="btn btn-warning btn-block">
								{{ __('candidate.jobs.complete_profile_to_apply') }}
							</a>
							<small class="text-muted d-block mt-2 text-center">{{ __('messages.application.complete_profile_first') }}</small>
						@elseif ($eligibility['education_not_met'])
							<button type="button" class="btn btn-secondary btn-block" disabled>
								{{ __('candidate.jobs.apply_now') }}
							</button>
							<small class="text-danger d-block mt-2 text-center">{{ __('messages.application.education_not_met', [
								'required' => $eligibility['min_education_label'],
								'current' => $eligibility['candidate_education_label'],
							]) }}</small>
						@else
							<a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="btn btn-primary btn-block">
								{{ __('candidate.jobs.apply_now') }}
							</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
