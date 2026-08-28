@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.apply.title'))
@section('content')
	<section class="bg-half page-next-level">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="text-center text-white">
						<h4 class="text-uppercase title mb-4">{{ $job->code }} - {{ $job->title }}</h4>
						<ul class="page-next d-inline-block mb-0">
							<li><a href="#" class="text-uppercase fw-bold">{{ __('candidate.nav.home') }}</a></li>
							<li>
								<span class="text-uppercase text-white">{{ __('candidate.nav.jobs') }}</span>
							</li>
							<li>
								<span class="text-uppercase text-white fw-bold">{{ $job->code }} - {{ $job->title }}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-7">
					@include('layouts.alert-section')
					<div class="job-detail border rounded p-4">
						<div class="job-detail-content">
							@if (count($job->image_urls) > 0)
								<div id="applyCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
									<ol class="carousel-indicators">
										@foreach ($job->image_urls as $index => $imageUrl)
											<li data-bs-target="#applyCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
										@endforeach
									</ol>
									<div class="carousel-inner rounded">
										@foreach ($job->image_urls as $index => $imageUrl)
											<div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
												<img src="{{ $imageUrl }}" alt="{{ $job->title }}" class="d-block w-100 job-carousel-img" data-slide="{{ $index }}">
											</div>
										@endforeach
									</div>
									<a class="carousel-control-prev" href="#applyCarousel" role="button" data-bs-slide="prev">
										<span class="carousel-control-prev-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Previous</span>
									</a>
									<a class="carousel-control-next" href="#applyCarousel" role="button" data-bs-slide="next">
										<span class="carousel-control-next-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Next</span>
									</a>
								</div>
							@endif
							<div class="job-detail-com-desc overflow-hidden d-block">
								<h4 class="mb-2"><a href="#" class="text-dark">{{ __('candidate.apply.application_label') }} | {{ $job->code }} - {{ $job->title }}</a></h4>
								<p class="text-muted mb-0"><i class="mdi mdi-link-variant me-2"></i>{{ $job->category->name }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-laptop me-2"></i>{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }} | {{ $job->experience }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-account me-2"></i>{{ __('candidate.jobs.applicants_count', ['count' => $formattedAppliesTotal]) }}</p>
							</div>
						</div>
					</div>
					<form id="apply-form" action="{{ route('candidate.jobs.applications.store') }}" method="POST">
						@csrf
						<input type="hidden" name="job_uuid" value="{{ $job->uuid }}">
						<div class="row mt-4">
							<div class="col-lg-12">
								<div class="job-detail border rounded p-4">
									<div class="job-detail-content">
										<div class="mb-3">
											<label>{{ __('candidate.apply.supporting_documents') }} :</label>
											@if (isset($candidate) && $candidate->documents && $candidate->documents->count() > 0)
												<div id="document-selector">
													<div class="document-row d-flex align-items-center gap-2 mb-2">
														<select name="existing_documents[]" class="form-select document-select">
															<option value="">{{ __('candidate.apply.select_document') }}</option>
															@foreach ($candidate->documents as $doc)
																<option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->type_label }})</option>
															@endforeach
														</select>
														<button type="button" class="btn btn-danger btn-sm remove-document flex-shrink-0 d-none">&times;</button>
													</div>
													<button type="button" id="add-document" class="btn btn-outline-primary btn-sm mt-1">
														<i class="mdi mdi-plus"></i> {{ __('candidate.apply.add_document') }}
													</button>
												</div>
												@error('existing_documents')
													<span class="text-danger fw-bold d-block mt-1">{{ $message }}</span>
												@enderror
											@else
												<div class="alert alert-warning mb-0 mt-2">
													<i class="mdi mdi-alert me-1"></i>
													{{ __('candidate.apply.no_documents_yet') }}
													<a href="{{ route('candidate.my.documents.index') }}" class="alert-link">{{ __('candidate.apply.add_documents_now') }}</a>
												</div>
											@endif
										</div>
										<div class="mb-3">
											<label for="">{{ __('candidate.apply.cover_letter') }} : </label>
											<div id="quill-editor-cover_letter" class="mb-3" style="height: 100px;"></div>
											<textarea class="mb-3 d-none" name="cover_letter" id="quill-editor-cover_letter-area"></textarea>
											@error('cover_letter')
												<span class="text-danger fw-bold">{{ $message }}</span>
											@enderror
										</div>
										<div class="mb-3">
											<label for="">{{ __('candidate.apply.why_apply') }} : </label>
											<div id="quill-editor-description" class="mb-3" style="height: 150px;"></div>
											<textarea class="mb-3 d-none" name="description" id="quill-editor-description-area"></textarea>
											@error('description')
												<span class="text-danger fw-bold">{{ $message }}</span>
											@enderror
										</div>
									</div>
									<button type="submit" class="btn btn-primary w-100">
										{{ __('candidate.jobs.apply_now') }}
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-lg-4 col-md-5 mt-4 mt-sm-0">
					<div class="job-detail border rounded p-4">
						<h5 class="text-muted text-center pb-2"><i class="mdi mdi-info me-2"></i>{{ __('candidate.jobs.information') }}</h5>
						<div class="job-detail-location pt-4 border-top">
							<div class="job-details-desc-item d-flex gap-2 mb-2">
								<i class="mdi mdi-clock-outline text-muted flex-shrink-0 mt-1"></i>
								<p class="text-muted mb-0">{{ __('candidate.jobs.information') }}: {{ $activeBatch?->name ?? '-' }} | {{ $activeBatch ? date('d M Y', strtotime($activeBatch->start_date)) . ' - ' . date('d M Y', strtotime($activeBatch->end_date)) : '-' }}</p>
							</div>
							<div class="job-details-desc-item d-flex gap-2 mb-2">
								<i class="mdi mdi-laptop text-muted flex-shrink-0 mt-1"></i>
								<p class="text-muted mb-0">{{ \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type }}</p>
							</div>
							<div class="job-details-desc-item d-flex gap-2 mb-2">
								<i class="mdi mdi-information-outline text-muted flex-shrink-0 mt-1"></i>
								<p class="text-muted mb-0">{{ $job->experience }}</p>
							</div>
							<div class="job-details-desc-item d-flex gap-2 mb-2">
								<i class="mdi mdi-account text-muted flex-shrink-0 mt-1"></i>
								<p class="text-muted mb-0">{{ __('candidate.apply.quota_people', ['count' => $job->quota]) }}</p>
							</div>
							<div class="job-details-desc-item d-flex gap-2 mb-2">
								<i class="mdi mdi-currency-usd text-muted flex-shrink-0 mt-1"></i>
								<p class="text-muted mb-0">: {{ $job->is_show_salary ? $job->salary_display : __('candidate.apply.salary_not_stated') }}</p>
							</div>
							<div class="job-details-desc-item d-flex gap-2 mb-2">
								<i class="mdi mdi-clock-outline text-muted flex-shrink-0 mt-1"></i>
								<p class="text-muted mb-0">: {{ __('candidate.apply.weekdays') }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
<div class="modal fade" id="imageZoomModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="false">
		<div class="modal-dialog modal-dialog-centered m-0 p-0" style="max-width: 100vw; min-height: 100vh;" role="document">
			<div class="modal-content" style="background: rgba(0,0,0,0.15); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: none; border-radius: 0; min-height: 100vh;">
				<button type="button" class="btn-close btn-close-white position-fixed" data-bs-dismiss="modal" aria-label="Close" style="top: 20px; right: 25px; z-index: 1050; font-size: 1.5rem;">
				</button>
				<div class="modal-body d-flex align-items-center justify-content-center p-0" style="min-height: 100vh;">
					<div id="zoomCarousel" class="carousel slide w-100" data-bs-interval="false">
						<div class="carousel-inner">
							@foreach ($job->image_urls as $index => $imageUrl)
								<div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
									<img src="{{ $imageUrl }}" alt="{{ $job->title }}" class="d-block mx-auto" style="max-width: 90vw; max-height: 90vh; object-fit: contain;">
								</div>
							@endforeach
						</div>
						<a class="carousel-control-prev" href="#zoomCarousel" role="button" data-bs-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</a>
						<a class="carousel-control-next" href="#zoomCarousel" role="button" data-bs-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@section('js')
	<script>
		$(document).ready(function() {
			const toolbarOptions = [
				['bold', 'italic', 'underline', 'strike'],
				['blockquote', 'code-block'],
				[{ 'header': 1 }, { 'header': 2 }],
				[{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
				[{ 'indent': '-1' }, { 'indent': '+1' }],
				[{ 'direction': 'rtl' }],
				[{ 'size': ['small', false, 'large', 'huge'] }],
				[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
				[{ 'color': [] }, { 'background': [] }],
				[{ 'font': [] }],
				[{ 'align': [] }],
				['clean']
			];

			function initQuill(editorId, areaId, oldValue = '') {
				if (!$('#' + areaId).length) return;

				var editor = new Quill('#' + editorId, {
					theme: 'snow',
					modules: { toolbar: toolbarOptions }
				});

				// Restore old value saat ada error validasi
				if (oldValue) {
					editor.root.innerHTML = oldValue;
				}

				// Sync Quill → textarea
				editor.on('text-change', function() {
					$('#' + areaId).val(editor.root.innerHTML);
				});

				// Sync textarea → Quill
				$('#' + areaId).on('input', function() {
					editor.root.innerHTML = $(this).val();
				});
			}

			initQuill('quill-editor-description', 'quill-editor-description-area', `{!! old('description') !!}`);
			initQuill('quill-editor-cover_letter', 'quill-editor-cover_letter-area', `{!! old('cover_letter') !!}`);

			$('#applyCarousel .carousel-item img').on('click', function() {
				var slideIndex = $(this).data('slide');
				$('#zoomCarousel').carousel(slideIndex);
				$('#imageZoomModal').modal('show');
			});

			$('#imageZoomModal').on('hidden.bs.modal', function () {
				$('#zoomCarousel').carousel(0);
			});
		});

		{{-- Dynamic document multi-select --}}
		var allDocs = @json($candidate->documents ?? []);

		function rebuildDocumentOptions() {
			var selected = [];
			document.querySelectorAll('.document-select').forEach(function(sel) {
				if (sel.value) selected.push(sel.value);
			});
			document.querySelectorAll('.document-select').forEach(function(sel) {
				var val = sel.value;
				sel.innerHTML = '<option value="">' + '{{ __("candidate.apply.select_document") }}' + '</option>';
				allDocs.forEach(function(doc) {
					if (!selected.includes(String(doc.id)) || String(doc.id) === val) {
						var opt = document.createElement('option');
						opt.value = doc.id;
						opt.textContent = doc.name + ' (' + doc.type_label + ')';
						sel.appendChild(opt);
					}
				});
				sel.value = val;
			});
			document.querySelectorAll('.remove-document').forEach(function(btn) {
				btn.classList.toggle('d-none', document.querySelectorAll('.document-row').length <= 1);
			});
		}

		document.getElementById('add-document').addEventListener('click', function() {
			var container = document.getElementById('document-selector');
			var rows = container.querySelectorAll('.document-row');
			if (rows.length > 0) {
				var clone = rows[0].cloneNode(true);
				clone.querySelector('select').value = '';
				container.insertBefore(clone, this);
				rebuildDocumentOptions();
			}
		});

		document.getElementById('document-selector').addEventListener('change', function(e) {
			if (e.target.classList.contains('document-select')) {
				rebuildDocumentOptions();
			}
		});

		document.getElementById('document-selector').addEventListener('click', function(e) {
			if (e.target.classList.contains('remove-document')) {
				var row = e.target.closest('.document-row');
				var container = document.getElementById('document-selector');
				if (container.querySelectorAll('.document-row').length > 1) {
					row.remove();
					rebuildDocumentOptions();
				}
			}
		});

		document.getElementById('apply-form')?.addEventListener('submit', function() {
			this.querySelectorAll('.document-select').forEach(function(sel) {
				if (!sel.value) {
					sel.disabled = true;
				}
			});
		});
	</script>
@endsection
