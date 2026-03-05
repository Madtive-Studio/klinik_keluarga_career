@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', 'Interview')
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.jobs.applications.tab-menu')
				<div class="col-lg-8 col-md-5 mt-4 mt-sm-0">
					<h5>{{ $interviewsCount }} Tahap Wawancara dalam setahun terakhir</h5>
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
						@forelse ($interviews as $key => $interview)
							<div class="job-list-box mt-3 border rounded">
								<div class="p-3">
									<div class="row align-items-center">
										<div class="col-lg-2">
											<div class="company-logo-img">
												<img src="{{ asset('client/images/job-placeholder.png') }}" width="100" alt=""
													class="img-fluid mx-auto d-block rounded">
											</div>
										</div>
										<div class="col-lg-10 col-md-9">
											<div class="job-list-desc">
												<h5 class="mb-0"><a href="#" class="text-dark">Undangan Wawancara - {{ $interview->is_online ? 'Online' : 'Offline' }}</a></h5>
												<h6 class="mb-0"><a href="#" class="text-muted">{{ $interview->job->code ?? '#' }} - {{ $interview->job->title ?? '-' }}</a></h6>
												<p class="text-muted mb-0">{{ $interview->job->category->name ?? '-' }}
												</p>
												<ul class="list-inline mb-0">
													<li class="list-inline-item mr-3">
														<p class="text-muted mb-0"><i class="mdi mdi-calendar mr-2"></i>Waktu : {{ \Carbon\Carbon::parse($interview->start_datetime)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($interview->end_datetime)->format('d/m/Y H:i') }}</p>
														@if ($interview->is_online)
															<p class="text-muted mb-0"><i class="mdi mdi-link mr-2"></i>Link : <a href="{{ $interview->link }}" target="_blank">{{ $interview->link }}</a></p>
														@else
															<p class="text-muted mb-0"><i class="mdi mdi-map-marker mr-2"></i>Alamat : {{ $company->address }}</p>
														@endif
													</li>
												</ul>
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
				window.location.href = 'wawancara-saya?urutkan=' + orderBy
			})
		})
	</script>
@endsection
