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
							<div class="col-md-4">
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
									<label class="form-label">{{ __('admin.jobs.salary') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" name="salary" class="form-control @error('salary') is-invalid @enderror" placeholder="{{ __('admin.jobs.salary_placeholder') }}" required value="{{ old('salary', $job->salary ?? '') }}">
									</div>
									@error('salary') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
							@php
								$showSalary = old('is_show_salary', isset($job) ? ($job->is_show_salary ? '1' : '0') : '1');
							@endphp
							<div class="col-md-12 mb-3">
								<label class="form-label d-block">{{ __('admin.jobs.show_salary_question') }}</label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="is_show_salary" id="show_salary_on" value="1" @checked((string) $showSalary === '1')>
									<label class="form-check-label" for="show_salary_on">{{ __('admin.jobs.on') }}</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="is_show_salary" id="show_salary_off" value="0" @checked((string) $showSalary === '0')>
									<label class="form-check-label" for="show_salary_off">{{ __('admin.jobs.off') }}</label>
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
								$requiredSkills = old('required_skills', $criteria ? implode(', ', $criteria->required_skills ?? []) : '');
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
								<label class="form-label">{{ __('admin.jobs.required_skills') }}</label>
								<textarea name="required_skills" class="form-control @error('required_skills') is-invalid @enderror" rows="2" placeholder="{{ __('admin.jobs.required_skills_placeholder') }}">{{ $requiredSkills }}</textarea>
								@error('required_skills') <small class="text-danger">{{ $message }}</small> @enderror
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
							<div class="col-md-4 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_education') }}</label>
								<input type="number" min="0" max="100" name="weight_education" class="form-control weight-input @error('weight_education') is-invalid @enderror" value="{{ old('weight_education', $criteria?->weight_education ?? 25) }}">
								@error('weight_education') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_experience') }}</label>
								<input type="number" min="0" max="100" name="weight_experience" class="form-control weight-input @error('weight_experience') is-invalid @enderror" value="{{ old('weight_experience', $criteria?->weight_experience ?? 25) }}">
								@error('weight_experience') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_skills') }}</label>
								<input type="number" min="0" max="100" name="weight_skills" class="form-control weight-input @error('weight_skills') is-invalid @enderror" value="{{ old('weight_skills', $criteria?->weight_skills ?? 30) }}">
								@error('weight_skills') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_profile') }}</label>
								<input type="number" min="0" max="100" name="weight_profile" class="form-control weight-input @error('weight_profile') is-invalid @enderror" value="{{ old('weight_profile', $criteria?->weight_profile ?? 10) }}">
								@error('weight_profile') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">{{ __('admin.jobs.weight_cover_letter') }}</label>
								<input type="number" min="0" max="100" name="weight_cover_letter" class="form-control weight-input @error('weight_cover_letter') is-invalid @enderror" value="{{ old('weight_cover_letter', $criteria?->weight_cover_letter ?? 10) }}">
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
@section('js')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const tabFieldMap = {
				'tab-basic': ['batch_id', 'category_id', 'title', 'type', 'quota', 'salary', 'experience'],
				'tab-content': ['qualification', 'description'],
				'tab-scoring': [
					'min_education', 'required_skills',
					'weight_education', 'weight_experience', 'weight_skills',
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
