@extends('admin.layouts.main')
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
							<h5 class="mb-0">Form {{ isset($scheduleInterview) ? 'Edit' : 'Create' }} Schedule Interview</h5>
						</div>
						<div class="card-body row">
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">UUID</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="uuid" readonly placeholder="Code" required value="{{ isset($scheduleInterview) ? $scheduleInterview->uuid : $uuid }}" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">Code</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="code" readonly placeholder="Code" required value="{{ isset($scheduleInterview) ? $scheduleInterview->code : $code }}" />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Select Apply Data</label>
									<div class="input-group input-group-merge">
										<select name="apply_id" id="apply_id" class="form-control" required>
											<option value="">-- Select Apply Data --</option>
											@foreach ($applies as $apply)
												<option value="{{ $apply->id }}"
													{{ isset($scheduleInterview) && isset($scheduleInterview->apply_id) && $scheduleInterview->apply_id == (isset($apply) ? $apply->id : null) ? 'selected' : '' }}>
													{{ $apply->batch->code }} - {{ $apply->batch->name }} - {{ $apply->candidate->name }} - {{ $apply->job->title }} - {{ $apply->status }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Title</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name" name="title" placeholder="Title" value="{{ isset($scheduleInterview) ? $scheduleInterview->title : '' }}" required />
								</div>
							</div>
							<div class="col-md-4" style="align-self: center;">
								<div class="mb-3">
									<label for="is_online">
										<input type="checkbox" name="is_online" id="is_online" {{ isset($scheduleInterview) && $scheduleInterview->is_online ? 'checked' : '' }}> Interview Online?
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">Start Datetime</label>
									<div class="input-group input-group-merge">
										<input type="datetime-local" class="form-control dt-full-name" name="start_datetime" placeholder="..." value="{{ isset($scheduleInterview) ? $scheduleInterview->start_datetime : '' }}" required />
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">End Datetime</label>
									<div class="input-group input-group-merge">
										<input type="datetime-local" class="form-control dt-full-name" name="end_datetime" placeholder="..." value="{{ isset($scheduleInterview) ? $scheduleInterview->end_datetime : '' }}" required />
									</div>
								</div>
							</div>
							<div class="col-md-12" id="form_link">
								<div class="mb-3">
									<label class="form-label">Link Zoom/Gmeet</label>
									<div class="input-group input-group-merge">
										<input type="text" name="link" class="form-control" required value="{{ isset($scheduleInterview) ? $scheduleInterview->link : '' }}">
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Description</label>
								<div class="input-group input-group-merge">
									<textarea name="description" id="" class="form-control" required>{{ isset($scheduleInterview) ? $scheduleInterview->description : '' }}</textarea>
								</div>
							</div>
							<div class="mb-3">
								<button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
								<a href="{{ route('admin.schedule-interviews.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
@endsection
