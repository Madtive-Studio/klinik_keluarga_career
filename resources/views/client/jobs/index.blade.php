@extends('client.layouts.main', ['navbarType' => 'candidate'])
@section('title', 'Loker')
@section('content')
	<section class="bg-half page-next-level" style="background: url('https://img.freepik.com/premium-photo/workspace-wide-light-office_280538-7380.jpg?semt=ais_hybrid') no-repeat center center; background-size: cover;">
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
						<form class="registration-form" method="GET" action="{{ route('client.job-vacancies.index') }}">
							<div class="row">
								<div class="col-md-4">
									<div class="registration-form-box">
										<i class="fa fa-briefcase"></i>
										<input type="text" name="q"
											class="form-control rounded registration-input-box"
											placeholder="Cari loker...">
									</div>
								</div>
								<div class="col-md-3">
									<div class="registration-form-box">
										<i class="fa fa-list-alt"></i>
										<select id="select-category" name="kategori" class="demo-default">
											<option value="">Kategori...</option>
											@foreach ($categories as $category)
												<option value="{{ $category->id }}"
													{{ isset($selectedCategory) && $selectedCategory == $category->id ? 'selected' : '' }}>
													{{ $category->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="registration-form-box">
										<i class="fa fa-list-alt"></i>
										<select id="select-category" name="jenis" class="demo-default">
											<option value="">Jenis...</option>
											<option value="WFH/Remote">WFH/Remote</option>
											<option value="Fulltime/Onsite">Fulltime/Onsite</option>
											<option value="Partime/Freelancer">Partime/Freelancer</option>
											<option value="Internship">Internship</option>
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
										@forelse ($categories as $key => $category)
											<div class="custom-control custom-radio">
												<input type="radio" id="{{ $category->id }}" name="kategori" class="custom-control-input">
												<label class="custom-control-label ml-1 text-muted f-15" for="{{ $category->id }}">{{ $category->name }}</label>
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
									<h5 class="text-dark mb-0 pt-2 f-18">Menampilkan data dari 0-20</h5>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						@forelse ($jobs as $key => $job)
							<div class="col-lg-12 mt-4 pt-2">
								<div class="job-list-box border rounded">
									<div class="p-3">
										<div class="row align-items-center">
											<div class="col-lg-2">
												<div class="company-logo-img">
													<img src="{{ asset('client/images/job-placeholder.png') }}" width="100" alt=""
														class="img-fluid mx-auto d-block rounded">
												</div>
											</div>
											<div class="col-lg-7 col-md-9">
												<div class="job-list-desc">
													<h6 class="mb-2"><a href="{{ url('loker/' . $job->uuid) }}" class="text-dark">{{ $job->code }} - {{ $job->title }}</a></h6>
													<p class="text-muted mb-0">{{ $job->category->name }}</p>
													<ul class="list-inline mb-0">
														<li class="list-inline-item mr-3">
															<p class="text-muted mb-0"><i class="mdi mdi-map-marker mr-2"></i>Cianjur, Jawa Barat</p>
														</li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-3">
												<div class="job-list-button-sm text-right">
													<span class="badge badge-success">{{ $job->type }}</span>
													<div class="mt-3">
														<a href="{{ url('loker/' . $job->uuid) }}" class="btn btn-sm btn-primary">Lamar Sekarang</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							<p class="text-center mx-auto mt-3">Tidak ada data</p>
						@endforelse
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
