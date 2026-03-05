@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', 'CV Saya')
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.cv-saya.tab-menu')
				<div class="col-lg-8 col-md-5 mt-sm-0">
					<div class="jobs-list">
						@forelse ($cvs as $key => $cv)
							<div class="job-list-box mb-3 border rounded">
								<div class="p-3">
									<div class="row align-items-center">
										<div class="col-lg-2">
											<div class="company-logo-img">
												<img src="{{ asset('assets/candidate/images/file-placeholder.jpg') }}" width="50" alt="" class="img-fluid mx-auto d-block rounded">
											</div>
										</div>
										<div class="col-lg-7 col-md-9">
											<div class="job-list-desc">
												<h6 class="mb-0"><a href="#" class="text-dark">{{ $cv->name ?? '-' }}</a></h6>
												<ul class="list-inline mb-0">
													<li class="list-inline-item mr-3">
														<p class="text-muted mb-0"><i class="mdi mdi-calendar mr-2"></i>Diupload pada {{ date('d M Y H:i:s', strtotime($cv->created_at)) }}</p>
													</li>
												</ul>
											</div>
										</div>
										<div class="col-lg-3 col-md-3">
											<div class="job-list-button-sm text-right">
												<a href="{{ Illuminate\Support\Facades\Storage::url($cv->file) }}" target="_blank" download="" class="btn btn-primary btn-sm" type="button">
													<i class="mdi mdi-download"></i> Unduh
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							<p class="mb-0 text-center">Belum ada data.</p>
						@endforelse
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('js')
	<script>
		$(function() {
			$('.form_add_new_cv').hide()
			$(document).on('click', '#add_new_cv', function() {
				$('.form_add_new_cv').toggle()
			})
		})
	</script>
@endsection
