@extends('admin.layouts.main')
@section('css')
	<link href="https://cdn.jsdelivr.net/npm/slim-select@2.8.2/dist/slimselect.css" rel="stylesheet" />
	<style>
		.ss-main {
			width: 100%;
			border-radius: 0.375rem;
		}
	</style>
@endsection
@section('content')
	<div class="container-fluid flex-grow-1 container-p-y">
		<div class="row">
			<form class="row" id="form-add-new-record" method="POST" action="{{ !empty($scheduleInterview) ? route('admin.schedule-interviews.update', $scheduleInterview->id) : route('admin.schedule-interviews.store') }}">
				@csrf
				@if (!empty($scheduleInterview))
					@method('PATCH')
				@endif
				<div class="col-md-6 mb-6">
					<div class="card">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">{{ isset($scheduleInterview) ? __('admin.schedule_interviews.form_edit') : __('admin.schedule_interviews.form_create') }}</h5>
						</div>
						<div class="card-body row">
							<input type="hidden" name="uuid" value="{{ old('uuid', isset($scheduleInterview) ? $scheduleInterview->uuid : ($uuid ?? '')) }}" />
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.code') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="code" readonly placeholder="{{ __('admin.schedule_interviews.code') }}" required value="{{ old('code', isset($scheduleInterview) ? $scheduleInterview->code : ($code ?? '')) }}" />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.select_apply') }} (Shortlisted)</label>
									<select name="apply_id" id="apply_id" required>
										<option data-placeholder="true" value="">{{ __('admin.schedule_interviews.select_apply') }}</option>
										@foreach ($applies as $apply)
											<option value="{{ $apply->id }}"
												{{ (old('apply_id', isset($scheduleInterview) ? $scheduleInterview->apply_id : '') == $apply->id) ? 'selected' : '' }}>
												{{ $apply->candidate->name ?? '-' }} ({{ $apply->candidate->email ?? '-' }}) — {{ $apply->job->title ?? '-' }} [{{ $apply->batch->code ?? '-' }} - {{ $apply->batch->name ?? '-' }}]
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.schedule_interviews.title_col') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name" name="title" placeholder="{{ __('admin.schedule_interviews.title_col') }}" value="{{ isset($scheduleInterview) ? $scheduleInterview->title : '' }}" required />
								</div>
							</div>
							<div class="col-md-4" style="align-self: center;">
								<div class="mb-3">
									<label for="is_online">
										<input type="checkbox" name="is_online" id="is_online" {{ isset($scheduleInterview) && $scheduleInterview->is_online ? 'checked' : '' }}> {{ __('admin.schedule_interviews.interview_online') }}
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.start_datetime') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control flatpickr-datetime" id="start_datetime" name="start_datetime" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('start_datetime', isset($scheduleInterview) ? formatFlatpickrDatetime($scheduleInterview->start_datetime) : '') }}" data-min-date="{{ isset($scheduleInterview) ? '' : 'today' }}" />
									</div>
									<small class="text-danger error-feedback" id="error-start-datetime">@error('start_datetime') {{ $message }} @enderror</small>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.end_datetime') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control flatpickr-datetime" id="end_datetime" name="end_datetime" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('end_datetime', isset($scheduleInterview) ? formatFlatpickrDatetime($scheduleInterview->end_datetime) : '') }}" data-min-date="{{ isset($scheduleInterview) ? '' : 'today' }}" />
									</div>
									<small class="text-danger error-feedback" id="error-end-datetime">@error('end_datetime') {{ $message }} @enderror</small>
								</div>
							</div>
							<div class="col-md-12" id="form_link">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.link') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" name="link" class="form-control" required value="{{ isset($scheduleInterview) ? $scheduleInterview->link : '' }}">
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.schedule_interviews.description') }}</label>
								<div class="input-group input-group-merge">
									<textarea name="description" id="" class="form-control" required>{{ isset($scheduleInterview) ? $scheduleInterview->description : '' }}</textarea>
								</div>
							</div>
							<div class="mb-3">
								<button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">{{ __('admin.form.submit') }}</button>
								<a href="{{ route('admin.schedule-interviews.index') }}" class="btn btn-outline-secondary">{{ __('admin.form.cancel') }}</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
