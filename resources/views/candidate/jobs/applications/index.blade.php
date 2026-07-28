@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.applications.title'))
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.jobs.applications.tab_menu')
				<div class="col-lg-8 col-md-5 mt-4 mt-sm-0">
					<h5 class="mb-2">{{ __('common.total') }} : {{ __('candidate.applications.total_documents', ['count' => $applies->apply_count]) }}</h5>
					<div class="show-results d-flex align-items-center justify-content-between flex-wrap gap-2">
						<div class="sort-button">
							<select class="nice-select rounded" id="sortedBy">
								<option value="">{{ __('candidate.applications.sort_by') }}</option>
								<option value="Newest" {{ request('sortedBy') === 'Newest' ? 'selected' : '' }}>{{ __('candidate.applications.newest') }}</option>
								<option value="Oldest" {{ request('sortedBy') === 'Oldest' ? 'selected' : '' }}>{{ __('candidate.applications.oldest') }}</option>
							</select>
						</div>
						<div class="sort-button">
							<select id="perPage" class="nice-select rounded" style="width: auto;">
								<option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
								<option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
								<option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
							</select>
						</div>
					</div>
					<div class="jobs-list">
						@forelse ($applies as $key => $apply)
							<div class="job-list-box mt-3 border rounded">
								<div class="p-3">
									<div class="row align-items-center">
										<div class="col-lg-2">
											<div class="company-logo-img">
												<img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="img-fluid mx-auto d-block rounded" style="max-width: 100px;">
											</div>
										</div>
										<div class="col-lg-7 col-md-9">
											<div class="job-list-desc">
												<h6 class="mb-0"><a href="#" class="text-dark">{{ $apply->job->code ?? '#' }} - {{ $apply->job->title ?? '-' }}</a></h6>
												<p class="text-muted mb-0">{{ $apply->job->category->name ?? '-' }}
												</p>
												<ul class="list-inline mb-0">
													<li class="list-inline-item me-3">
														<p class="text-muted mb-0"><i class="mdi mdi-calendar me-2"></i>{{ __('common.sent_at') }} {{ date('d M Y H:i:s', strtotime($apply->created_at)) }}</p>
													</li>
												</ul>
												<span class="badge bg-primary mt-2">{{ __('common.applied') }}</span>
											</div>
										</div>
										<div class="col-lg-3 col-md-3">
											<div class="job-list-button-sm text-end">
												<span class="badge bg-success">{{ strtoupper($apply->status) }}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							<p class="mb-0 text-center">{{ __('common.no_data') }}</p>
						@endforelse

						{{-- Pagination Links --}}
						<div class="mt-4 d-flex justify-content-center">
							{{ $applies->appends(request()->query())->links('pagination::bootstrap-5') }}
						</div>
						
						{{-- Info Pagination --}}
						<div class="mt-2 text-center text-muted small">
							{{ __('common.showing', ['from' => $applies->firstItem(), 'to' => $applies->lastItem(), 'total' => $applies->total(), 'unit' => __('common.documents')]) }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('js')
	<script>
		$(function() {
			$('#sortedBy').on('change', function() {
				let orderBy = $(this).find('option:selected').val()
				var url = new URL(window.location.href);
				url.searchParams.set('sorted_by', orderBy);
				window.location.href = url.toString();
			})

			$('#perPage').on('change', function() {
				var perPage = $(this).val();
				var url = new URL(window.location.href);
				url.searchParams.set('per_page', perPage);
				window.location.href = url.toString();
			});
		})
	</script>
@endsection
