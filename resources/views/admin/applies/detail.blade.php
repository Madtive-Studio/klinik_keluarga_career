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
								Informasi kandidat dan Lowongan pekerjaan
							</h5>
						</div>
						<div class="card-body row">
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Batch</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ $apply->batch->code . ' - ' . $apply->batch->name ?? '-' }}" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Lowongan pekerjaan</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ $apply->job->code . ' - ' . $apply->job->title . ' - ' . $apply->job->type . ' | ' . $apply->job->category->name }}" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Detail pelamar</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ $apply->candidate->name . ' - ' . $apply->candidate->phone . ' - ' . $apply->candidate->email }}" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Waktu/tanggal melamar</label>
									<div class="input-group input-group-merge">
										<input type="text" class="form-control dt-full-name" value="{{ date('d M Y H:i:s', strtotime($apply->created_at)) }}" />
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Cover letter</label>
									<div class="input-group input-group-merge">
										{!! $apply->cover_letter !!}
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label">Deskripsi (alasan melamar pekerjaan ini)</label>
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
								Tahap screening CV dan status
							</h5>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<a href="{{ Illuminate\Support\Facades\Storage::url($apply->cv->file) }}" target="_blank" class="btn btn-info btn-sm">
									<i class="ti ti-download"></i> Lihat & Unduh CV / Resume
								</a>
							</div>
							<div class="mb-3">
								<select name="status" id="status" class="form-control">
									<option value="">Ubah status</option>
									<option value="IN REVIEW" {{ $apply->status == 'IN REVIEW' ? 'selected' : '' }}>IN REVIEW</option>
									<option value="NOT SUITABLE" {{ $apply->status == 'NOT SUITABLE' ? 'selected' : '' }}>NOT SUITABLE</option>
									<option value="SHORTLISTED" {{ $apply->status == 'SHORTLISTED' ? 'selected' : '' }}>SHORTLISTED</option>
									<option value="HIRED" {{ $apply->status == 'HIRED' ? 'selected' : '' }}>HIRED</option>
								</select>
							</div>
							<div class="mb-3">
								<button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Simpan</button>
								<a href="{{ route('admin.applies.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
