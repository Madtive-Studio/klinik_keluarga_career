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

					<input type="file" id="document-file-input" accept=".pdf,.doc,.docx,image/*"
					style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;">
					@if ($activeType)
						<label for="document-file-input" id="document-dropzone"
							class="document-dropzone mb-3 d-block"
							data-type="{{ $activeType->value }}"
							data-label="{{ $activeType->getLabel() }}">
							<div class="document-dropzone__content text-center py-4 px-3">
								<i class="mdi mdi-cloud-upload-outline document-dropzone__icon d-block mb-2"></i>
								<p class="mb-1 fw-bold text-dark">{{ __('candidate.documents.dropzone_title', ['type' => $activeType->getLabel()]) }}</p>
								<p class="mb-0 text-muted small">{{ __('candidate.documents.dropzone_hint') }}</p>
								<p class="mb-0 text-muted small mt-1">{{ __('candidate.documents.accepted_formats') }}</p>
							</div>
						</label>
					@else
						<label for="document-file-input" id="document-dropzone"
							class="document-dropzone mb-3 d-block"
							data-type=""
							data-label="">
							<div class="document-dropzone__content text-center py-4 px-3">
								<i class="mdi mdi-cloud-upload-outline document-dropzone__icon d-block mb-2"></i>
								<p class="mb-1 fw-bold text-dark">{{ __('candidate.documents.dropzone_all_title') }}</p>
								<p class="mb-0 text-muted small">{{ __('candidate.documents.dropzone_all_hint') }}</p>
								<p class="mb-0 text-muted small mt-1">{{ __('candidate.documents.accepted_formats') }}</p>
							</div>
						</label>
					@endif

					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="mb-0">{{ __('common.total') }} : {{ __('candidate.applications.total_documents', ['count' => $candidate->documents_count]) }}</h5>

						<div class="d-flex align-items-center">
							<label class="me-2">{{ __('common.show') }}:</label>
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
												<img src="{{ asset(getPlaceholderFilePath($document->file)) }}" alt="" class="img-fluid mx-auto d-block rounded" style="max-width: 100px;">
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
															<i class="mdi mdi-calendar me-2"></i>
															{{ __('common.uploaded') }} {{ $document->created_at->diffForHumans() }}
														</p>
													</li>
												</ul>
											</div>
										</div>
										<div class="col-lg-3 col-md-3">
											<div class="job-list-button-sm text-end d-flex justify-content-end gap-1">
												<form action="{{ route('candidate.my.documents.destroy', $document->id) }}" method="POST">
													@csrf
													@method('DELETE')
													<button type="submit" class="btn btn-danger btn-sm document-action-btn delete-btn">
														<i class="mdi mdi-delete"></i>
														<span>{{ __('common.delete') }}</span>
													</button>
												</form>
												<a href="{{ $document->file_url }}" target="_blank" download class="btn btn-primary btn-sm document-action-btn">
													<i class="mdi mdi-download"></i>
													<span>{{ __('common.download') }}</span>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							@include('candidate.partials.empty_state', [
								'icon' => 'mdi-file-document-outline',
								'title' => $activeType
									? __('candidate.documents.empty_filtered_title', ['type' => $activeType->getLabel()])
									: __('candidate.documents.empty_title'),
								'description' => $activeType
									? __('candidate.documents.empty_filtered_description')
									: __('candidate.documents.empty_description'),
							])
						@endforelse
					</div>

					@if ($candidate->documents->total() > 0)
						<div class="mt-4 d-flex justify-content-center">
							{{ $candidate->documents->appends(request()->query())->links('pagination::bootstrap-5') }}
						</div>

						<div class="mt-2 text-center text-muted small">
							{{ __('common.showing', ['from' => $candidate->documents->firstItem(), 'to' => $candidate->documents->lastItem(), 'total' => $candidate->documents->total(), 'unit' => __('common.documents')]) }}
						</div>
					@endif
				</div>
			</div>
		</div>
	</section>
@endsection

