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
				<li class="nav-item"><button type="button" class="nav-link" id="tab-btn-scoring" data-bs-toggle="tab" data-bs-target="#tab-scoring">{{ __('admin.jobs.tab_scoring') }}</button></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="tab-basic">
					<div class="card mb-4">
						<div class="card-body row">
							<input type="hidden" name="uuid" value="{{ old('uuid', $job->uuid ?? $uuid ?? '') }}">
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.jobs.code') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="code" readonly placeholder="{{ __('admin.jobs.code') }}" required value="{{ old('code', $job->code ?? $code ?? '') }}" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.jobs.select_batch') }}</label>
									<div class="input-group input-group-merge">
										<select name="batch_id" id="batch_id" class="form-control @error('batch_id') is-invalid @enderror" required>
											<option value="">{{ __('admin.jobs.select_batch') }}</option>
											@foreach ($batches as $batch)
												<option value="{{ $batch->id }}" @selected(old('batch_id', $job->batch_id ?? '') == $batch->id)>
													{{ $batch->code }} - {{ $batch->name }} | {{ $batch->start_date }} - {{ $batch->end_date }}
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
												<option value="{{ $category->id }}" @selected(old('category_id', $job->category_id ?? '') == $category->id)>
													{{ $category->name }}
												</option>
											@endforeach
										</select>
									</div>
									@error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
							<div class="mb-3">
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
								<input type="file" id="job-image-input" class="d-none" accept="image/jpeg,image/png,image/webp" multiple>
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
							<div class="mb-3">
								<label class="form-label">{{ __('admin.jobs.type') }}</label>
								<div class="input-group input-group-merge">
									<select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
										<option value="">{{ __('admin.jobs.select_type') }}</option>
										@foreach (\App\Enums\JobType::cases() as $jobType)
											<option value="{{ $jobType->value }}" @selected(old('type', $job->type ?? '') === $jobType->value)>{{ $jobType->getLabel() }}</option>
										@endforeach
									</select>
								</div>
								@error('type') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="row align-items-end">
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
								<div class="col-md-2">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.jobs.quota') }}</label>
										<div class="input-group input-group-merge">
											<input type="number" name="quota" class="form-control @error('quota') is-invalid @enderror" required value="{{ old('quota', $job->quota ?? 0) }}">
										</div>
										@error('quota') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.jobs.salary_min') }}</label>
										<div class="input-group input-group-merge">
											<span class="input-group-text">Rp</span>
											<input type="text" inputmode="numeric" id="salary_min_display" class="form-control salary-amount-input @error('salary_min') is-invalid @enderror" placeholder="{{ __('admin.jobs.salary_min_placeholder') }}" required value="{{ $salaryMinDisplay }}" autocomplete="off">
											<input type="hidden" name="salary_min" id="salary_min" value="{{ $salaryMinValue }}">
										</div>
										@error('salary_min') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.jobs.salary_max') }}</label>
										<div class="input-group input-group-merge">
											<span class="input-group-text">Rp</span>
											<input type="text" inputmode="numeric" id="salary_max_display" class="form-control salary-amount-input @error('salary_max') is-invalid @enderror" placeholder="{{ __('admin.jobs.salary_max_placeholder') }}" required value="{{ $salaryMaxDisplay }}" autocomplete="off">
											<input type="hidden" name="salary_max" id="salary_max" value="{{ $salaryMaxValue }}">
										</div>
										<small class="text-muted">{{ __('admin.jobs.salary_range_hint') }}</small>
										@error('salary_max') <small class="text-danger d-block">{{ $message }}</small> @enderror
									</div>
								</div>
								<div class="col-md-2">
									<div class="mb-3">
										<label class="form-label d-block">{{ __('admin.jobs.show_salary') }}</label>
										<input type="hidden" name="is_show_salary" value="0">
										<label class="switch switch-primary switch-sm mt-1">
											<input type="checkbox" class="switch-input" name="is_show_salary" id="show_salary_switch" value="1" @checked((string) $showSalary === '1')>
											<span class="switch-toggle-slider">
												<span class="{{ (string) $showSalary === '1' ? 'switch-on' : 'switch-off' }}"></span>
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="mb-3">
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

				<div class="tab-pane fade" id="tab-scoring">
					<div class="card mb-4">
						<div class="card-header"><h5 class="mb-0">{{ __('admin.jobs.auto_scoring') }}</h5></div>
						<div class="card-body row">
							@php
								$criteria = isset($job) ? $job->criteria : null;
							@endphp
							<div class="col-md-4 mb-3">
								<label class="form-label">{{ __('admin.jobs.min_education') }}</label>
								<select name="min_education" class="form-control @error('min_education') is-invalid @enderror">
									<option value="">{{ __('admin.jobs.no_requirement') }}</option>
									@foreach (\App\Enums\EducationLevel::cases() as $level)
										<option value="{{ $level->value }}" @selected(old('min_education', $criteria?->min_education) === $level->value)>{{ $level->label() }}</option>
									@endforeach
								</select>
								@error('min_education') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-12 mb-3">
								<small class="text-muted">{{ __('admin.jobs.experience_scoring_hint') }}</small>
							</div>
							<div class="col-md-12"><hr><p class="mb-2"><strong>{{ __('admin.jobs.weight_title') }}</strong></p></div>
							<div class="col-12 mb-3">
								<div id="weight-total-alert" class="alert alert-warning d-none mb-0" role="alert">
									<strong>{{ __('admin.jobs.weight_adjust') }}</strong>
									<span id="weight-total-message">{{ __('admin.js.weight_default') }}</span>
								</div>
								<div id="weight-total-success" class="alert alert-success d-none mb-0" role="alert">
									{{ __('admin.jobs.weight_ready') }}
								</div>
							</div>
							<div class="col-md-3 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_education') }}</label>
								<input type="number" min="0" max="100" name="weight_education" class="form-control weight-input @error('weight_education') is-invalid @enderror" value="{{ old('weight_education', $criteria?->weight_education ?? 30) }}">
								@error('weight_education') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-3 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_experience') }}</label>
								<input type="number" min="0" max="100" name="weight_experience" class="form-control weight-input @error('weight_experience') is-invalid @enderror" value="{{ old('weight_experience', $criteria?->weight_experience ?? 30) }}">
								@error('weight_experience') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-3 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_profile') }}</label>
								<input type="number" min="0" max="100" name="weight_profile" class="form-control weight-input @error('weight_profile') is-invalid @enderror" value="{{ old('weight_profile', $criteria?->weight_profile ?? 20) }}">
								@error('weight_profile') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-3 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_cover_letter') }}</label>
								<input type="number" min="0" max="100" name="weight_cover_letter" class="form-control weight-input @error('weight_cover_letter') is-invalid @enderror" value="{{ old('weight_cover_letter', $criteria?->weight_cover_letter ?? 20) }}">
								@error('weight_cover_letter') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-12"><hr><p class="mb-2"><strong>{{ __('admin.jobs.threshold_title') }}</strong></p></div>
							<div class="col-md-6 mb-3">
								<label class="form-label">{{ __('admin.jobs.threshold_shortlist') }}</label>
								<input type="number" min="0" max="100" name="threshold_shortlist" class="form-control @error('threshold_shortlist') is-invalid @enderror" value="{{ old('threshold_shortlist', $criteria?->threshold_shortlist ?? 70) }}">
								<small class="text-muted">{{ __('admin.jobs.threshold_shortlist_hint') }}</small>
								@error('threshold_shortlist') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">{{ __('admin.jobs.threshold_review') }}</label>
								<input type="number" min="0" max="100" name="threshold_reject" class="form-control @error('threshold_reject') is-invalid @enderror" value="{{ old('threshold_reject', $criteria?->threshold_reject ?? 40) }}">
								<small class="text-muted">{{ __('admin.jobs.threshold_review_hint') }}</small>
								@error('threshold_reject') <small class="text-danger d-block">{{ $message }}</small> @enderror
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

		.job-image-item img {
			width: 120px;
			height: 120px;
			object-fit: cover;
			border-radius: 0.5rem;
			border: 1px solid rgba(67, 89, 113, 0.15);
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
	</style>
@endsection
@section('js')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const tabFieldMap = {
				'tab-basic': ['batch_id', 'category_id', 'title', 'images', 'type', 'quota', 'salary_min', 'salary_max', 'is_show_salary', 'experience'],
				'tab-content': ['qualification', 'description'],
				'tab-scoring': [
					'min_education',
					'weight_education', 'weight_experience',
					'weight_profile', 'weight_cover_letter', 'threshold_shortlist', 'threshold_reject'
				],
			};
			const errorFields = @json($errors->keys());

			if (errorFields.length) {
				for (const [tabId, fields] of Object.entries(tabFieldMap)) {
					if (fields.some(field => errorFields.includes(field))) {
						const tabButton = document.getElementById('tab-btn-' + tabId.replace('tab-', ''));
						if (tabButton) {
							bootstrap.Tab.getOrCreateInstance(tabButton).show();
						}
						break;
					}
				}

				const alert = document.querySelector('.alert-danger');
				if (alert) {
					alert.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			}

			const weightInputs = document.querySelectorAll('.weight-input');
			const weightAlert = document.getElementById('weight-total-alert');
			const weightSuccess = document.getElementById('weight-total-success');
			const weightMessage = document.getElementById('weight-total-message');

			function replacePlaceholders(template, replacements) {
				return Object.entries(replacements).reduce(function (message, entry) {
					return message.replace(':' + entry[0], entry[1]);
				}, template);
			}

			function updateWeightTotal() {
				if (!weightInputs.length || !weightAlert || !weightSuccess || !weightMessage) return;

				const total = Array.from(weightInputs).reduce(function (sum, input) {
					return sum + (parseInt(input.value, 10) || 0);
				}, 0);

				if (total === 100) {
					weightAlert.classList.add('d-none');
					weightSuccess.classList.remove('d-none');
					return;
				}

				weightSuccess.classList.add('d-none');
				weightAlert.classList.remove('d-none');

				if (total === 0) {
					weightMessage.textContent = window.adminI18n.weight_default;
				} else if (total < 100) {
					weightMessage.textContent = replacePlaceholders(window.adminI18n.weight_current, { total: total }) + ' ' + replacePlaceholders(window.adminI18n.weight_add, { points: 100 - total });
				} else {
					weightMessage.textContent = replacePlaceholders(window.adminI18n.weight_current, { total: total }) + ' ' + replacePlaceholders(window.adminI18n.weight_reduce, { points: total - 100 });
				}
			}

			weightInputs.forEach(function (input) {
				input.addEventListener('input', updateWeightTotal);
			});
			updateWeightTotal();

			const salaryInputs = [
				{ display: document.getElementById('salary_min_display'), hidden: document.getElementById('salary_min') },
				{ display: document.getElementById('salary_max_display'), hidden: document.getElementById('salary_max') },
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

			function syncSalaryHiddenInput(displayInput, hiddenInput) {
				if (!displayInput || !hiddenInput) return;
				const amount = parseSalaryAmount(displayInput.value);
				hiddenInput.value = amount === '' ? '' : String(amount);
				displayInput.value = amount === '' ? '' : formatSalaryAmount(amount);
			}

			salaryInputs.forEach(function (pair) {
				if (!pair.display || !pair.hidden) return;

				pair.display.addEventListener('input', function () {
					const digits = pair.display.value.replace(/\D/g, '');
					pair.hidden.value = digits;
					pair.display.value = digits ? formatSalaryAmount(digits) : '';
				});

				pair.display.addEventListener('blur', function () {
					syncSalaryHiddenInput(pair.display, pair.hidden);
				});
			});

			document.getElementById('form-add-new-record')?.addEventListener('submit', function () {
				salaryInputs.forEach(function (pair) {
					syncSalaryHiddenInput(pair.display, pair.hidden);
				});
			});

			const showSalarySwitch = document.getElementById('show_salary_switch');
			showSalarySwitch?.addEventListener('change', function () {
				const state = this.closest('.switch')?.querySelector('.switch-toggle-slider span');
				if (!state) return;
				state.classList.toggle('switch-on', this.checked);
				state.classList.toggle('switch-off', !this.checked);
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
			@endphp
			const jobImageI18n = @json($jobImageI18n);
			const jobUuid = @json(old('uuid', $job->uuid ?? $uuid ?? ''));
			const maxJobImages = 3;
			const maxImageSize = 5 * 1024 * 1024;
			const allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp'];
			const uploadUrl = @json(route('admin.jobs.upload-image'));
			const deleteUrl = @json(route('admin.jobs.destroy-image'));
			const csrfToken = @json(csrf_token());
			let jobImages = @json($initialJobImages);

			const imageInput = document.getElementById('job-image-input');
			const imageDropzone = document.getElementById('job-image-dropzone');
			const imageGallery = document.getElementById('job-image-gallery');
			const imagePlaceholder = document.getElementById('job-image-placeholder');
			const imageBrowseBtn = document.getElementById('job-image-browse');
			const imageCounter = document.getElementById('job-image-counter');
			const imageError = document.getElementById('job-image-error');
			const hiddenInputs = document.getElementById('job-image-hidden-inputs');

			function showImageError(message) {
				if (!imageError) return;
				imageError.textContent = message;
				imageError.classList.remove('d-none');
				imageDropzone?.classList.add('is-invalid');
			}

			function clearImageError() {
				if (!imageError) return;
				imageError.textContent = '';
				imageError.classList.add('d-none');
				imageDropzone?.classList.remove('is-invalid');
			}

			function updateImageCounter() {
				if (!imageCounter) return;
				imageCounter.textContent = jobImageI18n.counter
					.replace(':count', jobImages.length)
					.replace(':max', maxJobImages);
			}

			function syncHiddenInputs() {
				if (!hiddenInputs) return;
				hiddenInputs.innerHTML = jobImages.map(function(image) {
					return '<input type="hidden" name="images[]" value="' + image.path.replace(/"/g, '&quot;') + '">';
				}).join('');
			}

			function renderJobImages() {
				if (!imageGallery) return;

				imageGallery.innerHTML = jobImages.map(function(image, index) {
					return ''
						+ '<div class="job-image-item" data-index="' + index + '">'
						+ '<img src="' + image.url + '" alt="">'
						+ '<button type="button" class="job-image-item__remove" data-index="' + index + '" aria-label="Remove">'
						+ '<i class="ti ti-x"></i>'
						+ '</button>'
						+ '</div>';
				}).join('');

				if (imagePlaceholder) {
					imagePlaceholder.style.display = jobImages.length ? 'none' : 'block';
				}

				updateImageCounter();
				syncHiddenInputs();
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

			async function uploadJobImage(file) {
				const formData = new FormData();
				formData.append('image', file);
				formData.append('job_uuid', jobUuid);
				formData.append('_token', csrfToken);

				const response = await fetch(uploadUrl, {
					method: 'POST',
					body: formData,
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json',
					},
				});

				if (!response.ok) {
					const payload = await response.json().catch(function() {
						return {};
					});
					const message = payload.errors?.image?.[0]
						|| payload.message
						|| jobImageI18n.uploadFailed;
					throw new Error(message);
				}

				return response.json();
			}

			async function removeJobImage(index) {
				const image = jobImages[index];
				if (!image) return;

				await fetch(deleteUrl, {
					method: 'DELETE',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': csrfToken,
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json',
					},
					body: JSON.stringify({
						job_uuid: jobUuid,
						path: image.path,
					}),
				});

				jobImages.splice(index, 1);
				renderJobImages();
				clearImageError();
			}

			async function handleSelectedFiles(fileList) {
				const files = Array.from(fileList || []);
				if (!files.length) return;

				if (jobImages.length >= maxJobImages) {
					showImageError(jobImageI18n.maxReached);
					return;
				}

				imageDropzone?.classList.add('is-uploading');

				try {
					for (const file of files) {
						if (jobImages.length >= maxJobImages) {
							showImageError(jobImageI18n.maxReached);
							break;
						}

						if (!validateImageFile(file)) {
							continue;
						}

						const uploaded = await uploadJobImage(file);
						jobImages.push({
							path: uploaded.path,
							url: uploaded.url,
						});
					}

					renderJobImages();
				} catch (error) {
					showImageError(error.message || jobImageI18n.uploadFailed);
				} finally {
					imageDropzone?.classList.remove('is-uploading');
					if (imageInput) {
						imageInput.value = '';
					}
				}
			}

			imageBrowseBtn?.addEventListener('click', function(event) {
				event.preventDefault();
				event.stopPropagation();
				imageInput?.click();
			});

			imageDropzone?.addEventListener('click', function(event) {
				if (event.target.closest('.job-image-item__remove, #job-image-browse')) {
					return;
				}
				if (jobImages.length >= maxJobImages) {
					showImageError(jobImageI18n.maxReached);
					return;
				}
				imageInput?.click();
			});

			imageInput?.addEventListener('change', function() {
				handleSelectedFiles(this.files);
			});

			imageGallery?.addEventListener('click', function(event) {
				const button = event.target.closest('.job-image-item__remove');
				if (!button) return;
				event.preventDefault();
				removeJobImage(parseInt(button.dataset.index, 10));
			});

			['dragenter', 'dragover'].forEach(function(eventName) {
				imageDropzone?.addEventListener(eventName, function(event) {
					event.preventDefault();
					event.stopPropagation();
					imageDropzone.classList.add('is-dragover');
				});
			});

			['dragleave', 'drop'].forEach(function(eventName) {
				imageDropzone?.addEventListener(eventName, function(event) {
					event.preventDefault();
					event.stopPropagation();
					imageDropzone.classList.remove('is-dragover');
				});
			});

			imageDropzone?.addEventListener('drop', function(event) {
				handleSelectedFiles(event.dataTransfer?.files);
			});

			renderJobImages();

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
				editor.root.innerHTML = quillEditor.value || '';

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
				editor.root.innerHTML = quillEditor.value || '';

				editor.on('text-change', function() {
					quillEditor.value = editor.root.innerHTML;
				});
				quillEditor.addEventListener('input', function() {
					editor.root.innerHTML = quillEditor.value;
				});
			}
		});
	</script>
@endsection
