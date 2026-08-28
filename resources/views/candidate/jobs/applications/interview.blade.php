@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.applications.interview'))
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.jobs.applications.tab_menu')
				<div class="col-lg-8 col-md-5 mt-4 mt-sm-0">
					<h5 class="mb-3 fw-bold">{{ __('candidate.applications.interview_count', ['count' => $interviewsCount]) }}</h5>
					<div class="show-results d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
						<div class="sort-button">
							<select class="form-select rounded" name="urutkan" id="urutkan" style="width: auto; min-width: 170px;">
								<option value="">{{ __('candidate.applications.sort_by') }}</option>
								<option value="Terbaru" {{ request('urutkan') === 'Terbaru' ? 'selected' : '' }}>{{ __('candidate.applications.newest') }}</option>
								<option value="Terlama" {{ request('urutkan') === 'Terlama' ? 'selected' : '' }}>{{ __('candidate.applications.oldest') }}</option>
							</select>
						</div>
					</div>
					<div class="jobs-list">
						@forelse ($interviews as $key => $interview)
							<div class="job-list-box mt-3 border rounded shadow-sm bg-white">
								<div class="p-3">
									<div class="row align-items-center">
										<div class="col-4 col-md-2">
											<div class="company-logo-img text-center">
												<img src="{{ $interview->job->image_url }}" alt="{{ $interview->job->title ?? 'Job' }}"
													class="img-fluid mx-auto d-block rounded" style="max-height: 80px; object-fit: contain;">
											</div>
										</div>
										<div class="col-8 col-md-10">
											<div class="job-list-desc">
												<div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
													<h6 class="mb-0 fw-bold text-dark">{{ __('candidate.applications.interview_title') }}</h6>
													<span class="badge {{ $interview->is_online ? 'bg-info' : 'bg-success' }}">
														{{ $interview->is_online ? __('candidate.applications.online') : __('candidate.applications.offline') }}
													</span>
												</div>
												<p class="text-muted small mb-1">{{ $interview->job->code ?? '#' }} - {{ $interview->job->title ?? '-' }} ({{ $interview->job->category->name ?? '-' }})</p>
												<ul class="list-inline mb-0">
													<li class="list-inline-item me-3">
														<p class="text-muted small mb-0"><i class="mdi mdi-calendar me-1"></i>{{ __('candidate.applications.schedule_time') }}: <strong>{{ \Carbon\Carbon::parse($interview->start_datetime)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($interview->end_datetime)->format('d/m/Y H:i') }}</strong></p>
														@if ($interview->is_online)
															<p class="text-muted small mb-0 mt-1"><i class="mdi mdi-link me-1"></i>{{ __('candidate.applications.link') }}: <a href="{{ $interview->link }}" target="_blank" class="text-primary fw-semibold">{{ $interview->link }}</a></p>
														@else
															<p class="text-muted small mb-0 mt-1"><i class="mdi mdi-map-marker me-1"></i>{{ __('candidate.applications.address') }}: {{ $company->address ?? '-' }}</p>
														@endif
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							<div class="text-center py-5 bg-white border rounded">
								<p class="mb-0 text-muted fs-6">{{ __('common.no_data') }}</p>
							</div>
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
				let orderBy = $(this).val();
				let url = new URL(window.location.href);
				if (orderBy) {
					url.searchParams.set('urutkan', orderBy);
				} else {
					url.searchParams.delete('urutkan');
				}
				window.location.href = url.toString();
			});
		});
	</script>
@endsection