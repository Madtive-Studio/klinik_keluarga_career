@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', 'Beranda')
@section('content')
	<section class="bg-home">
		<div class="bg-overlay"></div>
		<div class="home-center">
			<div class="home-desc-center">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-10">
							<div class="title-heading text-center text-white">
								<h1 class="heading font-weight-bold mb-3">Bergabunglah Bersama Kami!</h1>
								<h6 class="small-title text-light mb-3 px-5">Kami memiliki komitmen ingin membuat Kota Cianjur sebagai ekosistem IT terbesar, kamu bisa wujudkan impian kamu bersama Madtive Studio!</h6>
								<p class="mb-5">Aktif Batch : {{ $formattedBatch }}</p>
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
													<input type="text" name="q" class="form-control rounded registration-input-box" placeholder="Cari loker...">
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
											<div class="col-md-2">
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
			</div>
		</div>
	</section>
	<section class="section bg-light">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12">
					<div class="section-title text-center pb-2">
						<h4 class="title title-line pb-5">Lowongan Pekerjaan</h4>
					</div>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-9 text-center mt-4 pt-2">
					<ul class="nav nav-pills nav nav-pills bg-white rounded nav-justified flex-column flex-sm-row"
						id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link rounded active" id="all-tab" data-toggle="pill" href="#all"
								role="tab" aria-controls="all" aria-selected="true">Semua</a>
						</li>
						<li class="nav-item">
							<a class="nav-link rounded" id="fulltime-tab" data-toggle="pill" href="#fulltime"
								role="tab" aria-controls="fulltime" aria-selected="false">Fulltime/Onsite</a>
						</li>
						<li class="nav-item">
							<a class="nav-link rounded" id="internship-tab" data-toggle="pill" href="#internship" role="tab"
								aria-controls="internship" aria-selected="false">Internship</a>
						</li>
						<li class="nav-item">
							<a class="nav-link rounded" id="wfh-tab" data-toggle="pill" href="#wfh" role="tab"
								aria-controls="wfh" aria-selected="false">WFH/Remote</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="tab-content mt-2" id="pills-tabContent">
						<div class="tab-pane fade show active" id="all-tab" role="tabpanel"
							aria-labelledby="all-tab-tab">
							<div class="row">
								@forelse ($jobsByType['All'] as $job)
									<div class="col-lg-12">
										<div class="job-box bg-white overflow-hidden border rounded mt-4 position-relative overflow-hidden">
											<div class="p-4">
												<div class="row align-items-center">
													<div class="col-md-2">
														<div class="mo-mb-2">
															<img src="{{ asset('assets/candidate/images/job-placeholder.png') }}" width="100" alt=""
																class="img-fluid mx-auto d-block rounded">
														</div>
													</div>
													<div class="col-md-3">
														<div>
															<h5 class="f-18"><a href="{{ route('candidate.jobs.vacancies.show', $job->uuid) }}" class="text-dark">{{ $job->title ?? '-' }}</a>
															</h5>
															<p class="text-muted mb-0">{{ $job->category->name ?? '-' }}</p>
														</div>
													</div>
													<div class="col-md-3">
														<div>
															<p class="text-muted mb-0"><i class="mdi mdi-map-marker text-primary mr-2"></i>Cianjur, Jawa Barat</p>
														</div>
													</div>
													@if ($job->is_show_salary)
														<div class="col-md-2">
															<div>
																<p class="text-muted mb-0 mo-mb-2">{{ $job->salary }}</p>
															</div>
														</div>
													@endif
													<div class="col-md-2">
														<div>
															<p class="text-muted mb-0">{{ $job->type ?? '-' }}</p>
														</div>
													</div>
												</div>
											</div>
											<div class="p-3 bg-light">
												<div class="row">
													<div class="col-md-10">
														<div>
															<p class="text-muted mb-0 mo-mb-2">{{ $job->experience ?? '-' }}</p>
														</div>
													</div>
													<div class="col-md-2">
														<div>
															<a href="{{ route('candidate.jobs.vacancies.apply',  $job->uuid) }}" class="text-primary"><strong>Lamar Sekarang</strong> <i class="mdi mdi-chevron-double-right"></i></a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="col-lg-12">
										<div class="text-center mt-3">
											<p>No data available</p>
										</div>
									</div>
								@endforelse
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
