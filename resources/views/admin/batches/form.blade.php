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
						@if ($errors->any())
							<div class="alert alert-danger" role="alert">
								<ul class="mb-0 ps-3">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST" action="{{ !empty($batch) ? route('admin.batches.update', $batch->id) : route('admin.batches.store') }}">
							@csrf
							@if (!empty($batch))
								@method('PATCH')
							@endif
							<div class="mb-3">
								<label class="form-label" for="batch_code">{{ __('admin.batches.code') }}</label>
								<div class="input-group">
									<input type="text" class="form-control dt-full-name @error('code') is-invalid @enderror" id="batch_code" name="code" placeholder="{{ __('admin.batches.code') }}" required value="{{ old('code', isset($batch) ? $batch->code : ($code ?? '')) }}" maxlength="50" />
									<button type="button" class="btn btn-outline-secondary" id="btn-generate-code" title="{{ __('admin.batches.regenerate_code') }}">
										<i class="ti ti-refresh me-1"></i> <span class="d-none d-sm-inline">{{ __('admin.batches.regenerate_code') }}</span>
									</button>
								</div>
								<small class="text-muted d-block mt-1">{{ __('admin.batches.code_help') }}</small>
								@error('code') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.name') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name @error('name') is-invalid @enderror" name="name" placeholder="{{ __('admin.batches.name') }}" value="{{ old('name', isset($batch) ? $batch->name : '') }}" required />
								</div>
								@error('name') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.quota') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control dt-full-name @error('quota') is-invalid @enderror" id="batch_quota" name="quota" placeholder="{{ __('admin.batches.quota') }}" value="{{ old('quota', isset($batch) ? $batch->quota : 0) }}" required />
								</div>
								@error('quota') <small class="text-danger d-block" id="error-batch-quota">{{ $message }}</small> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.start_date') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control flatpickr-datetime @error('start_date') is-invalid @enderror" name="start_date" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('start_date', isset($batch) ? formatFlatpickrDatetime($batch->start_date) : '') }}" />
								</div>
								@error('start_date') <small class="text-danger d-block">{{ $message }}</small> @enderror
							</div>
							<div class="mb-3">
								<label class="form-label">{{ __('admin.batches.end_date') }}</label>
								<div class="input-group input-group-merge">
									<input type="text" class="form-control flatpickr-datetime @error('end_date') is-invalid @enderror" name="end_date" placeholder="{{ __('admin.form.datetime_placeholder') }}" required value="{{ old('end_date', isset($batch) ? formatFlatpickrDatetime($batch->end_date) : '') }}" />
								</div>
								@error('end_date') <small class="text-danger d-block">{{ $message }}</small> @enderror
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
	<script>
		$(function() {
			const $btnGenerateCode = $('#btn-generate-code');
			const $codeInput = $('#batch_code');
			if ($btnGenerateCode.length && $codeInput.length) {
				$btnGenerateCode.on('click', function() {
					const randomPart = Math.random().toString(16).substring(2, 12).toUpperCase();
					$codeInput.val('#' + randomPart).trigger('focus');
				});
			}

			const $quotaInput = $('#batch_quota');
			if ($quotaInput.length) {
				$quotaInput.on('blur', function() {
					const val = $(this).val().trim();
					if (/^\d+$/.test(val)) {
						const maxInt = '2147483647';
						const cleanVal = val.replace(/^0+/, '') || '0';
						if (cleanVal.length > maxInt.length || (cleanVal.length === maxInt.length && cleanVal > maxInt)) {
							$(this).val('2147483647');
						}
					}
				});
			}
		});
	</script>
@endsection
