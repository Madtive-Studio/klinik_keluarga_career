@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="row">
			<div class="col-md-6 mb-6">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="mb-0">{{ isset($batch) ? __('admin.batches.form_edit') : __('admin.batches.form_create') }}</h5>
					</div>
					<div class="card-body">
						<form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST" action="{{ !empty($batch) ? route('admin.batches.update', $batch->id) : route('admin.batches.store') }}">
							@csrf
							@if (!empty($batch))
								@method('PATCH')
							@endif
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.code') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name" name="code" readonly placeholder="{{ __('admin.batches.code') }}" required value="{{ isset($batch) ? $batch->code : $code }}" />
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.name') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name" name="name" placeholder="{{ __('admin.batches.name') }}" value="{{ isset($batch) ? $batch->name : '' }}" required />
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.quota') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name" name="quota" placeholder="{{ __('admin.batches.quota') }}" value="{{ isset($batch) ? $batch->quota : 0 }}" required />
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.start_date') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control flatpickr-datetime" name="start_date" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('start_date', isset($batch) ? formatFlatpickrDatetime($batch->start_date) : '') }}" />
								</div>
								@error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.end_date') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control flatpickr-datetime" name="end_date" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('end_date', isset($batch) ? formatFlatpickrDatetime($batch->end_date) : '') }}" />
								</div>
								@error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
							</div>
							<div class="mb-3">
								<button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">{{ __('admin.form.submit') }}</button>
								<a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary">{{ __('admin.form.cancel') }}</a>
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
