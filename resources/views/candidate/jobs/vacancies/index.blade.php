@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.jobs.title'))
@section('content')
	<section class="bg-half page-next-level">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="text-center text-white">
						<h4 class="text-uppercase title mb-4">{{ __('candidate.jobs.list_title') }}</h4>
						<ul class="page-next d-inline-block mb-0">
							<li><a href="#" class="text-uppercase fw-bold">{{ __('candidate.nav.home') }}</a></li>
							<li>
								<span class="text-uppercase text-white fw-bold">{{ __('candidate.jobs.search_jobs') }}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="container">
		<div class="home-form-position">
			<div class="row justify-content-center">
				<div class="col-lg-10">
					<div class="home-registration-form job-list-reg-form bg-light shadow p-4 mb-0">
						<form id="filter-form" class="registration-form">
							<div class="row">
								<div class="col-md-5">
									<div class="registration-form-box">
										<i class="fa fa-briefcase"></i>
										<input type="text" name="q" value="{{ request()->get('q') }}" class="form-control rounded registration-input-box" placeholder="{{ __('candidate.home.search_placeholder') }}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="registration-form-box">
										<i class="fa fa-list-alt"></i>
										<select id="select-job-type" name="job_type" class="form-control">
											<option value="SEMUA">{{ __('candidate.applications.tab_all') }}</option>
											@foreach ($jobTypes as $value => $label)
												<option value="{{ $value }}" {{ request()->get('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="registration-form-box">
										<i class="fa fa-list-alt"></i>
										<select id="select-category" name="category" class="form-control">
											<option value="SEMUA">{{ __('candidate.applications.tab_all') }}</option>
											@foreach ($categories as $category)
												<option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="registration-form-box">
										<button type="submit" id="submit" class="submitBtn btn btn-primary w-100">
											<i class="mdi mdi-filter text-white"></i>&nbsp;&nbsp;{{ __('common.search') }}
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
								<div id="collapseSalary" class="collapse" aria-labelledby="headingSalary">
									<div class="card-body">
										<div class="mb-2">
											<label class="text-muted small">{{ __('candidate.jobs.salary_min') }}: <span id="salary_min_display" class="fw-bold">{{ request('salary_min') ? 'IDR '.number_format((int) request('salary_min'), 0, ',', '.') : 'IDR 0' }}</span></label>
											<input type="range" id="filter_salary_min" class="form-range" min="0" max="50000000" step="500000" value="{{ request('salary_min') ?: 0 }}">
											<input type="hidden" id="salary_min_raw" name="salary_min" value="{{ request('salary_min') }}">
										</div>
										<div class="mb-2">
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
								<div id="collapseEducation" class="collapse" aria-labelledby="headingEducation">
									<div class="card-body p-0">
										<div class="form-check">
											<input type="radio" id="education_0" name="min_education" value="" class="form-check-input education-filter" {{ !request('min_education') ? 'checked' : '' }}>
											<label class="form-check-label ms-2 text-muted f-15" for="education_0">{{ __('candidate.applications.tab_all') }}</label>
										</div>
										@foreach ($educationLevels as $level)
											<div class="form-check">
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
							<div class="show-results">
								<div class="float-start">
									<h5 class="text-dark mb-0 pt-2 f-18 info-showing">
										{{ __('common.showing_range', ['count' => request()->get('per_page', 10)]) }}
									</h5>
								</div>

								<div class="float-end">
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