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
								<h1 class="heading font-weight-bold mb-3">{{ __('candidate.home.heading') }}</h1>
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
										<div class="row">
											<div class="col-md-5">
												<div class="registration-form-box">
													<i class="fa fa-briefcase"></i>
													<input type="text" name="q" class="form-control rounded registration-input-box" placeholder="{{ __('candidate.home.search_placeholder') }}">
												</div>
											</div>
											<div class="col-md-3">
												<div class="registration-form-box">
													<i class="fa fa-list-alt"></i>
													<select id="select-category" name="category" class="demo-default">
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
											<div class="col-md-2">
												<div class="registration-form-box">
													<i class="fa fa-list-alt"></i>
													<select id="select-category" name="job_type" class="demo-default">
														<option value="">{{ __('candidate.home.type') }}</option>
														<option value="SEMUA">{{ __('common.all') }}</option>
														@foreach ($jobTypes as $value => $label)
															<option value="{{ $value }}" {{ request()->get('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="registration-form-box">
													<button type="submit" id="submit" class="submitBtn btn btn-primary btn-block">
														<i class="mdi mdi-filter text-white"></i>&nbsp;&nbsp;&nbsp;{{ __('common.search') }}
													</button>
												</div>
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
					<ul class="nav nav-pills nav nav-pills bg-white rounded nav-justified flex-column flex-sm-row" id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link rounded active" id="all-tab" data-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true" data-job-type="All">{{ __('common.all') }}</a>
						</li>
						@foreach ($jobTypes as $value => $label)
							@php $tabId = \Illuminate\Support\Str::slug($value, '-'); @endphp
							<li class="nav-item">
								<a class="nav-link rounded" id="{{ $tabId }}-tab" data-toggle="pill" href="#{{ $tabId }}" role="tab" aria-controls="{{ $tabId }}" aria-selected="false" data-job-type="{{ $value }}">{{ $label }}</a>
							</li>
						@endforeach
					</ul>
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
			$('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
				const tab = $(e.target);
				const tabId = tab.attr('href').replace('#', '');
				const jobType = tab.data('job-type');
				fetchHomeJobs(jobType, tabId);
			});
		});
	</script>
@endsection