@section('js')
	<script src="https://cdn.jsdelivr.net/npm/slim-select@2.8.2/dist/slimselect.min.js"></script>
	<script>
		$(function() {
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
					modules: { toolbar: toolbarOptions }
				});
				qualEditor.root.innerHTML = `{!! !empty($scheduleInterview) ? $scheduleInterview->qualification : '' !!}`;

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
					modules: { toolbar: toolbarOptions }
				});
				descEditor.root.innerHTML = `{!! !empty($scheduleInterview) ? $scheduleInterview->description : '' !!}`;

				descEditor.on('text-change', function() {
					$descriptionArea.val(descEditor.root.innerHTML);
				});
				$descriptionArea.on('input', function() {
					descEditor.root.innerHTML = $descriptionArea.val();
				});
			}

			@if (!empty($scheduleInterview))
				@if ($scheduleInterview->is_online)
					$('#form_link').show();
				@else
					$('#form_link').hide();
				@endif
			@else
				$('#form_link').hide();
			@endif

			$(document).on('change', '#is_online', function() {
				if ($(this).is(':checked')) {
					$('#form_link').show();
				} else {
					$('#form_link').hide();
				}
			});

			if ($('#apply_id').length && window.SlimSelect) {
				new SlimSelect({
					select: '#apply_id',
					settings: {
						placeholderText: 'Pilih / Cari Kandidat Shortlisted...',
						searchPlaceholder: 'Ketik nama kandidat, email, atau posisi...',
						searchText: 'Data kandidat tidak ditemukan',
						searchingText: 'Mencari...',
					}
				});
			}

			const $form = $('#form-add-new-record');
			const $startInput = $('#start_datetime');
			const $endInput = $('#end_datetime');
			const $startErr = $('#error-start-datetime');
			const $endErr = $('#error-end-datetime');

			if (!$form.length || !$startInput.length || !$endInput.length) return;

			function parseDatetimeString(str) {
				if (!str) return null;
				const parts = str.trim().split(' ');
				if (parts.length !== 2) return null;
				const dateParts = parts[0].split('-');
				const timeParts = parts[1].split(':');
				if (dateParts.length !== 3 || timeParts.length < 2) return null;
				const day = parseInt(dateParts[0], 10);
				const month = parseInt(dateParts[1], 10) - 1;
				const year = parseInt(dateParts[2], 10);
				const hour = parseInt(timeParts[0], 10);
				const minute = parseInt(timeParts[1], 10);
				const second = timeParts[2] ? parseInt(timeParts[2], 10) : 0;
				const d = new Date(year, month, day, hour, minute, second);
				return isNaN(d.getTime()) ? null : d;
			}

			function getSelectedDate($input) {
				const inputEl = $input[0];
				if (!inputEl) return null;
				if (inputEl._flatpickr && inputEl._flatpickr.selectedDates.length > 0) {
					return inputEl._flatpickr.selectedDates[0];
				}
				const val = $input.val();
				if (val) {
					if (inputEl._flatpickr) {
						const parsed = inputEl._flatpickr.parseDate(val, 'd-m-Y H:i:S');
						if (parsed) return parsed;
					}
					return parseDatetimeString(val);
				}
				return null;
			}

			function validateInterviewDates() {
				let isValid = true;
				$startErr.text('');
				$endErr.text('');
				$startInput.removeClass('is-invalid');
				$endInput.removeClass('is-invalid');

				const startDate = getSelectedDate($startInput);
				const endDate = getSelectedDate($endInput);
				const minAllowedTime = new Date(Date.now() - 60000);

				@if (empty($scheduleInterview))
				if (startDate && startDate.getTime() < minAllowedTime.getTime()) {
					isValid = false;
					$startInput.addClass('is-invalid');
					$startErr.text(@json(__('admin.schedule_interviews.validation_start_past')));
				}
				@else
				const originalStartVal = "{{ formatFlatpickrDatetime($scheduleInterview->start_datetime) }}";
				if ($startInput.val().trim() !== originalStartVal && startDate && startDate.getTime() < minAllowedTime.getTime()) {
					isValid = false;
					$startInput.addClass('is-invalid');
					$startErr.text(@json(__('admin.schedule_interviews.validation_start_past')));
				}
				@endif

				if (startDate && endDate && endDate.getTime() <= startDate.getTime()) {
					isValid = false;
					$endInput.addClass('is-invalid');
					$endErr.text(@json(__('admin.schedule_interviews.validation_end_before_start')));
				}

				return isValid;
			}

			$startInput.add($endInput).on('change input', validateInterviewDates);

			$form.on('submit', function(e) {
				if (!validateInterviewDates()) {
					e.preventDefault();
					const $firstInvalid = $form.find('.is-invalid').first();
					if ($firstInvalid.length) {
						$firstInvalid.trigger('focus');
					}
				}
			});
		});
	</script>
@endsection
