@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="row">
			<div class="col-md-6 mb-6">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="mb-0">Form {{ isset($category) ? 'Edit' : 'Create' }} Category</h5>
					</div>
					<div class="card-body">
						<form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST" action="{{ !empty($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}">
							@csrf
							@if (!empty($category))
								@method('PATCH')
							@endif
							<div class="mb-3">
								<label class="form-label">Name</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name" name="name" placeholder="Name" value="{{ isset($category) ? $category->name : '' }}" required />
								</div>
							</div>
							<div class="mb-3">
								<button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
								<a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
@endsection
