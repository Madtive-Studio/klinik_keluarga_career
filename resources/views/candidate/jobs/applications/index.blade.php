@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', 'Lamaran Saya')
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.lamaran-saya.tab-menu')
				<div class="col-lg-8 col-md-5 mt-4 mt-sm-0">
					<h5>{{ $appliesCount }} lamaran dalam setahun terakhir</h5>
					<div class="show-results">
						<div class="sort-button float-left">
							<select class="nice-select rounded" name="urutkan" id="urutkan">
								<option value="">Tampilkan Berdasarkan</option>
								<option value="Terbaru" {{ !empty(request('urutkan')) && request('urutkan') === 'Terbaru' ? 'selected' : '' }}>Paling Baru</option>
								<option value="Terlama" {{ !empty(request('urutkan')) && request('urutkan') === 'Terlama' ? 'selected' : '' }}>Paling Lama</option>
							</select>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="jobs-list">
						@forelse ($applies as $key => $apply)
							<div class="job-list-box mt-3 border rounded">
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
												<h6 class="mb-0"><a href="#" class="text-dark">{{ $apply->job->code ?? '#' }} - {{ $apply->job->title ?? '-' }}</a></h6>
												<p class="text-muted mb-0">{{ $apply->job->category->name ?? '-' }}
												</p>
												<ul class="list-inline mb-0">
													<li class="list-inline-item mr-3">
														<p class="text-muted mb-0"><i class="mdi mdi-calendar mr-2"></i>Dikirim pada {{ date('d M Y H:i:s', strtotime($apply->created_at)) }}</p>
													</li>
												</ul>
												<span class="badge badge-primary mt-2">Dilamar</span>
											</div>
										</div>
										<div class="col-lg-3 col-md-3">
											<div class="job-list-button-sm text-right">
												<span class="badge badge-success">{{ strtoupper($apply->status) }}</span>
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
			$(document).on('change', '#urutkan', function() {
				let orderBy = $(this).find('option:selected').val()
				window.location.href = 'lamaran-saya?urutkan=' + orderBy
			})
		})
	</script>
@endsection
