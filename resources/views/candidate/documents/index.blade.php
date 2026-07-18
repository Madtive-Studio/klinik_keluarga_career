@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.documents.title'))
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.documents.tab_menu')
				<div class="col-lg-9 col-md-5 my-3 mt-sm-0">
					@if ($message = Session::get('error'))
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-danger" role="alert">
									<p class="mb-0">{{ $message }}</p>
								</div>
							</div>
						</div>
					@endif
					@if ($message = Session::get('success'))
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-success" role="alert">
									<p class="mb-0">{{ $message }}</p>
								</div>
							</div>
						</div>
					@endif
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="mb-0">{{ __('common.total') }} : {{ __('candidate.applications.total_documents', ['count' => $candidate->documents_count]) }}</h5>
						
						{{-- Optional: Pilihan jumlah item per halaman --}}
						<div class="form-inline">
							<label class="mr-2">{{ __('common.show') }}:</label>
							<select id="perPage" class="form-control form-control-sm" style="width: auto;">
								<option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
								<option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
								<option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
							</select>
						</div>
					</div>
					
					<div class="jobs-list">
						@forelse ($candidate->documents as $key => $document)
							<div class="job-list-box mb-3 border rounded">
								<div class="p-3">
									<div class="row align-items-center">
										<div class="col-lg-2">
											<div class="company-logo-img">
												<img src="{{ asset(getPlaceholderFilePath($document->file)) }}" width="100" alt="" class="img-fluid mx-auto d-block rounded">
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
														<p class="text-muted mb-0">
															<i class="mdi mdi-calendar mr-2"></i>
															{{ __('common.uploaded') }} {{ $document->created_at->diffForHumans() }}
														</p>
													</li>
												</ul>
											</div>
										</div>
										<div class="col-lg-3 col-md-3">
											<div class="job-list-button-sm text-right d-flex justify-content-end gap-1">
												<form action="{{ route('candidate.my.documents.destroy', $document->id) }}" method="POST">
													@csrf
													@method('DELETE')
													<button type="submit" class="btn btn-danger btn-sm delete-btn">
														<i class="mdi mdi-delete"></i> {{ __('common.delete') }}
													</button>
												</form>
												<a href="{{ $document->file_url }}" target="_blank" download class="btn btn-primary btn-sm">
													<i class="mdi mdi-download"></i> {{ __('common.download') }}
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							<div class="alert alert-info text-center">
								<p class="mb-0">{{ __('common.no_data') }}</p>
							</div>
						@endforelse
					</div>
					
					{{-- Pagination Links --}}
					<div class="mt-4 d-flex justify-content-center">
						{{ $candidate->documents->appends(request()->query())->links('pagination::bootstrap-4') }}
					</div>
					
					{{-- Info Pagination --}}
					<div class="mt-2 text-center text-muted small">
						{{ __('common.showing', ['from' => $candidate->documents->firstItem(), 'to' => $candidate->documents->lastItem(), 'total' => $candidate->documents->total(), 'unit' => __('common.documents')]) }}
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@section('js')
	<script>
		const commonI18n = @json([
			'confirm_delete_title' => __('common.confirm_delete_title'),
			'confirm_delete_text' => __('common.confirm_delete_text'),
			'yes_delete' => __('common.yes_delete'),
			'cancel' => __('common.cancel'),
		]);

		$(function() {
			$('.form_add_new_cv').hide()
			
			$(document).on('click', '#add_new_cv', function() {
				$('.form_add_new_cv').toggle()
			})
			
			// Handle per page change
			$('#perPage').on('change', function() {
				var perPage = $(this).val();
				var url = new URL(window.location.href);
				url.searchParams.set('per_page', perPage);
				window.location.href = url.toString();
			});

			$('.delete-btn').click(function(e) {
				e.preventDefault();
				Swal.fire({
					title: commonI18n.confirm_delete_title,
					text: commonI18n.confirm_delete_text,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: commonI18n.yes_delete,
					cancelButtonText: commonI18n.cancel
				}).then((result) => {
					if (result.isConfirmed) {
						$(this).closest('form').submit();
					}
				});
			});
		});
	</script>
@endsection