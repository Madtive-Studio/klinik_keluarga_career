@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', 'Loker')
@section('content')
	<section class="bg-half page-next-level">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="text-center text-white">
						<h4 class="text-uppercase title mb-4">Lowongan Pekerjaan</h4>
						<ul class="page-next d-inline-block mb-0">
							<li><a href="#" class="text-uppercase font-weight-bold">Beranda</a></li>
							<li>
								<span class="text-uppercase text-white font-weight-bold">Cari Lowongan Pekerjaan</span>
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
								<div class="col-md-7">
									<div class="registration-form-box">
										<i class="fa fa-briefcase"></i>
										<input type="text" name="q" value="{{ request()->get('q') }}" class="form-control rounded registration-input-box" placeholder="Cari loker...">
									</div>
								</div>
								<div class="col-md-3">
									<div class="registration-form-box">
										<i class="fa fa-list-alt"></i>
										<select id="select-category" name="job_type" class="demo-default">
											<option value="SEMUA">Semua</option>
											@foreach ($jobTypes as $value => $label)
												<option value="{{ $value }}" {{ request()->get('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="registration-form-box">
										<button type="submit" id="submit" class="submitBtn btn btn-primary btn-block">
											<i class="mdi mdi-filter text-white"></i>&nbsp;&nbsp;&nbsp;Cari
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
								<a data-toggle="collapse" href="#collapsetwo" class="job-list" aria-expanded="true"
									aria-controls="collapsetwo">
									<div class="card-header" id="headingtwo">
										<h6 class="mb-0 text-dark f-18">Filter berdasarkan <br> kategori</h6>
									</div>
								</a>
								<div id="collapsetwo" class="collapse show" aria-labelledby="headingtwo">
									<div class="card-body p-0">
										<div class="custom-control custom-radio">
											<input type="radio" id="category_0" name="category_id" value="SEMUA" class="custom-control-input category-filter" {{ !request('category') ? 'checked' : '' }}>
											<label class="custom-control-label ml-1 text-muted f-15" for="category_0">
												Semua
											</label>
										</div>
										@forelse ($categories as $key => $category)
											<div class="custom-control custom-radio">
												<input type="radio" id="category_{{ $category->id }}" name="category_id" value="{{ $category->id }}" class="custom-control-input category-filter" {{ request('category') == $category->id ? 'checked' : '' }}>
												<label class="custom-control-label ml-1 text-muted f-15" for="category_{{ $category->id }}">
													{{ $category->name }}
												</label>
											</div>
										@empty
											<p class="text-center mx-auto">Tidak ada data</p>
										@endforelse
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
								<div class="float-left">
									<h5 class="text-dark mb-0 pt-2 f-18 info-showing">
										Menampilkan data dari 1 - {{ request()->get('per_page', 10) }}
									</h5>
								</div>

								<div class="float-right">
									<div class="form-inline">
										<label class="mr-2">Tampilkan:</label>
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

					{{-- Pagination Links --}}
					<div id="pagination-container" class="mt-4 d-flex justify-content-center">
						{{ $jobs->appends(request()->query())->links('pagination::bootstrap-4') }}
					</div>
					
					{{-- Info Pagination --}}
					<div class="mt-2 text-center text-muted small info-showing">
						Menampilkan {{ $jobs->firstItem() }} - {{ $jobs	->lastItem() }} dari {{ $jobs->total() }} lowongan pekerjaan
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
			params.set('category', $('input[name="category_id"]:checked').val() ?? '');
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
						'Menampilkan ' + response.firstItem + ' - ' + response.lastItem + ' dari ' + response.total + ' lowongan'
					);
					window.history.pushState({}, '', '?' + params.toString());
				}
			});
		}
		// Klik pagination pakai fetchJobsOnPage
		$(document).on('click', '#pagination-container a', function(e) {
			e.preventDefault();
			let page = new URL($(this).attr('href')).searchParams.get('page');
			fetchJobs(page);
		});

		// Submit form
		$('#filter-form').submit(function(e) {
			e.preventDefault();
			fetchJobs();
		});

		// Category filter
		$('.category-filter').change(function() {
			fetchJobs();
		});

		// Per page
		$('#perPage').change(function() {
			fetchJobs();
		});

		fetchJobs();
	</script>
@endsection
