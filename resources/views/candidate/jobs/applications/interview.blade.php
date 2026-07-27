@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('title', __('candidate.applications.interview'))
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.jobs.applications.tab_menu')
				<div class="col-lg-8 col-md-5 mt-4 mt-sm-0">
					<h5>{{ __('candidate.applications.interview_count', ['count' => $interviewsCount]) }}</h5>
					<div class="show-results">
						<div class="sort-button float-start">
							<select class="nice-select rounded" name="urutkan" id="urutkan">
								<option value="">{{ __('candidate.applications.sort_by') }}</option>
								<option value="Terbaru" {{ !empty(request('urutkan')) && request('urutkan') === 'Terbaru' ? 'selected' : '' }}>{{ __('candidate.applications.newest') }}</option>
								<option value="Terlama" {{ !empty(request('urutkan')) && request('urutkan') === 'Terlama' ? 'selected' : '' }}>{{ __('candidate.applications.oldest') }}</option>
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
												<h5 class="mb-0"><a href="#" class="text-dark">{{ __('candidate.applications.interview_title') }} - {{ $interview->is_online ? __('candidate.applications.online') : __('candidate.applications.offline') }}</a></h5>
												<h6 class="mb-0"><a href="#" class="text-muted">{{ $interview->job->code ?? '#' }} - {{ $interview->job->title ?? '-' }}</a></h6>
												<p class="text-muted mb-0">{{ $interview->job->category->name ?? '-' }}
												</p>
												<ul class="list-inline mb-0">
													<li class="list-inline-item me-3">
														<p class="text-muted mb-0"><i class="mdi mdi-calendar me-2"></i>{{ __('candidate.applications.schedule_time') }} : {{ \Carbon\Carbon::parse($interview->start_datetime)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($interview->end_datetime)->format('d/m/Y H:i') }}</p>
														@if ($interview->is_online)
															<p class="text-muted mb-0"><i class="mdi mdi-link me-2"></i>{{ __('candidate.applications.link') }} : <a href="{{ $interview->link }}" target="_blank">{{ $interview->link }}</a></p>
														@else
															<p class="text-muted mb-0"><i class="mdi mdi-map-marker me-2"></i>{{ __('candidate.applications.address') }} : {{ $company->address }}</p>
														@endif
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							<p class="mb-0 text-center">{{ __('common.no_data') }}</p>
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
				window.location.href = '{{ route("candidate.my.applications.index") }}?urutkan=' + orderBy
			})
		})
	</script>
@endsection