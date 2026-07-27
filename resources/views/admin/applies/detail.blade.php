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
											<a href="{{ Illuminate\Support\Facades\Storage::url($applyDoc->document->file) }}" target="_blank"
												class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
												<div>
													<i class="ti ti-file-text me-1"></i>
													<span>{{ $applyDoc->document->name }}</span>
												</div>
												<div>
													<span class="badge {{ $applyDoc->type?->getBadgeClass() ?? 'badge-secondary' }} me-2">{{ $applyDoc->type?->getLabel() ?? $applyDoc->type }}</span>
													<i class="ti ti-download text-muted"></i>
												</div>
											</a>
										@endforeach
									</div>
								@elseif ($apply->document)
									<a href="{{ Illuminate\Support\Facades\Storage::url($apply->document->file) }}" target="_blank" class="btn btn-info btn-sm">
										<i class="ti ti-download"></i> {{ __('admin.applies.view_cv') }}
									</a>
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
	</script>
@endsection
