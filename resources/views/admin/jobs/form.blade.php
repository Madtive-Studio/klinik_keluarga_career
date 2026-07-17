@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="row">
			<form class="row" id="form-add-new-record" method="POST" action="{{ !empty($job) ? route('admin.jobs.update', $job->id) : route('admin.jobs.store') }}">
				@csrf
				@if (!empty($job))
					@method('PATCH')
				@endif
				<div class="col-md-6 mb-6">
					<div class="card">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">Form {{ isset($job) ? 'Edit' : 'Create' }} Job</h5>
						</div>
						<div class="card-body row">
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">UUID</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="uuid" readonly placeholder="Code" required value="{{ isset($job) ? $job->uuid : $uuid }}" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">Code</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="code" readonly placeholder="Code" required value="{{ isset($job) ? $job->code : $code }}" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">Select Batch</label>
									<div class="input-group input-group-merge">
										<select name="batch_id" id="batch_id" class="form-control" required>
											<option value="">-- Select Batch --</option>
											@foreach ($batches as $batch)
												<option value="{{ $batch->id }}"
													{{ isset($job) && isset($job->batch_id) && $job->batch_id == (isset($batch) ? $batch->id : null) ? 'selected' : '' }}>
													{{ $batch->code }} - {{ $batch->name }} | {{ $batch->start_date }} - {{ $batch->end_date }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">Select Category</label>
									<div class="input-group input-group-merge">
										<select name="category_id" id="category_id" class="form-control" required>
											<option value="">-- Select Category --</option>
											@foreach ($categories as $category)
												<option value="{{ $category->id }}"
													{{ isset($job) && isset($job->category_id) && $job->category_id == (isset($category) ? $category->id : null) ? 'selected' : '' }}>
													{{ $category->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Title</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name" name="title" placeholder="Title" value="{{ isset($job) ? $job->title : '' }}" required />
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Type</label>
								<div class="input-group input-group-merge">
									<select name="type" id="type" class="form-control" required>
										<option value="">-- Select Type --</option>
										<option value="WFH/Remote" {{ isset($job) && $job->type == 'WFH/Remote' ? 'selected' : '' }}>WFH/Remote</option>
										<option value="Partime/Freelancer" {{ isset($job) && $job->type == 'Partime/Freelancer' ? 'selected' : '' }}>Partime/Freelancer</option>
										<option value="Fulltime/Onsite" {{ isset($job) && $job->type == 'Fulltime/Onsite' ? 'selected' : '' }}>Fulltime/Onsite</option>
										<option value="Internship" {{ isset($job) && $job->type == 'Internship' ? 'selected' : '' }}>Internship</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">Quota</label>
									<div class="input-group input-group-merge">
										<input type="number" name="quota" class="form-control" required value="{{ isset($job) ? $job->quota : 0 }}">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">Salary</label>
									<div class="input-group input-group-merge">
										<input type="text" name="salary" class="form-control" placeholder="Rp. 2.000.000 - 5.000.000" required value="{{ isset($job) ? $job->salary : '' }}">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">Show salary for candidate?</label>
									<br>
									<input type="checkbox" name="is_show_salary" {{ isset($job) && $job->is_show_salary ? 'checked' : '' }}>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Experience</label>
								<div class="input-group input-group-merge">
									<input type="text" name="experience" class="form-control" value="{{ isset($job) ? $job->experience : '' }}" required placeholder="1-2 years of experience">
								</div>
							</div>
						</div>
					</div>
					<div class="card mt-6">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">Qualification</h5>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<div id="quill-editor-qualification" class="mb-3" style="height: 150px;"></div>
								<textarea class="mb-3 d-none" name="qualification" id="quill-editor-qualification-area"></textarea>
							</div>
						</div>
					</div>
					<div class="card mt-6">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">Kriteria Penilaian Otomatis</h5>
						</div>
						<div class="card-body row">
							@php
								$criteria = isset($job) ? $job->criteria : null;
								$requiredSkills = old('required_skills', $criteria ? implode(', ', $criteria->required_skills ?? []) : '');
							@endphp
							<div class="col-md-4 mb-3">
								<label class="form-label">Min. Pendidikan</label>
								<select name="min_education" class="form-control">
									<option value="">-- Tidak ada syarat --</option>
									@foreach (['SMA', 'D3', 'S1', 'S2', 'S3'] as $level)
										<option value="{{ $level }}" @selected(old('min_education', $criteria?->min_education) === $level)>{{ $level }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Min. Pengalaman (tahun)</label>
								<input type="number" min="0" max="50" name="min_experience_years" class="form-control" value="{{ old('min_experience_years', $criteria?->min_experience_years ?? 0) }}">
							</div>
							<div class="col-md-12 mb-3">
								<label class="form-label">Skill Wajib (pisahkan dengan koma)</label>
								<textarea name="required_skills" class="form-control" rows="2" placeholder="Contoh: Komunikasi, Microsoft Office, Keperawatan">{{ $requiredSkills }}</textarea>
							</div>
							<div class="col-md-12"><hr><p class="mb-2"><strong>Bobot Penilaian (total harus 100)</strong></p></div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Pendidikan</label>
								<input type="number" min="0" max="100" name="weight_education" class="form-control" value="{{ old('weight_education', $criteria?->weight_education ?? 25) }}">
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Pengalaman</label>
								<input type="number" min="0" max="100" name="weight_experience" class="form-control" value="{{ old('weight_experience', $criteria?->weight_experience ?? 25) }}">
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Skill</label>
								<input type="number" min="0" max="100" name="weight_skills" class="form-control" value="{{ old('weight_skills', $criteria?->weight_skills ?? 30) }}">
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Kelengkapan Profil</label>
								<input type="number" min="0" max="100" name="weight_profile" class="form-control" value="{{ old('weight_profile', $criteria?->weight_profile ?? 10) }}">
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Cover Letter</label>
								<input type="number" min="0" max="100" name="weight_cover_letter" class="form-control" value="{{ old('weight_cover_letter', $criteria?->weight_cover_letter ?? 10) }}">
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Threshold Shortlist</label>
								<input type="number" min="0" max="100" name="threshold_shortlist" class="form-control" value="{{ old('threshold_shortlist', $criteria?->threshold_shortlist ?? 70) }}">
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Threshold Reject</label>
								<input type="number" min="0" max="100" name="threshold_reject" class="form-control" value="{{ old('threshold_reject', $criteria?->threshold_reject ?? 40) }}">
							</div>
							@error('weight_education') <div class="col-12"><small class="text-danger">{{ $message }}</small></div> @enderror
							@error('threshold_shortlist') <div class="col-12"><small class="text-danger">{{ $message }}</small></div> @enderror
						</div>
					</div>
				</div>
				<div class="col-md-6 mb-6">
					<div class="card">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">Description</h5>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<div id="quill-editor-description" class="mb-3" style="height: 175px;"></div>
								<textarea class="mb-3 d-none" name="description" id="quill-editor-description-area"></textarea>
							</div>
							<div class="mb-3">
								<button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
								<a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
