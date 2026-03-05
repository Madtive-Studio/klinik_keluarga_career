@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', 'Upload Dokumen')
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row">
				@include('candidate.cv-saya.tab-menu')
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
					<form class="form_add_new_cv" method="POST" action="{{ route('candidate.my.cv.process') }}" enctype="multipart/form-data">
						@csrf
						<div class="job-list-box border rounded">
							<div class="p-3">
								<div class="form-group mt-3" id="form_add_new_cv">
									<label for="">Tambah file</label>
									<input type="file" name="add_new_cv" id="add_new_cv" class="form-control add_new_cv">
									@error('add_new_cv')
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
			$('.form_add_new_cv').show()
		})
	</script>
@endsection
