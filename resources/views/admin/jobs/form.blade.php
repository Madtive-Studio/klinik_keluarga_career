@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<div>
				<h4 class="mb-1">{{ isset($job) ? 'Edit Job' : 'Create Job' }}</h4>
				<p class="text-muted mb-0">Kelola informasi lowongan, deskripsi, dan kriteria penilaian.</p>
			</div>
			<a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i> Kembali ke Job List
			</a>
		</div>

		@if ($errors->any())
			<div class="alert alert-danger" role="alert">
				<strong>Validasi gagal.</strong> Periksa field yang ditandai di bawah ini.
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
				<li class="nav-item"><button type="button" class="nav-link active" id="tab-btn-basic" data-bs-toggle="tab" data-bs-target="#tab-basic">Informasi Utama</button></li>
				<li class="nav-item"><button type="button" class="nav-link" id="tab-btn-content" data-bs-toggle="tab" data-bs-target="#tab-content">Kualifikasi & Deskripsi</button></li>
				<li class="nav-item"><button type="button" class="nav-link" id="tab-btn-scoring" data-bs-toggle="tab" data-bs-target="#tab-scoring">Kriteria Penilaian</button></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="tab-basic">
					<div class="card mb-4">
						<div class="card-body row">
							<input type="hidden" name="uuid" value="{{ old('uuid', $job->uuid ?? $uuid ?? '') }}">
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">Code</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" name="code" readonly placeholder="Code" required value="{{ old('code', $job->code ?? $code ?? '') }}" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label class="form-label">Select Batch</label>
									<div class="input-group input-group-merge">
										<select name="batch_id" id="batch_id" class="form-control @error('batch_id') is-invalid @enderror" required>
											<option value="">-- Select Batch --</option>
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
									<label class="form-label">Select Category</label>
									<div class="input-group input-group-merge">
										<select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
											<option value="">-- Select Category --</option>
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
								<label class="form-label">Title</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name @error('title') is-invalid @enderror" name="title" placeholder="Title" value="{{ old('title', $job->title ?? '') }}" required />
								</div>
								@error('title') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">Type</label>
								<div class="input-group input-group-merge">
									<select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
										<option value="">-- Select Type --</option>
										@foreach (\App\Enums\JobType::cases() as $jobType)
											<option value="{{ $jobType->value }}" @selected(old('type', $job->type ?? '') === $jobType->value)>{{ $jobType->getLabel() }}</option>
										@endforeach
									</select>
								</div>
								@error('type') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">Quota</label>
									<div class="input-group input-group-merge">
										<input type="number" name="quota" class="form-control @error('quota') is-invalid @enderror" required value="{{ old('quota', $job->quota ?? 0) }}">
									</div>
									@error('quota') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
							<div class="col-md-4">
								<div class="mb-3">
									<label class="form-label">Salary</label>
									<div class="input-group input-group-merge">
										<input type="text" name="salary" class="form-control @error('salary') is-invalid @enderror" placeholder="Rp. 2.000.000 - 5.000.000" required value="{{ old('salary', $job->salary ?? '') }}">
									</div>
									@error('salary') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
							@php
								$showSalary = old('is_show_salary', isset($job) ? ($job->is_show_salary ? '1' : '0') : '1');
							@endphp
							<div class="col-md-12 mb-3">
								<label class="form-label d-block">Show salary for candidate?</label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="is_show_salary" id="show_salary_on" value="1" @checked((string) $showSalary === '1')>
									<label class="form-check-label" for="show_salary_on">On</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="is_show_salary" id="show_salary_off" value="0" @checked((string) $showSalary === '0')>
									<label class="form-check-label" for="show_salary_off">Off</label>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Pengalaman</label>
								<div class="input-group input-group-merge">
									<input type="text" name="experience" class="form-control @error('experience') is-invalid @enderror" value="{{ old('experience', $job->experience ?? '') }}" required placeholder="Contoh: 1-2 tahun, Fresh Graduate">
								</div>
								<small class="text-muted">Digunakan untuk tampilan lowongan dan penilaian otomatis pengalaman kandidat.</small>
								@error('experience') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="tab-content">
					<div class="row">
						<div class="col-md-6 mb-4">
							<div class="card h-100">
								<div class="card-header"><h5 class="mb-0">Qualification</h5></div>
								<div class="card-body">
									<div id="quill-editor-qualification" class="mb-3 @error('qualification') border border-danger rounded @enderror" style="height: 220px;"></div>
									<textarea class="d-none" name="qualification" id="quill-editor-qualification-area">{{ old('qualification', $job->qualification ?? '') }}</textarea>
									@error('qualification') <small class="text-danger">{{ $message }}</small> @enderror
								</div>
							</div>
						</div>
						<div class="col-md-6 mb-4">
							<div class="card h-100">
								<div class="card-header"><h5 class="mb-0">Description</h5></div>
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
						<div class="card-header"><h5 class="mb-0">Kriteria Penilaian Otomatis</h5></div>
						<div class="card-body row">
							@php
								$criteria = isset($job) ? $job->criteria : null;
								$requiredSkills = old('required_skills', $criteria ? implode(', ', $criteria->required_skills ?? []) : '');
							@endphp
							<div class="col-md-4 mb-3">
								<label class="form-label">Min. Pendidikan</label>
								<select name="min_education" class="form-control @error('min_education') is-invalid @enderror">
									<option value="">-- Tidak ada syarat --</option>
									@foreach (\App\Enums\EducationLevel::cases() as $level)
										<option value="{{ $level->value }}" @selected(old('min_education', $criteria?->min_education) === $level->value)>{{ $level->label() }}</option>
									@endforeach
								</select>
								@error('min_education') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-12 mb-3">
								<label class="form-label">Skill Wajib (pisahkan dengan koma)</label>
								<textarea name="required_skills" class="form-control @error('required_skills') is-invalid @enderror" rows="2" placeholder="Contoh: Komunikasi, Microsoft Office, Keperawatan">{{ $requiredSkills }}</textarea>
								@error('required_skills') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-12 mb-3">
								<small class="text-muted">Minimum pengalaman untuk penilaian otomatis diambil dari field <strong>Pengalaman</strong> pada tab Informasi Utama.</small>
							</div>
							<div class="col-md-12"><hr><p class="mb-2"><strong>Bobot Penilaian</strong></p></div>
							<div class="col-12 mb-3">
								<div id="weight-total-alert" class="alert alert-warning d-none mb-0" role="alert">
									<strong>Perlu penyesuaian bobot.</strong>
									<span id="weight-total-message">Total bobot saat ini 0. Sesuaikan agar berjumlah 100.</span>
								</div>
								<div id="weight-total-success" class="alert alert-success d-none mb-0" role="alert">
									Total bobot sudah 100. Siap disimpan.
								</div>
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Pendidikan</label>
								<input type="number" min="0" max="100" name="weight_education" class="form-control weight-input @error('weight_education') is-invalid @enderror" value="{{ old('weight_education', $criteria?->weight_education ?? 25) }}">
								@error('weight_education') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Pengalaman</label>
								<input type="number" min="0" max="100" name="weight_experience" class="form-control weight-input @error('weight_experience') is-invalid @enderror" value="{{ old('weight_experience', $criteria?->weight_experience ?? 25) }}">
								@error('weight_experience') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Skill</label>
								<input type="number" min="0" max="100" name="weight_skills" class="form-control weight-input @error('weight_skills') is-invalid @enderror" value="{{ old('weight_skills', $criteria?->weight_skills ?? 30) }}">
								@error('weight_skills') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Kelengkapan Profil</label>
								<input type="number" min="0" max="100" name="weight_profile" class="form-control weight-input @error('weight_profile') is-invalid @enderror" value="{{ old('weight_profile', $criteria?->weight_profile ?? 10) }}">
								@error('weight_profile') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Bobot Surat Lamaran</label>
								<input type="number" min="0" max="100" name="weight_cover_letter" class="form-control weight-input @error('weight_cover_letter') is-invalid @enderror" value="{{ old('weight_cover_letter', $criteria?->weight_cover_letter ?? 10) }}">
								@error('weight_cover_letter') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-12"><hr><p class="mb-2"><strong>Batas Skor Rekomendasi (skala 0–100)</strong></p></div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Batas Skor Direkomendasi</label>
								<input type="number" min="0" max="100" name="threshold_shortlist" class="form-control @error('threshold_shortlist') is-invalid @enderror" value="{{ old('threshold_shortlist', $criteria?->threshold_shortlist ?? 70) }}">
								<small class="text-muted">Skor ≥ nilai ini → kandidat direkomendasikan</small>
								@error('threshold_shortlist') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Batas Skor Review</label>
								<input type="number" min="0" max="100" name="threshold_reject" class="form-control @error('threshold_reject') is-invalid @enderror" value="{{ old('threshold_reject', $criteria?->threshold_reject ?? 40) }}">
								<small class="text-muted">Skor ≥ nilai ini → perlu review; di bawahnya → kurang sesuai</small>
								@error('threshold_reject') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-end gap-2 mt-2">
				<a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">Cancel</a>
				<button type="submit" class="btn btn-primary">Simpan Job</button>
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

				if (total < 100) {
					weightMessage.textContent = 'Total bobot saat ini ' + total + '. Tambahkan ' + (100 - total) + ' poin agar berjumlah 100.';
				} else {
					weightMessage.textContent = 'Total bobot saat ini ' + total + '. Kurangi ' + (total - 100) + ' poin agar berjumlah 100.';
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
