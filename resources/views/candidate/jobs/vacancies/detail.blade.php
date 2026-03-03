@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', 'Detail Loker')
@section('content')
	<section class="bg-half page-next-level" style="background: url('https://img.freepik.com/premium-photo/workspace-wide-light-office_280538-7380.jpg?semt=ais_hybrid') no-repeat center center; background-size: cover;">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<div class="text-center text-white">
						<h4 class="text-uppercase title mb-4">{{ $job->code }} - {{ $job->title }}</h4>
						<ul class="page-next d-inline-block mb-0">
							<li><a href="#" class="text-uppercase font-weight-bold">Beranda</a></li>
							<li>
								<span class="text-uppercase text-white">Lowongan Pekerjaan</span>
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
			@if ($message = Session::get('has_applied'))
				<div class="alert alert-primary" role="alert">
					<p class="mb-0">Kamu sudah melamar lowongan pekerjaan ini.</p>
				</div>
			@endif
			<div class="row">
				<div class="col-lg-8 col-md-7">
					<div class="job-detail border rounded p-4">
						<div class="job-detail-content">
							<img src="images/featured-job/img-4.png" alt="" class="img-fluid float-left mr-md-3 mr-2 mx-auto d-block">
							<div class="job-detail-com-desc overflow-hidden d-block">
								<h4 class="mb-2"><a href="#" class="text-dark">{{ $job->code }} - {{ $job->title }}</a></h4>
								<p class="text-muted mb-0"><i class="mdi mdi-link-variant mr-2"></i>{{ $job->category->name }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-laptop mr-2"></i>{{ $job->type }} | {{ $job->experience }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-account mr-2"></i>{{ $formattedAppliesTotal }} orang melamar pekerjaan ini</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<h5 class="text-dark mt-4">Deskripsi Pekerjaan :</h5>
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
							<h5 class="text-dark mt-4">Kualifikasi :</h5>
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
						<h5 class="text-muted text-center pb-2"><i class="mdi mdi-info mr-2"></i>Informasi</h5>
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
								<p class="text-muted mb-2">{{ $job->type }}</p>
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
								<p class="text-muted mb-2">{{ $job->quota }} orang (Kuota)</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-currency-usd text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ $job->is_show_salary ? $job->salary : 'Tidak disebutkan' }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-clock-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">: Senin - Jum'at</p>
							</div>
						</div>
					</div>
					<div class="job-detail border rounded mt-4">
						<a href="{{ url('loker/' . $job->uuid . '/lamar') }}" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#exampleModal">
							Lamar Sekarang
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
