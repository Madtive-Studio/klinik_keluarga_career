@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.jobs.title'))
@section('content')
	<style>
		.vacancies-search-wrapper {
			position: relative;
			z-index: 10;
			margin-top: -55px;
		}
		.vacancies-search-card {
			background: #ffffff !important;
			border-radius: 14px;
			box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12) !important;
			border: 1px solid rgba(226, 232, 240, 0.9);
			transition: all 0.3s ease;
		}
		.vacancies-search-card .input-group-text {
			background-color: #f8fafc;
			border-color: #e2e8f0;
			border-radius: 8px 0 0 8px;
		}
		.vacancies-search-card .form-control,
		.vacancies-search-card .form-select {
			background-color: #f8fafc;
			border-color: #e2e8f0;
			border-radius: 0 8px 8px 0;
			font-size: 14px;
		}
		.vacancies-search-card .form-control:focus,
		.vacancies-search-card .form-select:focus {
			background-color: #ffffff;
			border-color: #2f55d4;
			box-shadow: 0 0 0 3px rgba(47, 85, 212, 0.15);
		}
		.vacancies-search-card .btn-search-vacancies {
			border-radius: 8px;
			padding: 10px 16px;
			font-weight: 600;
			box-shadow: 0 4px 12px rgba(47, 85, 212, 0.25);
			transition: all 0.2s ease-in-out;
		}
		.vacancies-search-card .btn-search-vacancies:hover {
			transform: translateY(-1px);
			box-shadow: 0 6px 16px rgba(47, 85, 212, 0.35);
		}
		@media (max-width: 768px) {
			.vacancies-search-wrapper {
				margin-top: -35px;
			}
		}
	</style>
	<section class="bg-half page-next-level">
		<div class="bg-overlay"></div>
		<div class="container" style="position: relative; z-index: 2;">
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="text-center text-white">
						<h4 class="text-uppercase title mb-4">{{ __('candidate.jobs.list_title') }}</h4>
						<ul class="page-next d-inline-block mb-0">
							<li><a href="{{ route('candidate.home') }}" class="text-uppercase fw-bold">{{ __('candidate.nav.home') }}</a></li>
							<li>
								<span class="text-uppercase text-white fw-bold">{{ __('candidate.jobs.search_jobs') }}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="container vacancies-search-wrapper mb-4">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="vacancies-search-card p-3 p-md-4">
					<form id="filter-form" class="registration-form">
						<div class="row g-2 align-items-center">
							<div class="col-12 col-md-5">
								<div class="input-group">
									<span class="input-group-text"><i class="fa fa-briefcase text-muted"></i></span>
									<input type="text" name="q" value="{{ request()->get('q') }}" class="form-control" placeholder="{{ __('candidate.home.search_placeholder') }}">
								</div>
							</div>
							<div class="col-12 col-md-3">
								<div class="input-group">
									<span class="input-group-text"><i class="fa fa-list-alt text-muted"></i></span>
									<select id="select-job-type" name="job_type" class="form-select">
										<option value="SEMUA">{{ __('candidate.applications.tab_all') }}</option>
										@foreach ($jobTypes as $value => $label)
											<option value="{{ $value }}" {{ request()->get('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-12 col-md-2">
								<div class="input-group">
									<span class="input-group-text"><i class="fa fa-list-alt text-muted"></i></span>
									<select id="select-category" name="category" class="form-select">
										<option value="SEMUA">{{ __('candidate.applications.tab_all') }}</option>
										@foreach ($categories as $category)
											<option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-12 col-md-2">
								<button type="submit" class="btn btn-primary btn-search-vacancies w-100">
									<i class="mdi mdi-filter me-1"></i>{{ __('common.search') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<section class="section pt-0">
		<div class="container">
			<div class="row">
				<div class="col-lg-3">
					<div class="left-sidebar">
						<div class="accordion" id="accordionExample">
							<div class="card rounded mt-4">
								<a data-bs-toggle="collapse" href="#collapseSalary" class="job-list" aria-expanded="false">
									<div class="card-header" id="headingSalary">
										<h6 class="mb-0 text-dark f-18"><i class="mdi mdi-currency-usd me-1"></i>{{ __('candidate.jobs.salary_range') }}</h6>
									</div>
								</a>
								<div id="collapseSalary" class="" aria-labelledby="headingSalary">
									<div class="card-body">
										<div class="mb-2 px-2">
											<label class="text-muted small">{{ __('candidate.jobs.salary_min') }}: <span id="salary_min_display" class="fw-bold">{{ request('salary_min') ? 'IDR '.number_format((int) request('salary_min'), 0, ',', '.') : 'IDR 0' }}</span></label>
											<input type="range" id="filter_salary_min" class="form-range" min="0" max="50000000" step="500000" value="{{ request('salary_min') ?: 0 }}">
											<input type="hidden" id="salary_min_raw" name="salary_min" value="{{ request('salary_min') }}">
										</div>
										<div class="mb-2 px-2">
											<label class="text-muted small">{{ __('candidate.jobs.salary_max') }}: <span id="salary_max_display" class="fw-bold">{{ request('salary_max') ? 'IDR '.number_format((int) request('salary_max'), 0, ',', '.') : 'IDR 50.000.000' }}</span></label>
											<input type="range" id="filter_salary_max" class="form-range" min="0" max="50000000" step="500000" value="{{ request('salary_max') ?: 50000000 }}">
											<input type="hidden" id="salary_max_raw" name="salary_max" value="{{ request('salary_max') }}">
										</div>
									</div>
								</div>
							</div>

							<div class="card rounded mt-4">
								<a data-bs-toggle="collapse" href="#collapseEducation" class="job-list" aria-expanded="false">
									<div class="card-header" id="headingEducation">
										<h6 class="mb-0 text-dark f-18"><i class="mdi mdi-school me-1"></i>{{ __('candidate.jobs.min_education') }}</h6>
									</div>
								</a>
								<div id="collapseEducation" class="" aria-labelledby="headingEducation">
									<div class="card-body">
										<div class="form-check px-3">
											<input type="radio" id="education_0" name="min_education" value="" class="form-check-input education-filter" {{ !request('min_education') ? 'checked' : '' }}>
											<label class="form-check-label ms-2 text-muted f-15" for="education_0">{{ __('candidate.applications.tab_all') }}</label>
										</div>
										@foreach ($educationLevels as $level)
											<div class="form-check px-3">
												<input type="radio" id="education_{{ $level->value }}" name="min_education" value="{{ $level->value }}" class="form-check-input education-filter" {{ request('min_education') === $level->value ? 'checked' : '' }}>
												<label class="form-check-label ms-2 text-muted f-15" for="education_{{ $level->value }}">{{ $level->label() }}</label>
											</div>
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-9 mt-4 pt-2">
					<div class="row align-items-center">
						<div class="col-lg-12">
							<div class="show-results d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div>
									<h5 class="text-dark mb-0 f-18 info-showing">
										{{ __('common.showing_range', ['count' => request()->get('per_page', 10)]) }}
									</h5>
								</div>

								<div>
									<div class="d-flex align-items-center">
										<label class="me-2">{{ __('candidate.jobs.show') }}:</label>
										<select id="perPage" name="per_page" class="form-control form-control-sm" style="width: auto;">
											<option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
											<option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
											<option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div id="job-list-container">
					</div>

					<div id="pagination-container" class="mt-4 d-flex justify-content-center">
						{{ $jobs->appends(request()->query())->links('pagination::bootstrap-5') }}
					</div>

					<div class="mt-2 text-center text-muted small info-showing">
						{{ __('candidate.js.showing_jobs', ['from' => $jobs->firstItem(), 'to' => $jobs->lastItem(), 'total' => $jobs->total()]) }}
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('js')
	<script>
		function getParams() {
			let params = new URLSearchParams(window.location.search);
			params.set('q', $('input[name="q"]').val());
			params.set('job_type', $('select[name="job_type"]').val());
			params.set('category', $('select[name="category"]').val());
			params.set('salary_min', $('#salary_min_raw').val());
			params.set('salary_max', $('#salary_max_raw').val());
			params.set('min_education', $('input[name="min_education"]:checked').val() ?? '');
			params.set('per_page', $('#perPage').val());
			return params;
		}

		function fetchJobs(page = 1) {
			let params = getParams();
			if(page) {
				params.set('page', page); 
			}

			$.ajax({
				url: "{{ route('candidate.jobs.vacancies.index') }}",
				data: params.toString(),
				headers: { 'X-Requested-With': 'XMLHttpRequest' },
				success: function(response) {
					$('#job-list-container').html(response.html);
					$('#pagination-container').html(response.pagination);
					$('.info-showing').text(
						'{{ __("candidate.js.showing_jobs", ["from" => ":from", "to" => ":to", "total" => ":total"]) }}'
							.replace(':from', response.firstItem)
							.replace(':to', response.lastItem)
							.replace(':total', response.total)
					);
					window.history.pushState({}, '', '?' + params.toString());
				}
			});
		}

		$(document).on('click', '#pagination-container a', function(e) {
			e.preventDefault();
			let page = new URL($(this).attr('href')).searchParams.get('page');
			fetchJobs(page);
		});

		$('#filter-form').submit(function(e) {
			e.preventDefault();
			fetchJobs();
		});

		$('#select-job-type, #select-category').change(function() {
			fetchJobs();
		});

		$('.education-filter').change(function() {
			fetchJobs();
		});

		let salaryTimer;
		$('#filter_salary_min, #filter_salary_max').on('input', function() {
			var val = $(this).val();
			$(this).siblings('input[type="hidden"]').val(val);
			var display = $(this).closest('.mb-2').find('span.fw-bold');
			display.text('IDR ' + new Intl.NumberFormat('id-ID').format(val));
			clearTimeout(salaryTimer);
			salaryTimer = setTimeout(fetchJobs, 500);
		});

		$('#perPage').change(function() {
			fetchJobs();
		});

		fetchJobs();
	</script>
@endsection