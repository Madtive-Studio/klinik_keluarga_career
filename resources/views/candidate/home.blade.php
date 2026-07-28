@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', __('candidate.home.title'))
@section('content')
	<section class="bg-home">
		<div class="bg-overlay"></div>
		<div class="home-center">
			<div class="home-desc-center">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-10">
							<div class="title-heading text-center text-white">
								<h1 class="heading fw-bold mb-3">{{ __('candidate.home.heading') }}</h1>
								<h6 class="small-title text-light mb-3 px-5">{{ __('candidate.home.subtitle') }}</h6>
								<p class="mb-5">{{ __('candidate.home.active_batch', ['batch' => $formattedBatch]) }}</p>
							</div>
						</div>
					</div>
					<div class="home-form-position">
						<div class="row">
							<div class="col-lg-12">
								<div class="home-registration-form p-4 mb-3">
									<form class="registration-form" method="GET" action="{{ route('candidate.jobs.vacancies.index') }}">
										<div class="row g-2 px-3">
											<div class="col-12 col-md-5">
												<div class="input-group">
													<span class="input-group-text bg-white"><i class="fa fa-briefcase text-muted"></i></span>
													<input type="text" name="q" class="form-control" placeholder="{{ __('candidate.home.search_placeholder') }}">
												</div>
											</div>
											<div class="col-12 col-md-3">
												<div class="input-group">
													<span class="input-group-text bg-white"><i class="fa fa-list-alt text-muted"></i></span>
													<select id="select-category" name="category" class="form-select">
														<option value="">{{ __('candidate.home.category') }}</option>
														@foreach ($categories as $category)
															<option value="{{ $category->id }}"
																{{ isset($selectedCategory) && $selectedCategory == $category->id ? 'selected' : '' }}>
																{{ $category->name }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-12 col-md-2">
												<div class="input-group">
													<span class="input-group-text bg-white"><i class="fa fa-list-alt text-muted"></i></span>
													<select id="select-job-type" name="job_type" class="form-select">
														<option value="">{{ __('candidate.home.type') }}</option>
														<option value="SEMUA">{{ __('common.all') }}</option>
														@foreach ($jobTypes as $value => $label)
															<option value="{{ $value }}" {{ request()->get('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-12 col-md-2">
												<button type="submit" class="btn btn-primary w-100">
													<i class="mdi mdi-filter me-1"></i>{{ __('common.search') }}
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section bg-light">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12">
					<div class="section-title text-center pb-2">
						<h4 class="title title-line pb-5">{{ __('candidate.home.jobs_section') }}</h4>
					</div>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-9 text-center mt-4 pt-2">
					<ul class="nav nav-pills bg-white rounded nav-justified flex-sm-row d-none d-sm-flex" id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link rounded active" id="all-tab" data-bs-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true" data-job-type="All">{{ __('common.all') }}</a>
						</li>
						@foreach ($jobTypes as $value => $label)
							@php $tabId = \Illuminate\Support\Str::slug($value, '-'); @endphp
							<li class="nav-item">
								<a class="nav-link rounded" id="{{ $tabId }}-tab" data-bs-toggle="pill" href="#{{ $tabId }}" role="tab" aria-controls="{{ $tabId }}" aria-selected="false" data-job-type="{{ $value }}">{{ $label }}</a>
							</li>
						@endforeach
					</ul>
					<select class="form-select d-sm-none" id="mobile-job-type">
						<option value="All">{{ __('common.all') }}</option>
						@foreach ($jobTypes as $value => $label)
							<option value="{{ $value }}">{{ $label }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="tab-content mt-2" id="pills-tabContent">
						@php
							$tabSets = ['All' => __('common.all')] + $jobTypes;
						@endphp
						@foreach ($tabSets as $value => $label)
							@php
								$isAll = $value === 'All';
								$tabId = $isAll ? 'all' : \Illuminate\Support\Str::slug($value, '-');
								$jobs = $jobsByType[$value] ?? collect();
							@endphp
							<div class="tab-pane fade {{ $isAll ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel" aria-labelledby="{{ $tabId }}-tab">
								<div class="row jobs-container" id="jobs-container-{{ $tabId }}">
									@if ($isAll)
										@include('candidate.home.section._job_list', ['jobs' => $jobs])
									@else
										<div class="col-lg-12">
											<div class="text-center mt-3 text-muted">{{ __('candidate.home.click_tab_to_load') }}</div>
										</div>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
		<div class="modal-dialog modal-dialog-centered m-0 p-0" style="max-width: 100vw; min-height: 100vh;">
			<div class="modal-content" style="background: rgba(0,0,0,0.15); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: none; border-radius: 0; min-height: 100vh;">
				<button type="button" class="btn-close btn-close-white position-fixed" data-bs-dismiss="modal" aria-label="Close" style="top: 20px; right: 25px; z-index: 1050; font-size: 1.5rem;"></button>
				<div class="modal-body d-flex align-items-center justify-content-center p-0" style="min-height: 100vh;">
					<div id="zoomCarousel" class="carousel slide w-100" data-bs-interval="false">
						<div class="carousel-inner" id="zoomCarouselInner"></div>
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
		const candidateI18n = @json(__('candidate.js'));

		function fetchHomeJobs(jobType, tabId) {
			const container = $('#jobs-container-' + tabId);
			container.html('<div class="col-lg-12"><div class="text-center mt-3 text-muted">' + candidateI18n.loading + '</div></div>');

			$.ajax({
				url: "{{ route('candidate.home.jobs-by-type') }}",
				method: 'GET',
				data: {
					job_type: jobType
				},
				headers: { 'X-Requested-With': 'XMLHttpRequest' },
				success: function(response) {
					container.html(response.html);
				},
				error: function() {
					container.html('<div class="col-lg-12"><div class="text-center mt-3 text-danger">' + candidateI18n.load_failed + '</div></div>');
				}
			});
		}

		$(function() {
			$('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
				const tab = $(e.target);
				const tabId = tab.attr('href').replace('#', '');
				const jobType = tab.data('job-type');
				fetchHomeJobs(jobType, tabId);
			});

			$('#mobile-job-type').change(function() {
				const jobType = $(this).val();
				const tabId = jobType === 'All' ? 'all' : String(jobType).toLowerCase().replace(/\s+/g, '-');
				$('#' + tabId + '-tab').tab('show');
			});

			$(document).on('click', '.job-zoom-trigger', function() {
				var images = $(this).data('images');
				var title = $(this).data('title');
				var inner = $('#zoomCarouselInner');
				inner.empty();
				if (images && images.length) {
					$.each(images, function(i, src) {
						inner.append('<div class="carousel-item' + (i === 0 ? ' active' : '') + '"><img src="' + src + '" alt="' + title + '" class="d-block mx-auto" style="max-width: 90vw; max-height: 90vh; object-fit: contain;"></div>');
					});
				}
				$('#zoomCarousel').carousel(0);
				$('#imageZoomModal').modal('show');
			});

			$('#imageZoomModal').on('hidden.bs.modal', function () {
				$('#zoomCarouselInner').empty();
			});
		});
	</script>
@endsection
