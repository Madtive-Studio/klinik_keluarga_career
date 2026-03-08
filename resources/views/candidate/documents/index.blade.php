@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', 'Dokumen Saya')
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.documents.tab_menu')
				<div class="col-lg-8 col-md-5 mt-sm-0">
					<h5 class="mb-3">Total : {{ $candidate->documents_count ?? 0 }} Dokumen</h5>
					<div class="jobs-list">
						@forelse ($candidate->documents as $key => $document)
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
												<h6 class="mb-0">
													<a href="#" class="text-dark">
														{{ $document->name ?? '-' }}
														<span class="badge {{ $document->type_badge }}">
															{{ $document->type_label }}
														</span>
													</a>
												</h6>
												<ul class="list-inline mb-0">
													<li class="list-inline-item mr-3">
														<p class="text-muted mb-0"><i class="mdi mdi-calendar mr-2"></i>Diupload {{ $document->created_at->diffForHumans() }}</p>
													</li>
												</ul>
											</div>
										</div>
										<div class="col-lg-3 col-md-3">
											<div class="job-list-button-sm text-right">
												<a href="{{ $document->file_url }}" target="_blank" download="{{ $document->name }}" class="btn btn-primary btn-sm" type="button">
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
