@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="row">
			<form class="row" id="form-add-new-record" method="POST" action="{{ route('admin.applies.update', $apply->id) }}">
				@csrf
				@if (!empty($apply))
					@method('PATCH')
				@endif
				<div class="col-md-7 mb-6">
					<div class="card">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">
								<i class="menu-icon tf-icons ti ti-user"></i>
								{{ __('admin.applies.detail_title') }}
							</h5>
						</div>
						<div class="card-body row">
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.applies.batch') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ $apply->batch->code . ' - ' . $apply->batch->name ?? '-' }}" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.applies.job_vacancy') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ $apply->job->code . ' - ' . $apply->job->title . ' - ' . $apply->job->type . ' | ' . $apply->job->category->name }}" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.applies.applicant_detail') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ $apply->candidate->name . ' - ' . $apply->candidate->phone . ' - ' . $apply->candidate->email }}" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.applies.applied_at') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ date('d M Y H:i:s', strtotime($apply->created_at)) }}" />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.applies.cover_letter') }}</label>
									<div class="input-group input-group-merge">
										{!! $apply->cover_letter !!}
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.applies.description') }}</label>
									<div class="input-group input-group-merge">
										{!! $apply->description !!}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5 mb-6">
					<div class="card">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">
								<i class="menu-icon tf-icons ti ti-info-circle"></i>
								{{ __('admin.applies.screening_title') }}
							</h5>
						</div>
						<div class="card-body">
							@if ($apply->auto_score !== null)
								@php
									$recommendation = $apply->score_recommendation
										? \App\Enums\ScoreRecommendation::from($apply->score_recommendation)
										: null;
								@endphp
								<div class="alert alert-light border mb-3">
									<h6 class="mb-2">{{ __('admin.applies.auto_scoring') }}</h6>
									<p class="mb-1"><strong>{{ __('admin.applies.score_label') }}:</strong> {{ $apply->auto_score }}/100</p>
									@if ($recommendation)
										<p class="mb-1"><strong>{{ __('admin.applies.recommendation_label') }}:</strong>
											<span class="badge {{ $recommendation->badgeClass() }}">{{ $recommendation->label() }}</span>
										</p>
									@endif
									@if ($apply->score_breakdown)
										<ul class="mb-0 mt-2">
											<li>{{ __('admin.applies.breakdown_education') }}: {{ $apply->score_breakdown['education'] ?? 0 }}</li>
											<li>{{ __('admin.applies.breakdown_experience') }}: {{ $apply->score_breakdown['experience'] ?? 0 }}</li>
											<li>{{ __('admin.applies.breakdown_profile') }}: {{ $apply->score_breakdown['profile'] ?? 0 }}</li>
											<li>{{ __('admin.applies.breakdown_cover_letter') }}: {{ $apply->score_breakdown['cover_letter'] ?? 0 }}</li>
										</ul>
									@endif
									<small class="text-muted d-block mt-2">{{ __('admin.applies.scored_at') }}: {{ optional($apply->scored_at)->format('d M Y H:i') ?? '-' }}</small>
								</div>
							@endif
							<div class="mb-3">
								<label class="form-label fw-bold">{{ __('admin.applies.documents') }}</label>
								@php
									$applyDocuments = $apply->applyDocuments()->with('document')->get();
								@endphp
								@if ($applyDocuments->isNotEmpty())
									<div class="list-group list-group-flush">
										@foreach ($applyDocuments as $applyDoc)
											@php
												$docUrl = Illuminate\Support\Facades\Storage::url($applyDoc->document->file);
												$extension = strtolower(pathinfo($applyDoc->document->file, PATHINFO_EXTENSION));
												$docTypeLabel = $applyDoc->type?->getLabel() ?? $applyDoc->type;
												$docBadgeClass = $applyDoc->type?->getBadgeClass() ?? 'badge-secondary';
											@endphp
											<div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
												<div class="me-2 text-truncate" style="max-width: 200px;">
													<i class="ti ti-file-text me-1 text-primary"></i>
													<span title="{{ $applyDoc->document->name }}" class="fw-medium">{{ $applyDoc->document->name }}</span>
												</div>
												<div class="d-flex align-items-center gap-1">
													<span class="badge {{ $docBadgeClass }} me-1">{{ $docTypeLabel }}</span>
													<button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-preview-doc"
														data-url="{{ $docUrl }}"
														data-title="{{ $applyDoc->document->name }}"
														data-type="{{ $docTypeLabel }}"
														data-ext="{{ $extension }}"
														title="{{ __('admin.applies.preview') }}">
														<i class="ti ti-eye"></i>
													</button>
													<a href="{{ $docUrl }}" download target="_blank" class="btn btn-sm btn-icon btn-outline-secondary" title="Download">
														<i class="ti ti-download"></i>
													</a>
												</div>
											</div>
										@endforeach
									</div>
								@else
									<p class="text-muted small mb-0">{{ __('admin.applies.no_documents') }}</p>
								@endif
							</div>
							<div class="mb-3">
								<select name="status" id="status" class="form-control">
									<option value="">{{ __('admin.applies.change_status') }}</option>
									@foreach ($statuses as $value => $label)
										<option value="{{ $value }}" {{ $apply->status == $value ? 'selected' : '' }}>{{ $label }}</option>
									@endforeach
								</select>
							</div>
							<div class="mb-3">
								<button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">{{ __('admin.form.submit') }}</button>
								<a href="{{ route('admin.applies.index') }}" class="btn btn-outline-secondary">{{ __('admin.form.cancel') }}</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Document Preview Modal -->
	<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content border-0 shadow-lg">
				<div class="modal-header px-4 py-3 border-bottom bg-white">
					<h5 class="modal-title d-flex align-items-center gap-2 mb-0">
						<i class="ti ti-file-text text-primary fs-4"></i>
						<span id="docPreviewTitle" class="fw-bold fs-6 text-truncate" style="max-width: 350px;">{{ __('admin.applies.preview_title') }}</span>
						<span id="docPreviewBadge" class="badge bg-primary"></span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-3 p-md-4 bg-light" id="docPreviewBody" style="max-height: 68vh; overflow-y: auto;">
					<!-- Dynamic Preview Content -->
				</div>
				<div class="modal-footer px-4 py-3 border-top bg-white justify-content-between">
					<a id="docPreviewExternalLink" href="#" target="_blank" class="btn btn-outline-secondary">
						<i class="ti ti-external-link me-1"></i> {{ __('admin.applies.open_new_tab') }}
					</a>
					<a id="docPreviewDownloadBtn" href="#" download class="btn btn-primary">
						<i class="ti ti-download me-1"></i> Download
					</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (document.getElementById('quill-editor-qualification-area')) {
				const toolbarOptions = [
					['bold', 'italic', 'underline', 'strike'],
					['blockquote', 'code-block'],
					['link', 'image', 'video', 'formula'],
					[{
						'header': 1
					}, {
						'header': 2
					}],
					[{
						'list': 'ordered'
					}, {
						'list': 'bullet'
					}, {
						'list': 'check'
					}],
					[{
						'script': 'sub'
					}, {
						'script': 'super'
					}],
					[{
						'indent': '-1'
					}, {
						'indent': '+1'
					}],
					[{
						'direction': 'rtl'
					}],
					[{
						'size': ['small', false, 'large', 'huge']
					}],
					[{
						'header': [1, 2, 3, 4, 5, 6, false]
					}],
					[{
						'color': []
					}, {
						'background': []
					}],
					[{
						'font': []
					}],
					[{
						'align': []
					}],
					['clean']
				];
				var editor = new Quill('#quill-editor-qualification', {
					theme: 'snow',
					modules: {
						toolbar: toolbarOptions
					}
				});
				var quillEditor = document.getElementById('quill-editor-qualification-area');
				editor.root.innerHTML = `{!! !empty($job) ? $job->qualification : '' !!}`;

				editor.on('text-change', function() {
					quillEditor.value = editor.root.innerHTML;
				});
				quillEditor.addEventListener('input', function() {
					editor.root.innerHTML = quillEditor.value;
				});
			}
		});

		document.addEventListener('DOMContentLoaded', function() {
			if (document.getElementById('quill-editor-description-area')) {
				const toolbarOptions = [
					['bold', 'italic', 'underline', 'strike'],
					['blockquote', 'code-block'],
					['link', 'image', 'video', 'formula'],
					[{
						'header': 1
					}, {
						'header': 2
					}],
					[{
						'list': 'ordered'
					}, {
						'list': 'bullet'
					}, {
						'list': 'check'
					}],
					[{
						'script': 'sub'
					}, {
						'script': 'super'
					}],
					[{
						'indent': '-1'
					}, {
						'indent': '+1'
					}],
					[{
						'direction': 'rtl'
					}],
					[{
						'size': ['small', false, 'large', 'huge']
					}],
					[{
						'header': [1, 2, 3, 4, 5, 6, false]
					}],
					[{
						'color': []
					}, {
						'background': []
					}],
					[{
						'font': []
					}],
					[{
						'align': []
					}],
					['clean']
				];
				var editor = new Quill('#quill-editor-description', {
					theme: 'snow',
					modules: {
						toolbar: toolbarOptions
					}
				});
				var quillEditor = document.getElementById('quill-editor-description-area');
				editor.root.innerHTML = `{!! !empty($job) ? $job->description : '' !!}`;

				editor.on('text-change', function() {
					quillEditor.value = editor.root.innerHTML;
				});
				quillEditor.addEventListener('input', function() {
					editor.root.innerHTML = quillEditor.value;
				});
			}
		});

		$(document).on('click', '.btn-preview-doc', function(e) {
			e.preventDefault();
			const url = $(this).data('url');
			const title = $(this).data('title');
			const typeLabel = $(this).data('type');
			const ext = String($(this).data('ext')).toLowerCase();

			$('#docPreviewTitle').text(title);
			$('#docPreviewBadge').text(typeLabel);
			$('#docPreviewExternalLink').attr('href', url);
			$('#docPreviewDownloadBtn').attr('href', url);

			const imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'svg'];
			const officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

			if (ext === 'pdf') {
				$('#docPreviewBody').html(`
					<div class="bg-white rounded-3 shadow-sm overflow-hidden p-2">
						<iframe src="${url}" style="width:100%; height:55vh; border:none; border-radius: 6px;" frameborder="0"></iframe>
					</div>
				`);
			} else if (imageExtensions.includes(ext)) {
				$('#docPreviewBody').html(`
					<div class="text-center py-2">
						<div class="bg-white rounded-3 shadow-sm p-3 d-inline-block mw-100">
							<img src="${url}" class="img-fluid rounded" style="max-height: 52vh; object-fit: contain;" alt="${title}">
						</div>
					</div>
				`);
			} else if (ext === 'csv' || ext === 'txt') {
				$('#docPreviewBody').html(`
					<div class="bg-white rounded-3 shadow-sm p-3">
						<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
							<span class="fw-bold text-primary"><i class="ti ti-table me-1"></i> Data Table Preview (${ext.toUpperCase()})</span>
							<small class="text-muted">Parsed automatically</small>
						</div>
						<div class="table-responsive" style="max-height: 48vh;">
							<div class="text-center py-4" id="csvLoadingState">
								<div class="spinner-border text-primary" role="status"></div>
								<p class="mt-2 text-muted small">Memuat isi berkas...</p>
							</div>
							<table class="table table-bordered table-striped table-hover table-sm d-none mb-0" id="csvPreviewTable">
								<thead class="table-primary" id="csvPreviewThead"></thead>
								<tbody id="csvPreviewTbody"></tbody>
							</table>
						</div>
					</div>
				`);

				fetch(url)
					.then(response => response.text())
					.then(text => {
						const lines = text.trim().split('\n');
						if (lines.length === 0) {
							$('#csvLoadingState').html('<p class="text-muted">Berkas kosong.</p>');
							return;
						}
						let theadHtml = '';
						let tbodyHtml = '';

						lines.forEach((line, index) => {
							const cells = line.split(/,(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/).map(c => c.trim().replace(/^"|"$/g, ''));
							if (index === 0) {
								theadHtml += '<tr>' + cells.map(cell => `<th>${cell}</th>`).join('') + '</tr>';
							} else {
								tbodyHtml += '<tr>' + cells.map(cell => `<td>${cell}</td>`).join('') + '</tr>';
							}
						});

						$('#csvPreviewThead').html(theadHtml);
						$('#csvPreviewTbody').html(tbodyHtml);
						$('#csvLoadingState').addClass('d-none');
						$('#csvPreviewTable').removeClass('d-none');
					})
					.catch(() => {
						$('#csvLoadingState').html('<p class="text-danger small">Gagal memproses pratinjau tabel. Silakan unduh dokumen.</p>');
					});
			} else if (officeExtensions.includes(ext)) {
				const isOfficeExcel = ['xls', 'xlsx'].includes(ext);
				const isOfficeWord = ['doc', 'docx'].includes(ext);
				const isOfficePpt = ['ppt', 'pptx'].includes(ext);

				let officeIconClass = 'ti-file-text text-primary';
				let officeBadgeClass = 'bg-primary';
				let officeTypeName = 'Microsoft Word Document';

				if (isOfficeExcel) {
					officeIconClass = 'ti-file-spreadsheet text-success';
					officeBadgeClass = 'bg-success';
					officeTypeName = 'Microsoft Excel Spreadsheet';
				} else if (isOfficePpt) {
					officeIconClass = 'ti-presentation text-warning';
					officeBadgeClass = 'bg-warning';
					officeTypeName = 'Microsoft PowerPoint Presentation';
				}

				const isLocalhost = ['localhost', '127.0.0.1'].includes(window.location.hostname);

				if (isLocalhost) {
					$('#docPreviewBody').html(`
						<div class="bg-white rounded-3 shadow-sm p-4 text-center">
							<div class="mb-3">
								<i class="ti ${officeIconClass} d-block mx-auto mb-2" style="font-size: 3.5rem;"></i>
								<h5 class="fw-bold mb-1">${title}</h5>
								<span class="badge ${officeBadgeClass} mb-2">${officeTypeName} (.${ext.toUpperCase()})</span>
							</div>
							<div class="alert alert-info border-0 bg-light text-start p-3 mb-4">
								<div class="d-flex align-items-start gap-2">
									<i class="ti ti-info-circle text-info fs-5 mt-1 flex-shrink-0"></i>
									<div>
										<strong>Informasi Pratinjau Server Lokal:</strong><br>
										<small class="text-muted">Berkas format Microsoft Office (.${ext.toUpperCase()}) di lingkungan lokal (localhost/127.0.0.1) dapat diunduh atau dibuka langsung di perangkat Anda. Di server produksi (domain publik), pratinjau otomatis aktif via Google Docs Viewer.</small>
									</div>
								</div>
							</div>
							<div class="d-flex align-items-center justify-content-center gap-2">
								<a href="${url}" target="_blank" class="btn btn-outline-primary">
									<i class="ti ti-external-link me-1"></i> Buka Berkas
								</a>
								<a href="${url}" download class="btn btn-primary">
									<i class="ti ti-download me-1"></i> Unduh Berkas
								</a>
							</div>
						</div>
					`);
				} else {
					const absoluteUrl = window.location.origin + url;
					const googleDocsUrl = `https://docs.google.com/gview?url=${encodeURIComponent(absoluteUrl)}&embedded=true`;
					$('#docPreviewBody').html(`
						<div class="bg-white rounded-3 shadow-sm overflow-hidden p-2">
							<iframe src="${googleDocsUrl}" style="width:100%; height:55vh; border:none; border-radius: 6px;" frameborder="0"></iframe>
						</div>
					`);
				}
			} else {
				$('#docPreviewBody').html(`
					<div class="bg-white rounded-3 shadow-sm text-center py-5 px-3">
						<i class="ti ti-file-description text-primary d-block mb-3" style="font-size: 4rem;"></i>
						<h6 class="mb-2">${title}</h6>
						<p class="text-muted small mb-4">Format file (.${ext}) dapat diunduh atau dibuka langsung di jendela baru browser.</p>
						<a href="${url}" target="_blank" class="btn btn-primary me-2">
							<i class="ti ti-external-link me-1"></i> Buka di Jendela Baru
						</a>
						<a href="${url}" download class="btn btn-outline-secondary">
							<i class="ti ti-download me-1"></i> Unduh Berkas
						</a>
					</div>
				`);
			}

			$('#docPreviewModal').modal('show');
		});
	</script>
@endsection
