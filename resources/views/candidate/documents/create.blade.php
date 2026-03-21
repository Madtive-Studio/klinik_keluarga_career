@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', 'Upload Dokumen')
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.documents.tab_menu')
				<div class="col-lg-8 col-md-5 mt-4 mt-sm-0">
					@if ($message = Session::get('error'))
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-danger" role="alert">
									<p class="mb-0">{{ $message }}</p>
								</div>
							</div>
						</div>
					@endif
					@if ($message = Session::get('success'))
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-success" role="alert">
									<p class="mb-0">{{ $message }}</p>
								</div>
							</div>
						</div>
					@endif
					<form class="form-documents" method="POST" action="{{ route('candidate.my.documents.store') }}" enctype="multipart/form-data">
						@csrf
						<div class="job-list-box border rounded">
							<div class="p-3">
								<div class="form-group mt-3" id="form-section">
									<label for="file">Pilih File</label>
									<div class="custom-file">
										<input type="file" name="file" class="custom-file-input" id="customFile">
										<label class="custom-file-label" for="customFile">Choose file</label>
									</div>
									@error('file')
										<span class="text-danger font-weight-bold">{{ $message }}</span>
									@enderror
								</div>

								<div class="form-group mt-3" id="form-section">
									<label for="type">Tipe File</label>
									<select class="form-control" name="type" id="type">
										@foreach ($types as $value => $label)
											<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
									@error('file')
										<span class="text-danger font-weight-bold">{{ $message }}</span>
									@enderror
								</div>
								<div class="form-group mt-3">
									<button type="submit" class="btn btn-success btn-sm">Simpan</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('js')
	<script>
		$(function() {
			$('.custom-file-input').on('change', function() {
				var fileName = $(this).val().split('\\').pop();
				$(this).next('.custom-file-label').html(fileName);
			});

			$('.custom-file-input').on('change', function() {
				var fileInput = this;
				var maxSize = 8 * 1024 * 1024; // 8MB
				
				if (fileInput.files && fileInput.files[0]) {
					if (fileInput.files[0].size > maxSize) {
						$('.btn-submit').prop('disabled', true);
						$('.alert-danger').remove();
						
						var alertHtml = `
							<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-danger" role="alert">
										<p class="mb-0">File terlalu besar! Maksimal 8MB</p>
									</div>
								</div>
							</div>
						`;
						
						$('.form-documents').before(alertHtml);
						
						$('html, body').animate({
							scrollTop: $('.alert-danger').offset().top - 100
						}, 500);
						
						$(fileInput).val('');
						$(fileInput).next('.custom-file-label').html('Choose file');

						setTimeout(function() {
							$('.alert-danger').fadeOut(300, function() {
								$(this).remove();
							});
							$('.btn-submit').prop('disabled', false);
						}, 5000);
					}
				}
			});
		});
	</script>
@endsection
