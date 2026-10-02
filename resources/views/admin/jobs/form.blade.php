@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<div>
				<h4 class="mb-1">{{ isset($job) ? __('admin.jobs.edit') : __('admin.jobs.create') }}</h4>
				<p class="text-muted mb-0">{{ __('admin.jobs.subtitle') }}</p>
			</div>
			<a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i> {{ __('admin.jobs.back_to_list') }}
			</a>
		</div>

		@if ($errors->any())
			<div class="alert alert-danger" role="alert">
				<strong>{{ __('admin.jobs.validation_failed') }}</strong> {{ __('admin.jobs.validation_hint') }}
				<ul class="mb-0 mt-2">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form id="form-add-new-record" method="POST" action="{{ !empty($job) ? route('admin.jobs.update', $job->id) : route('admin.jobs.store') }}">
			@csrf
			@if (!empty($job))
				@method('PATCH')
			@endif

			<ul class="nav nav-pills mb-4" role="tablist">
				<li class="nav-item"><button type="button" class="nav-link active" id="tab-btn-basic" data-bs-toggle="tab" data-bs-target="#tab-basic">{{ __('admin.jobs.tab_basic') }}</button></li>
				<li class="nav-item"><button type="button" class="nav-link" id="tab-btn-content" data-bs-toggle="tab" data-bs-target="#tab-content">{{ __('admin.jobs.tab_content') }}</button></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="tab-basic">
					<div class="card mb-4">
						<div class="card-body row">
							<input type="hidden" name="uuid" value="{{ old('uuid', $job->uuid ?? $uuid ?? '') }}">
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label" for="job_code">{{ __('admin.jobs.code') }}</label>
									<div class="input-group">
										<input type="text" class="form-control dt-full-name @error('code') is-invalid @enderror" id="job_code" name="code" placeholder="{{ __('admin.jobs.code') }}" required value="{{ old('code', $job->code ?? $code ?? '') }}" maxlength="50" />
										<button type="button" class="btn btn-outline-secondary" id="btn-generate-code" title="{{ __('admin.jobs.regenerate_code') }}">
											<i class="ti ti-refresh me-1"></i> <span class="d-none d-sm-inline">{{ __('admin.jobs.regenerate_code') }}</span>
										</button>
									</div>
									@error('code') <small class="text-danger d-block">{{ $message }}</small> @enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.jobs.select_batch') }}</label>
									<div class="input-group input-group-merge">
										<select name="batch_id" id="batch_id" class="form-control @error('batch_id') is-invalid @enderror" required>
											<option value="">{{ __('admin.jobs.select_batch') }}</option>
											@foreach ($batches as $batch)
												@php
													$excludeJobId = (isset($job) && (int) $job->batch_id === (int) $batch->id) ? $job->id : null;
													$batchStart = \Illuminate\Support\Carbon::parse($batch->start_date)->format('d/m/Y');
													$batchEnd = \Illuminate\Support\Carbon::parse($batch->end_date)->format('d/m/Y');
												@endphp
												<option value="{{ $batch->id }}"
													@selected(old(`batch_id`, $job->batch_id ?? request('batch_id', '')) == $batch->id)
													data-batch-quota="{{ (int) $batch->quota }}"
													data-allocated-quota="{{ $batch->allocatedQuota($excludeJobId) }}"
													data-remaining-quota="{{ $batch->remainingQuota($excludeJobId) }}">
													{{ $batch->name }} | {{ $batchStart }} - {{ $batchEnd }}
												</option>
											@endforeach
										</select>
									</div>
									@error('batch_id') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.jobs.select_category') }}</label>
									<div class="input-group input-group-merge">
										<select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
											<option value="">{{ __('admin.jobs.select_category') }}</option>
											@foreach ($categories as $category)
												<option value="{{ $category->id }}" @selected(old(`category_id`, $job->category_id ?? '') == $category->id)>
													{{ $category->name }}
												</option>
											@endforeach
										</select>
									</div>
									@error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">{{ __('admin.jobs.title_col') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name @error('title') is-invalid @enderror" name="title" placeholder="{{ __('admin.jobs.title_col') }}" value="{{ old('title', $job->title ?? '') }}" required />
								</div>
								@error('title') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							@php
								$initialJobImages = collect(old('images', $job->images ?? []))
									->map(function ($path) {
										if (! is_string($path) || ! \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
											return null;
										}

										return [
											'path' => $path,
											'url' => \Illuminate\Support\Facades\Storage::url($path),
										];
									})
									->filter()
									->values()
									->all();
							@endphp
							<div class="col-md-12 mb-3">
								<label class="form-label d-block">{{ __('admin.jobs.images') }}</label>
								<input type="file" id="job-image-input" class="d-none" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" multiple>
								<div id="job-image-dropzone" class="job-image-dropzone @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
									<div id="job-image-gallery" class="job-image-gallery"></div>
									<div id="job-image-placeholder" class="job-image-placeholder">
										<i class="ti ti-photo-plus ti-lg mb-2"></i>
										<p class="mb-1 fw-medium">{{ __('admin.jobs.image_drop_title') }}</p>
										<small class="text-muted">{{ __('admin.jobs.image_drop_hint') }}</small>
									</div>
								</div>
								<div class="d-flex flex-wrap align-items-center gap-2 mt-2">
									<button type="button" class="btn btn-sm btn-outline-primary" id="job-image-browse">{{ __('admin.jobs.image_browse') }}</button>
									<small class="text-muted" id="job-image-counter">{{ __('admin.jobs.image_counter', ['count' => count($initialJobImages), 'max' => 3]) }}</small>
								</div>
								<small class="text-muted d-block mt-2">{{ __('admin.jobs.image_help') }}</small>
								<div id="job-image-error" class="text-danger small mt-1 d-none"></div>
								<div id="job-image-hidden-inputs"></div>
								@error('images') <small class="text-danger d-block">{{ $message }}</small> @enderror
								@error('images.*') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-12 mb-3">
								<label class="form-label">{{ __('admin.jobs.type') }}</label>
								<div class="input-group input-group-merge">
									<select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
										<option value="">{{ __('admin.jobs.select_type') }}</option>
										@foreach (\App\Enums\JobType::cases() as $jobType)
											<option value="{{ $jobType->value }}" @selected(old(`type`, $job->type ?? '') === $jobType->value)>{{ $jobType->getLabel() }}</option>
										@endforeach
									</select>
								</div>
								@error('type') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="row g-3 mb-3">
								@php
									$salaryMinValue = old('salary_min', $job->salary_min ?? '');
									$salaryMaxValue = old('salary_max', $job->salary_max ?? '');
									$salaryMinDisplay = $salaryMinValue !== '' && $salaryMinValue !== null
										? number_format((int) $salaryMinValue, 0, ',', '.')
										: '';
									$salaryMaxDisplay = $salaryMaxValue !== '' && $salaryMaxValue !== null
										? number_format((int) $salaryMaxValue, 0, ',', '.')
										: '';
									$showSalary = old('is_show_salary', isset($job) ? ($job->is_show_salary ? '1' : '0') : '1');
								@endphp
								<div class="col-lg-4 col-md-6">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.jobs.quota') }}</label>
										<input type="number" name="quota" id="job_quota" min="1" class="form-control @error('quota') is-invalid @enderror" required value="{{ old('quota', $job->quota ?? 0) }}">
										<small class="text-muted d-block mt-1" id="batch-quota-info">{{ __('admin.jobs.batch_quota_info', ['quota' => '-', 'allocated' => '-', 'remaining' => '-']) }}</small>
										@error('quota') <small class="text-danger d-block">{{ $message }}</small> @enderror
										<div id="batch-quota-warning" class="alert alert-warning py-2 px-3 mt-2 mb-0 d-none small" role="alert"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-6">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.jobs.salary_min') }}</label>
										<div class="input-group">
											<span class="input-group-text">Rp</span>
											<input type="text" inputmode="numeric" id="salary_min_display" class="form-control salary-amount-input @error('salary_min') is-invalid @enderror" placeholder="{{ __('admin.jobs.salary_min_placeholder') }}" required value="{{ $salaryMinDisplay }}" autocomplete="off">
											<input type="hidden" name="salary_min" id="salary_min" value="{{ $salaryMinValue }}">
										</div>
										@error('salary_min') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
								</div>
								<div class="col-lg-4 col-md-6">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.jobs.salary_max') }}</label>
										<div class="input-group">
											<span class="input-group-text">Rp</span>
											<input type="text" inputmode="numeric" id="salary_max_display" class="form-control salary-amount-input @error('salary_max') is-invalid @enderror" placeholder="{{ __('admin.jobs.salary_max_placeholder') }}" required value="{{ $salaryMaxDisplay }}" autocomplete="off">
											<input type="hidden" name="salary_max" id="salary_max" value="{{ $salaryMaxValue }}">
										</div>
										<small class="text-muted">{{ __('admin.jobs.salary_range_hint') }}</small>
										@error('salary_max') <small class="text-danger d-block">{{ $message }}</small> @enderror
									</div>
								</div>
								<div class="col-lg-4 col-md-6">
									<div class="mb-3">
										<label class="form-label d-block">{{ __('admin.jobs.show_salary') }}</label>
										<input type="hidden" name="is_show_salary" value="0">
										<label class="switch switch-primary switch-show-salary mt-1">
											<input type="checkbox" class="switch-input" name="is_show_salary" id="show_salary_switch" value="1" @checked((string) $showSalary === '1')>
											<span class="switch-toggle-slider">
												<span class="{{ (string) $showSalary === '1' ? 'switch-on' : 'switch-off' }}"></span>
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="row g-3">
								<div class="col-md-6 mb-3">
									<label class="form-label">{{ __('admin.jobs.min_education') }}</label>
									<select name="min_education" class="form-control @error('min_education') is-invalid @enderror">
										<option value="">{{ __('admin.jobs.no_requirement') }}</option>
										@foreach (\App\Enums\EducationLevel::cases() as $level)
											<option value="{{ $level->value }}" @selected(old(`min_education`, $job->min_education ?? '') === $level->value)>{{ $level->label() }}</option>
										@endforeach
									</select>
									@error('min_education') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
								<div class="col-md-6 mb-3">
									<label class="form-label">{{ __('admin.jobs.experience') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" name="experience" class="form-control @error('experience') is-invalid @enderror" value="{{ old('experience', $job->experience ?? '') }}" required placeholder="{{ __('admin.jobs.experience_placeholder') }}">
									</div>
									<small class="text-muted">{{ __('admin.jobs.experience_hint') }}</small>
									@error('experience') <small class="text-danger d-block">{{ $message }}</small> @enderror
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="tab-content">
					<div class="row">
						<div class="col-md-6 mb-4">
							<div class="card h-100">
								<div class="card-header"><h5 class="mb-0">{{ __('admin.jobs.qualification') }}</h5></div>
								<div class="card-body">
									<div id="quill-editor-qualification" class="mb-3 @error('qualification') border border-danger rounded @enderror" style="height: 220px;"></div>
									<textarea class="d-none" name="qualification" id="quill-editor-qualification-area">{{ old('qualification', $job->qualification ?? '') }}</textarea>
									@error('qualification') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
						</div>
						<div class="col-md-6 mb-4">
							<div class="card h-100">
								<div class="card-header"><h5 class="mb-0">{{ __('admin.jobs.description') }}</h5></div>
								<div class="card-body">
									<div id="quill-editor-description" class="mb-3 @error('description') border border-danger rounded @enderror" style="height: 220px;"></div>
									<textarea class="d-none" name="description" id="quill-editor-description-area">{{ old('description', $job->description ?? '') }}</textarea>
									@error('description') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-end gap-2 mt-2">
				<a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">{{ __('admin.form.cancel') }}</a>
				<button type="submit" class="btn btn-primary">{{ __('admin.jobs.save') }}</button>
			</div>
		</form>
	</div>
@endsection
@section('css')
	<style>
		.job-image-dropzone {
			border: 2px dashed rgba(67, 89, 113, 0.35);
			border-radius: 0.75rem;
			padding: 1rem;
			background: rgba(67, 89, 113, 0.03);
			cursor: pointer;
			transition: border-color 0.15s ease, background-color 0.15s ease;
		}

		.job-image-dropzone.is-dragover {
			border-color: var(--bs-primary, #7367f0);
			background: rgba(115, 103, 240, 0.08);
		}

		.job-image-dropzone.is-uploading {
			opacity: 0.75;
			pointer-events: none;
		}

		.job-image-gallery {
			display: flex;
			flex-wrap: wrap;
			gap: 0.75rem;
		}

		.job-image-gallery:not(:empty) + .job-image-placeholder {
			display: none;
		}

		.job-image-item {
			position: relative;
			width: 120px;
		}

		.job-image-item img,
		.job-image-item__preview {
			width: 120px;
			height: 120px;
			object-fit: cover;
			border-radius: 0.5rem;
			border: 1px solid rgba(67, 89, 113, 0.15);
			display: block;
		}

		.job-image-item__preview {
			background: rgba(67, 89, 113, 0.08);
			display: flex;
			align-items: center;
			justify-content: center;
			color: #6c757d;
		}

		.job-image-item__progress {
			margin-top: 0.35rem;
		}

		.job-image-item__progress .progress {
			height: 0.45rem;
			border-radius: 999px;
			background: rgba(67, 89, 113, 0.12);
		}

		.job-image-item__progress .progress-bar {
			font-size: 0.65rem;
			line-height: 0.45rem;
		}

		.switch-show-salary {
			transform: scale(1.2);
			transform-origin: left center;
		}

		.job-image-item__remove {
			position: absolute;
			top: 0.35rem;
			right: 0.35rem;
			width: 1.75rem;
			height: 1.75rem;
			border: 0;
			border-radius: 999px;
			background: rgba(255, 255, 255, 0.95);
			color: #ea5455;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
		}

		.job-image-placeholder {
			color: #6c757d;
			text-align: center;
			padding: 1rem 0.5rem;
		}

		.ql-toolbar.ql-snow {
			border-top-left-radius: 0.375rem;
			border-top-right-radius: 0.375rem;
			border-color: #dbdade;
		}
		.ql-container.ql-snow {
			border-bottom-left-radius: 0.375rem;
			border-bottom-right-radius: 0.375rem;
			border-color: #dbdade;
			font-family: inherit;
			font-size: 0.9375rem;
		}
	</style>
@endsection
@section('js')
	<script>
		$(function() {
			const tabFieldMap = {
				'tab-basic': ['batch_id', 'category_id', 'title', 'images', 'type', 'quota', 'salary_min', 'salary_max', 'is_show_salary', 'min_education', 'experience'],
				'tab-content': ['qualification', 'description'],
			};
			const errorFields = @json($errors->keys());

			if (errorFields.length) {
				for (const [tabId, fields] of Object.entries(tabFieldMap)) {
					if (fields.some(field => errorFields.includes(field))) {
						const $tabButton = $('#tab-btn-' + tabId.replace('tab-', ''));
						if ($tabButton.length && window.bootstrap) {
							bootstrap.Tab.getOrCreateInstance($tabButton[0]).show();
						}
						break;
					}
				}

				const $alert = $('.alert-danger');
				if ($alert.length) {
					$alert[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			}

			const salaryInputs = [
				{ $display: $('#salary_min_display'), $hidden: $('#salary_min') },
				{ $display: $('#salary_max_display'), $hidden: $('#salary_max') },
			];

			function parseSalaryAmount(value) {
				const digits = String(value || '').replace(/\D/g, '');
				return digits ? parseInt(digits, 10) : '';
			}

			function formatSalaryAmount(value) {
				const amount = parseSalaryAmount(value);
				if (amount === '') {
					return '';
				}

				return new Intl.NumberFormat('id-ID').format(amount);
			}

			function syncSalaryHiddenInput($displayInput, $hiddenInput) {
				if (!$displayInput.length || !$hiddenInput.length) return;
				const amount = parseSalaryAmount($displayInput.val());
				$hiddenInput.val(amount === '' ? '' : String(amount));
				$displayInput.val(amount === '' ? '' : formatSalaryAmount(amount));
			}

			salaryInputs.forEach(function (pair) {
				if (!pair.$display.length || !pair.$hidden.length) return;

				pair.$display.on('input', function () {
					const digits = $(this).val().replace(/\D/g, '');
					pair.$hidden.val(digits);
					$(this).val(digits ? formatSalaryAmount(digits) : '');
				});

				pair.$display.on('blur', function () {
					syncSalaryHiddenInput(pair.$display, pair.$hidden);
				});
			});

			$('#form-add-new-record').on('submit', function () {
				salaryInputs.forEach(function (pair) {
					syncSalaryHiddenInput(pair.$display, pair.$hidden);
				});
			});

			$('#show_salary_switch').on('change', function () {
				const $state = $(this).closest('.switch').find('.switch-toggle-slider span');
				if (!$state.length) return;
				$state.toggleClass('switch-on', this.checked);
				$state.toggleClass('switch-off', !this.checked);
			});

			@php
				$jobImageI18n = [
					'invalidType' => __('admin.js.image_invalid_type'),
					'tooLarge' => __('admin.js.image_too_large'),
					'maxReached' => __('admin.js.image_max_reached'),
					'uploadFailed' => __('admin.js.image_upload_failed'),
					'uploading' => __('admin.js.image_uploading'),
					'counter' => __('admin.jobs.image_counter'),
				];
				$batchQuotaI18n = [
					'info' => __('admin.jobs.batch_quota_info'),
					'warning' => __('admin.jobs.quota_exceeds_batch_warning'),
				];
			@endphp
			const jobImageI18n = @json($jobImageI18n);
			const batchQuotaI18n = @json($batchQuotaI18n);
			const jobUuid = @json(old('uuid', $job->uuid ?? $uuid ?? ''));
			const maxJobImages = 3;
			const maxImageSize = 5 * 1024 * 1024;
			const allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
			const uploadUrl = @json(route('admin.jobs.upload-image'));
			const deleteUrl = @json(route('admin.jobs.destroy-image'));
			const csrfToken = @json(csrf_token());
			let jobImages = @json($initialJobImages);
			let pendingUploads = [];

			const $batchSelect = $('#batch_id');
			const $jobQuotaInput = $('#job_quota');
			const $batchQuotaInfo = $('#batch-quota-info');
			const $batchQuotaWarning = $('#batch-quota-warning');

			const $imageInput = $('#job-image-input');
			const $imageDropzone = $('#job-image-dropzone');
			const $imageGallery = $('#job-image-gallery');
			const $imagePlaceholder = $('#job-image-placeholder');
			const $imageBrowseBtn = $('#job-image-browse');
			const $imageCounter = $('#job-image-counter');
			const $imageError = $('#job-image-error');
			const $hiddenInputs = $('#job-image-hidden-inputs');

			function showImageError(message) {
				if (!$imageError.length) return;
				$imageError.text(message).removeClass('d-none');
				$imageDropzone.addClass('is-invalid');
			}

			function clearImageError() {
				if (!$imageError.length) return;
				$imageError.text('').addClass('d-none');
				$imageDropzone.removeClass('is-invalid');
			}

			function updateImageCounter() {
				if (!$imageCounter.length) return;
				$imageCounter.text(jobImageI18n.counter
					.replace(':count', jobImages.length + pendingUploads.length)
					.replace(':max', maxJobImages));
			}

			function syncHiddenInputs() {
				if (!$hiddenInputs.length) return;
				const html = jobImages.map(function(image) {
					return '<input type="hidden" name="images[]" value="' + image.path.replace(/"/g, '&quot;') + '">';
				}).join('');
				$hiddenInputs.html(html);
			}

			function renderJobImages() {
				if (!$imageGallery.length) return;

				const uploadedHtml = jobImages.map(function(image, index) {
					return ''
						+ '<div class="job-image-item" data-index="' + index + '">'
						+ '<img src="' + image.url + '" alt="">'
						+ '<button type="button" class="job-image-item__remove" data-index="' + index + '" aria-label="Remove">'
						+ '<i class="ti ti-x"></i>'
						+ '</button>'
						+ '</div>';
				}).join('');

				const pendingHtml = pendingUploads.map(function(item) {
					const preview = item.previewUrl
						? '<img src="' + item.previewUrl + '" alt="">'
						: '<div class="job-image-item__preview"><i class="ti ti-photo"></i></div>';

					return ''
						+ '<div class="job-image-item job-image-item--uploading" data-upload-id="' + item.id + '">'
						+ preview
						+ '<div class="job-image-item__progress">'
						+ '<div class="progress">'
						+ '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:' + item.progress + '%" aria-valuenow="' + item.progress + '" aria-valuemin="0" aria-valuemax="100">' + item.progress + '%</div>'
						+ '</div>'
						+ '</div>'
						+ '</div>';
				}).join('');

				$imageGallery.html(uploadedHtml + pendingHtml);

				if ($imagePlaceholder.length) {
					$imagePlaceholder.toggle((jobImages.length + pendingUploads.length) === 0);
				}

				updateImageCounter();
				syncHiddenInputs();
			}

			function updatePendingUploadProgress(uploadId, progress) {
				const $bar = $imageGallery.find('[data-upload-id="' + uploadId + '"] .progress-bar');
				if (!$bar.length) return;
				$bar.css('width', progress + '%').attr('aria-valuenow', String(progress)).text(progress + '%');
			}

			function getSelectedBatchOption() {
				if (!$batchSelect.length) return null;
				const selectEl = $batchSelect[0];
				const option = selectEl.options[selectEl.selectedIndex];
				return option && option.value ? option : null;
			}

			function updateBatchQuotaDisplay() {
				const option = getSelectedBatchOption();

				if (!$batchQuotaInfo.length) return;

				if (!option) {
					$batchQuotaInfo.text(batchQuotaI18n.info
						.replace(':quota', '-')
						.replace(':allocated', '-')
						.replace(':remaining', '-'));
					$batchQuotaWarning.addClass('d-none');
					return;
				}

				const batchQuota = parseInt(option.dataset.batchQuota || '0', 10);
				const allocated = parseInt(option.dataset.allocatedQuota || '0', 10);
				const remaining = parseInt(option.dataset.remainingQuota || '0', 10);
				const requestedQuota = parseInt($jobQuotaInput.val() || '0', 10);

				$batchQuotaInfo.text(batchQuotaI18n.info
					.replace(':quota', batchQuota)
					.replace(':allocated', allocated)
					.replace(':remaining', remaining));

				if (!$batchQuotaWarning.length) return;

				if (requestedQuota > remaining) {
					$batchQuotaWarning.text(batchQuotaI18n.warning.replace(':remaining', remaining)).removeClass('d-none');
				} else {
					$batchQuotaWarning.addClass('d-none');
				}
			}

			function validateImageFile(file) {
				if (!allowedImageTypes.includes(file.type)) {
					showImageError(jobImageI18n.invalidType);
					return false;
				}

				if (file.size > maxImageSize) {
					showImageError(jobImageI18n.tooLarge);
					return false;
				}

				clearImageError();
				return true;
			}

			function uploadJobImage(file, onProgress) {
				const formData = new FormData();
				formData.append('image', file);
				formData.append('job_uuid', jobUuid);
				formData.append('_token', csrfToken);

				return $.ajax({
					url: uploadUrl,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json'
					},
					xhr: function() {
						const xhr = $.ajaxSettings.xhr();
						if (xhr.upload && typeof onProgress === 'function') {
							xhr.upload.addEventListener('progress', function(event) {
								if (event.lengthComputable) {
									onProgress(Math.min(100, Math.round((event.loaded / event.total) * 100)));
								}
							});
						}
						return xhr;
					}
				}).catch(function(jqXHR) {
					const payload = jqXHR.responseJSON || {};
					const message = payload.errors?.image?.[0]
						|| payload.message
						|| jobImageI18n.uploadFailed;
					throw new Error(message);
				});
			}

			function removeJobImage(index) {
				const image = jobImages[index];
				if (!image) return Promise.resolve();

				return $.ajax({
					url: deleteUrl,
					type: 'DELETE',
					contentType: 'application/json',
					data: JSON.stringify({
						job_uuid: jobUuid,
						path: image.path,
						_token: csrfToken
					}),
					headers: {
						'X-CSRF-TOKEN': csrfToken,
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json'
					}
				}).done(function() {
					jobImages.splice(index, 1);
					renderJobImages();
					clearImageError();
				});
			}

			async function handleSelectedFiles(fileList) {
				const files = Array.from(fileList || []);
				if (!files.length) return;

				if ((jobImages.length + pendingUploads.length) >= maxJobImages) {
					showImageError(jobImageI18n.maxReached);
					return;
				}

				$imageDropzone.addClass('is-uploading');

				try {
					for (const file of files) {
						if ((jobImages.length + pendingUploads.length) >= maxJobImages) {
							showImageError(jobImageI18n.maxReached);
							break;
						}

						if (!validateImageFile(file)) {
							continue;
						}

						const pending = {
							id: 'upload-' + Date.now() + '-' + Math.random().toString(16).slice(2),
							progress: 0,
							previewUrl: URL.createObjectURL(file),
						};
						pendingUploads.push(pending);
						renderJobImages();

						try {
							const uploaded = await uploadJobImage(file, function(progress) {
								pending.progress = progress;
								updatePendingUploadProgress(pending.id, progress);
							});

							pendingUploads = pendingUploads.filter(function(item) {
								return item.id !== pending.id;
							});
							URL.revokeObjectURL(pending.previewUrl);

							jobImages.push({
								path: uploaded.path,
								url: uploaded.url,
							});
							renderJobImages();
						} catch (uploadError) {
							pendingUploads = pendingUploads.filter(function(item) {
								return item.id !== pending.id;
							});
							URL.revokeObjectURL(pending.previewUrl);
							renderJobImages();
							throw uploadError;
						}
					}

					clearImageError();
				} catch (error) {
					showImageError(error.message || jobImageI18n.uploadFailed);
				} finally {
					$imageDropzone.removeClass('is-uploading');
					$imageInput.val('');
				}
			}

			$imageBrowseBtn.on('click', function(event) {
				event.preventDefault();
				event.stopPropagation();
				$imageInput.trigger('click');
			});

			$imageDropzone.on('click', function(event) {
				if ($(event.target).closest('.job-image-item__remove, #job-image-browse').length) {
					return;
				}
				if ((jobImages.length + pendingUploads.length) >= maxJobImages) {
					showImageError(jobImageI18n.maxReached);
					return;
				}
				$imageInput.trigger('click');
			});

			$imageInput.on('change', function() {
				handleSelectedFiles(this.files);
			});

			$imageGallery.on('click', '.job-image-item__remove', function(event) {
				event.preventDefault();
				const index = parseInt($(this).data('index'), 10);
				removeJobImage(index);
			});

			$imageDropzone.on('dragenter dragover', function(event) {
				event.preventDefault();
				event.stopPropagation();
				$imageDropzone.addClass('is-dragover');
			});

			$imageDropzone.on('dragleave drop', function(event) {
				event.preventDefault();
				event.stopPropagation();
				$imageDropzone.removeClass('is-dragover');
			});

			$imageDropzone.on('drop', function(event) {
				const dt = event.originalEvent.dataTransfer;
				handleSelectedFiles(dt?.files);
			});

			renderJobImages();
			updateBatchQuotaDisplay();

			$batchSelect.on('change', updateBatchQuotaDisplay);
			$jobQuotaInput.on('input', updateBatchQuotaDisplay);

			const toolbarOptions = [
				['bold', 'italic', 'underline', 'strike'],
				['blockquote', 'code-block'],
				['link', 'image', 'video', 'formula'],
				[{ 'header': 1 }, { 'header': 2 }],
				[{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
				[{ 'script': 'sub' }, { 'script': 'super' }],
				[{ 'indent': '-1' }, { 'indent': '+1' }],
				[{ 'direction': 'rtl' }],
				[{ 'size': ['small', false, 'large', 'huge'] }],
				[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
				[{ 'color': [] }, { 'background': [] }],
				[{ 'font': [] }],
				[{ 'align': [] }],
				['clean']
			];

			const $qualificationArea = $('#quill-editor-qualification-area');
			if ($qualificationArea.length) {
				const qualEditor = new Quill('#quill-editor-qualification', {
					theme: 'snow',
					modules: { toolbar: toolbarOptions },
					placeholder: 'Tuliskan kualifikasi lowongan pekerjaan di sini...'
				});
				qualEditor.root.innerHTML = $qualificationArea.val() || '';

				qualEditor.on('text-change', function() {
					$qualificationArea.val(qualEditor.root.innerHTML);
				});
				$qualificationArea.on('input', function() {
					qualEditor.root.innerHTML = $qualificationArea.val();
				});
			}

			const $descriptionArea = $('#quill-editor-description-area');
			if ($descriptionArea.length) {
				const descEditor = new Quill('#quill-editor-description', {
					theme: 'snow',
					modules: { toolbar: toolbarOptions },
					placeholder: 'Tuliskan deskripsi tugas dan tanggung jawab di sini...'
				});
				descEditor.root.innerHTML = $descriptionArea.val() || '';

				descEditor.on('text-change', function() {
					$descriptionArea.val(descEditor.root.innerHTML);
				});
				$descriptionArea.on('input', function() {
					descEditor.root.innerHTML = $descriptionArea.val();
				});
			}

			$('#btn-generate-code').on('click', function() {
				const randomPart = Math.random().toString(16).substring(2, 12).toUpperCase();
				$('#job_code').val('#' + randomPart).trigger('focus');
			});
		});
	</script>
@endsection