@section('js')
	@php
		$documentI18n = [
			'confirm_delete_title' => __('common.confirm_delete_title'),
			'confirm_delete_text' => __('common.confirm_delete_text'),
			'yes_delete' => __('common.yes_delete'),
			'cancel' => __('common.cancel'),
			'confirm_upload_title' => __('candidate.documents.confirm_upload_title'),
			'confirm_upload_text' => __('candidate.documents.confirm_upload_text'),
			'yes_upload' => __('candidate.documents.yes_upload'),
			'uploading' => __('candidate.documents.uploading'),
			'upload_success' => __('messages.document.upload_success'),
			'upload_failed' => __('candidate.documents.upload_failed'),
			'invalid_file_type' => __('candidate.documents.invalid_file_type'),
			'file_too_large' => __('candidate.documents.file_too_large'),
			'select_type_modal_title' => __('candidate.documents.select_type_modal_title'),
			'select_type_modal_label' => __('candidate.documents.select_type_modal_label'),
			'select_type_modal_required' => __('candidate.documents.select_type_modal_required'),
		];
		$documentTypesOptions = \App\Enums\DocumentType::getWithLabels();
	@endphp
	<style>
		.document-dropzone {
			border: 2px dashed #d6deef;
			border-radius: 0.75rem;
			background: #f8f9fc;
			cursor: pointer;
			transition: border-color 0.15s ease, background-color 0.15s ease;
		}

		.document-dropzone--info {
			cursor: default;
		}

		.document-dropzone.is-dragover,
		.document-type-drop.is-dragover {
			border-color: #2f55d4 !important;
			background: rgba(47, 85, 212, 0.08) !important;
		}

		.document-dropzone.is-uploading {
			opacity: 0.75;
			pointer-events: none;
		}

		.document-dropzone__icon {
			font-size: 2rem;
			color: #2f55d4;
			line-height: 1;
		}

		.document-type-drop.is-dragover .document-type-drop__hint {
			display: block !important;
		}

		.document-type-drop.is-dragover .document-type-drop__label {
			font-weight: 600;
		}

		.document-action-btn {
			display: inline-flex;
			align-items: center;
			gap: 0.35rem;
			line-height: 1.2;
		}

		.document-action-btn .mdi {
			font-size: 14px;
			line-height: 1;
		}
	</style>
	<script>
		const documentI18n = @json($documentI18n);
		const documentTypesOptions = @json($documentTypesOptions);
		const documentStoreUrl = @json(route('candidate.my.documents.store'));
		const csrfToken = @json(csrf_token());
		const maxDocumentSize = 10240 * 1024;
		const allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg', 'webp', 'gif', 'xls', 'xlsx'];
		const allowedDocumentMimePrefixes = ['image/'];

		$(function() {
			const $dropzone = $('#document-dropzone');
			const $fileInput = $('#document-file-input');

			function escapeHtml(text) {
				return $('<div>').text(text).html();
			}

			function getFileExtension(fileName) {
				const parts = fileName.split('.');
				return parts.length > 1 ? parts.pop().toLowerCase() : '';
			}

			function validateFile(file) {
				const extension = getFileExtension(file.name);
				const isAllowedImage = allowedDocumentMimePrefixes.some(function(prefix) {
					return file.type && file.type.startsWith(prefix);
				});

				if (!allowedDocumentExtensions.includes(extension) && !isAllowedImage) {
					Swal.fire({
						icon: 'error',
						title: documentI18n.upload_failed,
						text: documentI18n.invalid_file_type,
					});
					return false;
				}

				if (file.size > maxDocumentSize) {
					Swal.fire({
						icon: 'error',
						title: documentI18n.upload_failed,
						text: documentI18n.file_too_large,
					});
					return false;
				}

				return true;
			}

			function confirmUpload(file, type, typeLabel) {
				return Swal.fire({
					title: documentI18n.confirm_upload_title,
					html: documentI18n.confirm_upload_text
						.replace(':file', escapeHtml(file.name))
						.replace(':type', escapeHtml(typeLabel)),
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#2f55d4',
					cancelButtonColor: '#6c757d',
					confirmButtonText: documentI18n.yes_upload,
					cancelButtonText: documentI18n.cancel,
				}).then(function(result) {
					if (!result.isConfirmed) {
						$fileInput.val('');
						return Promise.resolve(false);
					}

					return uploadDocument(file, type);
				});
			}

			function promptSelectTypeAndUpload(file) {
				let optionsHtml = '<option value="">-- ' + documentI18n.select_type_modal_label + ' --</option>';
				for (const [key, label] of Object.entries(documentTypesOptions)) {
					optionsHtml += `<option value="${key}">${escapeHtml(label)}</option>`;
				}

				Swal.fire({
					title: documentI18n.select_type_modal_title,
					html: `
						<p class="text-muted small mb-3">File: <strong class="text-dark">${escapeHtml(file.name)}</strong></p>
						<div class="text-start mb-2">
							<label class="form-label small fw-bold text-dark">${documentI18n.select_type_modal_label}:</label>
							<select id="swal-document-type" class="form-select form-control" style="width: 100%;">
								${optionsHtml}
							</select>
						</div>
					`,
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#2f55d4',
					cancelButtonColor: '#6c757d',
					confirmButtonText: documentI18n.yes_upload,
					cancelButtonText: documentI18n.cancel,
					preConfirm: function() {
						const selectedVal = $('#swal-document-type').val() || '';
						if (!selectedVal) {
							Swal.showValidationMessage(documentI18n.select_type_modal_required);
						}
						return selectedVal;
					}
				}).then(function(result) {
					if (!result.isConfirmed || !result.value) {
						$fileInput.val('');
						return;
					}
					uploadDocument(file, result.value);
				});
			}

			function uploadDocument(file, type) {
				const formData = new FormData();
				formData.append('file', file);
				formData.append('type', type);
				formData.append('_token', csrfToken);

				$dropzone.addClass('is-uploading');

				Swal.fire({
					title: documentI18n.uploading,
					allowOutsideClick: false,
					didOpen: function() {
						Swal.showLoading();
					},
				});

				return $.ajax({
					url: documentStoreUrl,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'Accept': 'application/json',
						'X-Requested-With': 'XMLHttpRequest',
					}
				}).done(function() {
					Swal.fire({
						icon: 'success',
						title: documentI18n.upload_success,
						timer: 1500,
						showConfirmButton: false,
					}).then(function() {
						window.location.reload();
					});
				}).fail(function(jqXHR) {
					const payload = jqXHR.responseJSON || {};
					const message = payload.message
						|| (payload.errors && Object.values(payload.errors)[0][0])
						|| documentI18n.upload_failed;
					Swal.fire({
						icon: 'error',
						title: documentI18n.upload_failed,
						text: message,
					});
				}).always(function() {
					$dropzone.removeClass('is-uploading');
					$fileInput.val('');
				});
			}

			function handleSelectedFile(file, type, typeLabel) {
				if (!file || !validateFile(file)) {
					$fileInput.val('');
					return;
				}

				if (type && typeLabel) {
					confirmUpload(file, type, typeLabel);
				} else {
					promptSelectTypeAndUpload(file);
				}
			}

			function bindDropTarget($el) {
				if (!$el.length) return;

				$el.on('dragenter dragover', function(e) {
					e.preventDefault();
					e.stopPropagation();
					$(this).addClass('is-dragover');
				});

				$el.on('dragleave drop', function(e) {
					e.preventDefault();
					e.stopPropagation();
					$(this).removeClass('is-dragover');
				});

				$el.on('drop', function(e) {
					const dt = e.originalEvent.dataTransfer;
					const files = dt?.files;
					if (!files || !files.length) return;
					handleSelectedFile(files[0], $(this).data('type') || '', $(this).data('label') || '');
				});
			}

			if ($dropzone.length && $fileInput.length) {
				bindDropTarget($dropzone);

				// Click-to-select is handled natively by <label for="document-file-input">

				$fileInput.on('change', function() {
					if (!this.files || !this.files.length) return;
					handleSelectedFile(
						this.files[0],
						$dropzone.data('type') || '',
						$dropzone.data('label') || ''
					);
				});
			}

			$('.document-type-drop').each(function() {
				bindDropTarget($(this));
			});

			$('#perPage').on('change', function() {
				var perPage = $(this).val();
				var url = new URL(window.location.href);
				url.searchParams.set('per_page', perPage);
				window.location.href = url.toString();
			});

			$('.delete-btn').click(function(e) {
				e.preventDefault();
				Swal.fire({
					title: documentI18n.confirm_delete_title,
					text: documentI18n.confirm_delete_text,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: documentI18n.yes_delete,
					cancelButtonText: documentI18n.cancel
				}).then((result) => {
					if (result.isConfirmed) {
						$(this).closest('form').submit();
					}
				});
			});
		});
	</script>
@endsection
