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
							<li><a href="#" class="text-uppercase fw-bold">{{ __('candidate.nav.home') }}</a></li>
							<li>
								<span class="text-uppercase text-white">{{ __('candidate.nav.jobs') }}</span>
							</li>
							<li>
								<span class="text-uppercase text-white fw-bold">{{ $job->code }} - {{ $job->title }}</span>
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
							@if (count($job->image_urls) > 0)
								<div id="jobDetailCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
									<ol class="carousel-indicators">
										@foreach ($job->image_urls as $index => $imageUrl)
											<li data-bs-target="#jobDetailCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
										@endforeach
									</ol>
									<div class="carousel-inner rounded">
										@foreach ($job->image_urls as $index => $imageUrl)
											<div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
												<img src="{{ $imageUrl }}" alt="{{ $job->title }}" class="d-block w-100" style="max-height: 400px; object-fit: cover; cursor: zoom-in;" data-slide="{{ $index }}">
											</div>
										@endforeach
									</div>
									<a class="carousel-control-prev" href="#jobDetailCarousel" role="button" data-bs-slide="prev">
										<span class="carousel-control-prev-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Previous</span>
									</a>
									<a class="carousel-control-next" href="#jobDetailCarousel" role="button" data-bs-slide="next">
										<span class="carousel-control-next-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Next</span>
									</a>
								</div>
							@endif
							<div class="job-detail-com-desc overflow-hidden d-block">
								<h4 class="mb-2"><a href="#" class="text-dark">{{ $job->code }} - {{ $job->title }}</a></h4>
								<p class="text-muted mb-0"><i class="mdi mdi-link-variant me-2"></i>{{ $job->category->name }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-laptop me-2"></i>{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }} | {{ $job->experience }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-account me-2"></i>{{ __('candidate.jobs.applicants_count', ['count' => $formattedAppliesTotal]) }}</p>
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
						<h5 class="text-muted text-center pb-2"><i class="mdi mdi-info me-2"></i>{{ __('candidate.jobs.information') }}</h5>
						<div class="job-detail-location pt-4 border-top">
							<div class="job-details-desc-item">
								<div class="float-start me-2">
									<i class="mdi mdi-clock-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ $activeBatch?->name ?? '-' }} | {{ $activeBatch ? date('d M Y', strtotime($activeBatch->start_date)) . ' - ' . date('d M Y', strtotime($activeBatch->end_date)) : '-' }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-start me-2">
									<i class="mdi mdi-laptop text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-start me-2">
									<i class="mdi mdi-information-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ $job->experience }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-start me-2">
									<i class="mdi mdi-account text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ __('candidate.apply.quota_people', ['count' => $job->quota]) }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-start me-2">
									<i class="mdi mdi-currency-usd text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ $job->is_show_salary ? $job->salary_display : __('candidate.apply.salary_not_stated') }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-start me-2">
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
							<a href="{{ route('candidate.login.form') }}" class="btn btn-primary w-100">
								{{ __('candidate.jobs.login_to_apply') }}
							</a>
						@elseif ($eligibility['already_applied'])
							<button type="button" class="btn btn-secondary w-100" disabled>
								{{ __('candidate.jobs.already_applied') }}
							</button>
							<small class="text-muted d-block mt-2 text-center">{!! __('messages.application.already_applied_html', ['url' => route('candidate.my.applications.index')]) !!}</small>
						@elseif ($eligibility['profile_incomplete'])
							<a href="{{ route('candidate.my.profile.edit') }}" class="btn btn-warning w-100">
								{{ __('candidate.jobs.complete_profile_to_apply') }}
							</a>
							<small class="text-muted d-block mt-2 text-center">{{ __('messages.application.complete_profile_first') }}</small>
						@elseif ($eligibility['education_not_met'])
							<button type="button" class="btn btn-secondary w-100" disabled>
								{{ __('candidate.jobs.apply_now') }}
							</button>
							<small class="text-danger d-block mt-2 text-center">{{ __('messages.application.education_not_met', [
								'required' => $eligibility['min_education_label'],
								'current' => $eligibility['candidate_education_label'],
							]) }}</small>
						@else
							<a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="btn btn-primary w-100">
								{{ __('candidate.jobs.apply_now') }}
							</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
<div class="modal fade" id="imageZoomModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered m-0 p-0" style="max-width: 100vw; min-height: 100vh;" role="document">
			<div class="modal-content" style="background: rgba(0,0,0,0.92); border: none; border-radius: 0; min-height: 100vh;">
				<button type="button" class="btn-close btn-close-white position-fixed" data-bs-dismiss="modal" aria-label="Close" style="top: 20px; right: 25px; z-index: 1050; font-size: 1.5rem;">
				</button>
				<div class="modal-body d-flex align-items-center justify-content-center p-0" style="min-height: 100vh;">
					<div id="zoomCarousel" class="carousel slide w-100" data-bs-ride="carousel" data-interval="false">
						<div class="carousel-inner">
							@foreach ($job->image_urls as $index => $imageUrl)
								<div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
									<img src="{{ $imageUrl }}" alt="{{ $job->title }}" class="d-block mx-auto" style="max-width: 90vw; max-height: 90vh; object-fit: contain;">
								</div>
							@endforeach
						</div>
						<a class="carousel-control-prev" href="#zoomCarousel" role="button" data-bs-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</a>
						<a class="carousel-control-next" href="#zoomCarousel" role="button" data-bs-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
	<script>
		$(document).ready(function() {
			$('#jobDetailCarousel .carousel-item img').on('click', function() {
				var slideIndex = $(this).data('slide');
				$('#zoomCarousel').carousel(slideIndex);
				$('#imageZoomModal').modal('show');
			});

			$('#imageZoomModal').on('hidden.bs.modal', function () {
				$('#zoomCarousel').carousel(0);
			});
		});
	</script>
@endsection
