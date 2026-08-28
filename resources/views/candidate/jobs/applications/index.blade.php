@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.applications.title'))
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.jobs.applications.tab_menu')
				<div class="col-lg-8 col-md-5 mt-4 mt-sm-0">
					<h5 class="mb-3 fw-bold">{{ __('common.total') }} : {{ __('candidate.applications.total_applies', ['count' => $applies->total()]) }}</h5>
					<div class="show-results d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
						<div class="sort-button">
							<select class="form-select rounded" id="sortedBy" style="width: auto; min-width: 170px;">
								<option value="">{{ __('candidate.applications.sort_by') }}</option>
								<option value="Newest" {{ request('sortedBy') === 'Newest' ? 'selected' : '' }}>{{ __('candidate.applications.newest') }}</option>
								<option value="Oldest" {{ request('sortedBy') === 'Oldest' ? 'selected' : '' }}>{{ __('candidate.applications.oldest') }}</option>
							</select>
						</div>
						<div class="sort-button">
							<select id="perPage" class="form-select rounded" style="width: auto; min-width: 100px;">
								<option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 / {{ __('common.applies') }}</option>
								<option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 / {{ __('common.applies') }}</option>
								<option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / {{ __('common.applies') }}</option>
							</select>
						</div>
					</div>
					<div class="jobs-list">
						@forelse ($applies as $key => $apply)
							<div class="job-list-box mt-3 border rounded shadow-sm bg-white">
								<div class="p-3">
									<div class="row align-items-center">
										<div class="col-4 col-md-2">
											<div class="company-logo-img text-center">
												<img src="{{ $apply->job->image_url }}" alt="{{ $apply->job->title }}" class="img-fluid mx-auto d-block rounded" style="max-height: 80px; object-fit: contain;">
											</div>
										</div>
										<div class="col-8 col-md-7">
											<div class="job-list-desc">
												<h6 class="mb-1"><a href="{{ route('candidate.jobs.vacancies.show', $apply->job->uuid) }}" class="text-dark fw-bold">{{ $apply->job->code ?? '#' }} - {{ $apply->job->title ?? '-' }}</a></h6>
												<p class="text-muted small mb-1">{{ $apply->job->category->name ?? '-' }}</p>
												<ul class="list-inline mb-0">
													<li class="list-inline-item me-3">
														<p class="text-muted small mb-0"><i class="mdi mdi-calendar me-1"></i>{{ __('common.sent_at') }} {{ date('d M Y H:i', strtotime($apply->created_at)) }}</p>
													</li>
												</ul>
											</div>
										</div>
										<div class="col-12 col-md-3 mt-2 mt-md-0 text-md-end">
											<div class="d-flex flex-column align-items-md-end justify-content-center gap-1">
												@if ($apply->status === 'IN REVIEW')
													<span class="badge bg-warning text-dark px-3 py-2 fs-6">Dalam Review</span>
												@elseif ($apply->status === 'SHORTLISTED')
													<span class="badge bg-info px-3 py-2 fs-6">Tahap Offering</span>
												@elseif ($apply->status === 'HIRED')
													<span class="badge bg-success px-3 py-2 fs-6">Diterima</span>
												@elseif ($apply->status === 'NOT SUITABLE')
													<span class="badge bg-secondary px-3 py-2 fs-6">Belum Sesuai</span>
												@else
													<span class="badge bg-primary px-3 py-2 fs-6">{{ strtoupper($apply->status) }}</span>
												@endif
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

						@if ($applies->hasPages())
							<div class="mt-4 d-flex justify-content-center">
								{{ $applies->appends(request()->query())->links('pagination::bootstrap-5') }}
							</div>
						@endif
						
						@if ($applies->total() > 0)
							<div class="mt-3 text-center text-muted small">
								{{ __('common.showing', ['from' => $applies->firstItem(), 'to' => $applies->lastItem(), 'total' => $applies->total(), 'unit' => __('common.applies')]) }}
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('js')
	<script>
		$(function() {
			$(document).on('change', '#sortedBy', function() {
				let orderBy = $(this).val();
				var url = new URL(window.location.href);
				if (orderBy) {
					url.searchParams.set('sortedBy', orderBy);
				} else {
					url.searchParams.delete('sortedBy');
				}
				url.searchParams.delete('page');
				window.location.href = url.toString();
			});

			$(document).on('change', '#perPage', function() {
				var perPage = $(this).val();
				var url = new URL(window.location.href);
				if (perPage) {
					url.searchParams.set('per_page', perPage);
				} else {
					url.searchParams.delete('per_page');
				}
				url.searchParams.delete('page');
				window.location.href = url.toString();
			});
		});
	</script>
@endsection
