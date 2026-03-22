@extends('candidate.layouts.main', ['navbarType' => 'default'])
@section('content')
	<section class="bg-half page-next-level">
		<div class="bg-overlay"></div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="text-center text-white">
						<h4 class="text-uppercase title mb-4">{{ $job->code }} - {{ $job->title }}</h4>
						<ul class="page-next d-inline-block mb-0">
							<li><a href="#" class="text-uppercase font-weight-bold">Beranda</a></li>
							<li>
								<span class="text-uppercase text-white">Lowongan Pekerjaan</span>
							</li>
							<li>
								<span class="text-uppercase text-white font-weight-bold">{{ $job->code }} - {{ $job->title }}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-7">
					@if ($message = Session::get('error'))
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-danger" role="alert">
									<p class="mb-0">{{ $message }}</p>
								</div>
							</div>
						</div>
					@endif
					<div class="job-detail border rounded p-4">
						<div class="job-detail-content">
							<img src="images/featured-job/img-4.png" alt="" class="img-fluid float-left mr-md-3 mr-2 mx-auto d-block">
							<div class="job-detail-com-desc overflow-hidden d-block">
								<h4 class="mb-2"><a href="#" class="text-dark">Lamaran | {{ $job->code }} - {{ $job->title }}</a></h4>
								<p class="text-muted mb-0"><i class="mdi mdi-link-variant mr-2"></i>{{ $job->category->name }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-laptop mr-2"></i>{{ $job->type }} | {{ $job->experience }}</p>
								<p class="text-muted mb-0"><i class="mdi mdi-account mr-2"></i>{{ $formattedAppliesTotal }} orang melamar pekerjaan ini</p>
							</div>
						</div>
					</div>
					<form action="{{ route('candidate.jobs.applications.process', $job->uuid) }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row mt-4">
							<div class="col-lg-12">
								<div class="job-detail border rounded p-4">
									<div class="job-detail-content">
										<div class="form-group">
											<label for="">CV / Resume : </label>
											<div class="row">
												<div class="col-md-2">
													<label for="upload_cv">
														<input type="radio" name="type_of_cv" class="type_of_cv" id="upload_cv" value="upload"
															{{ old('type_of_cv') === 'upload' ? 'checked' : 'checked' }}> Upload
													</label>
												</div>
												<div class="col-md-2">
													<label for="select_cv">
														<input type="radio" name="type_of_cv" class="type_of_cv" id="select_cv" value="select"
															{{ old('type_of_cv') === 'select' ? 'checked' : '' }}> Pilih
													</label>
												</div>
											</div>
											@error('type_of_cv')
												<span class="text-danger font-weight-bold">{{ $message }}</span>
											@enderror
										</div>
										<div class="form-group" id="form_new_cv">
											<input type="file" name="new_cv" class="form-control" id="new_cv">
											@error('new_cv')
												<span class="text-danger font-weight-bold">{{ $message }}</span>
											@enderror
										</div>
										<div class="form-group" id="form_cv_id">
											<select name="cv_id" id="cv_id" class="form-control">
												<option value=""></option>
												@foreach ($candidateCVs as $key => $value)
													<option value="{{ $value->id }}">{{ $value->name }}</option>
												@endforeach
											</select>
											@error('cv_id')
												<span class="text-danger font-weight-bold">{{ $message }}</span>
											@enderror
										</div>
										<div class="form-group">
											<label for="">Cover Letter : </label>
											<div id="quill-editor-cover_letter" class="mb-3" style="height: 100px;"></div>
											<textarea class="mb-3 d-none" name="cover_letter" id="quill-editor-cover_letter-area"></textarea>
											@error('cover_letter')
												<span class="text-danger font-weight-bold">{{ $message }}</span>
											@enderror
										</div>
										<div class="form-group">
											<label for="">Deskripsikan kenapa kamu melamar pekerjaan ini : </label>
											<div id="quill-editor-description" class="mb-3" style="height: 150px;"></div>
											<textarea class="mb-3 d-none" name="description" id="quill-editor-description-area"></textarea>
											@error('description')
												<span class="text-danger font-weight-bold">{{ $message }}</span>
											@enderror
										</div>
									</div>
									<button type="submit" class="btn btn-primary btn-block">
										Lamar Sekarang
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-lg-4 col-md-5 mt-4 mt-sm-0">
					<div class="job-detail border rounded p-4">
						<h5 class="text-muted text-center pb-2"><i class="mdi mdi-info mr-2"></i>Informasi</h5>
						<div class="job-detail-location pt-4 border-top">
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-clock-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ $activeBatch->name }} | {{ date('d M Y', strtotime($activeBatch->start_date)) }} - {{ date('d M Y', strtotime($activeBatch->end_date)) }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-laptop text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ $job->type }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-information-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ $job->experience }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-account text-muted"></i>
								</div>
								<p class="text-muted mb-2">{{ $job->quota }} orang (Kuota)</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-currency-usd text-muted"></i>
								</div>
								<p class="text-muted mb-2">: {{ $job->is_show_salary ? $job->salary : 'Tidak disebutkan' }}</p>
							</div>
							<div class="job-details-desc-item">
								<div class="float-left mr-2">
									<i class="mdi mdi-clock-outline text-muted"></i>
								</div>
								<p class="text-muted mb-2">: Senin - Jum'at</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('js')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (document.getElementById('quill-editor-description-area')) {
				const toolbarOptions = [
					['bold', 'italic', 'underline', 'strike'],
					['blockquote', 'code-block'],
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
				editor.root.innerHTML = ``;

				editor.on('text-change', function() {
					quillEditor.value = editor.root.innerHTML;
				});
				quillEditor.addEventListener('input', function() {
					editor.root.innerHTML = quillEditor.value;
				});
			}
		});

		document.addEventListener('DOMContentLoaded', function() {
			if (document.getElementById('quill-editor-cover_letter-area')) {
				const toolbarOptions = [
					['bold', 'italic', 'underline', 'strike'],
					['blockquote', 'code-block'],
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
				var editor = new Quill('#quill-editor-cover_letter', {
					theme: 'snow',
					modules: {
						toolbar: toolbarOptions
					}
				});
				var quillEditor = document.getElementById('quill-editor-cover_letter-area');
				editor.root.innerHTML = ``;

				editor.on('text-change', function() {
					quillEditor.value = editor.root.innerHTML;
				});
				quillEditor.addEventListener('input', function() {
					editor.root.innerHTML = quillEditor.value;
				});
			}
		});

		$(function() {
			@if (old('type_of_cv') === 'upload')
				$('#form_new_cv').show()
				$('#form_cv_id').hide()
			@elseif (old('type_of_cv') === 'select')
				$('#form_new_cv').hide()
				$('#form_cv_id').show()
			@else
				$('#form_new_cv').show()
				$('#form_cv_id').hide()
			@endif

			$(document).on('change', '.type_of_cv', function() {
				let checkedValue = $(this).val()
				if (checkedValue === 'upload') {
					$('#form_new_cv').show()
					$('#form_cv_id').hide()
				} else if (checkedValue === 'select') {
					$('#form_new_cv').hide()
					$('#form_cv_id').show()
				}
			})
		})
	</script>
@endsection
