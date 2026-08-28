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
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.uuid') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="uuid" readonly placeholder="{{ __('admin.schedule_interviews.code') }}" required value="{{ isset($scheduleInterview) ? $scheduleInterview->uuid : $uuid }}" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.code') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="code" readonly placeholder="{{ __('admin.schedule_interviews.code') }}" required value="{{ isset($scheduleInterview) ? $scheduleInterview->code : $code }}" />
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
										<input type="text" class="form-control flatpickr-datetime" name="start_datetime" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('start_datetime', isset($scheduleInterview) ? formatFlatpickrDatetime($scheduleInterview->start_datetime) : '') }}" />
									</div>
									@error('start_datetime') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">{{ __('admin.schedule_interviews.end_datetime') }}</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control flatpickr-datetime" name="end_datetime" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('end_datetime', isset($scheduleInterview) ? formatFlatpickrDatetime($scheduleInterview->end_datetime) : '') }}" />
									</div>
									@error('end_datetime') <small class="text-danger">{{ $message }}</small> @enderror
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
				editor.root.innerHTML = `{!! !empty($scheduleInterview) ? $scheduleInterview->qualification : '' !!}`;

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
				editor.root.innerHTML = `{!! !empty($scheduleInterview) ? $scheduleInterview->description : '' !!}`;

				editor.on('text-change', function() {
					quillEditor.value = editor.root.innerHTML;
				});
				quillEditor.addEventListener('input', function() {
					editor.root.innerHTML = quillEditor.value;
				});
			}
		});

		$(function() {
			@if (!empty($scheduleInterview))
				@if ($scheduleInterview->is_online)
					$('#form_link').show()
				@else
					$('#form_link').hide()
				@endif
			@else
				$('#form_link').hide()
			@endif

			$(document).on('change', '#is_online', function() {
				let value = $(this).is(':checked')
				if (value) {
					$('#form_link').show()
				} else {
					$('#form_link').hide()
				}
			})
		})
	</script>
	<script src="https://cdn.jsdelivr.net/npm/slim-select@2.8.2/dist/slimselect.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (document.getElementById('apply_id')) {
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
		});
	</script>
@endsection
