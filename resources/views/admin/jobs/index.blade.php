@extends('admin.layouts.main')
@section('css')
	<link rel="stylesheet" href="{{ asset('assets/admin/assets/vendor/libs/nouislider/nouislider.css') }}" />
	<style>
		.noUi-connect {
			background: #7367f0 !important;
		}
		.noUi-horizontal {
			height: 8px !important;
		}
		.noUi-handle {
			width: 18px !important;
			height: 18px !important;
			right: -9px !important;
			top: -6px !important;
			border-radius: 50% !important;
			background: #ffffff !important;
			border: 3px solid #7367f0 !important;
			box-shadow: 0 2px 6px rgba(115, 103, 240, 0.4) !important;
			cursor: grab !important;
		}
		.noUi-handle:before, .noUi-handle:after {
			display: none !important;
		}
		.batch-pills-slider {
			display: flex;
			flex-wrap: nowrap;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
			scrollbar-width: thin;
			scroll-behavior: smooth;
			padding-bottom: 6px;
		}
		.batch-pills-slider::-webkit-scrollbar {
			height: 4px;
		}
		.batch-pills-slider::-webkit-scrollbar-track {
			background: rgba(0, 0, 0, 0.05);
			border-radius: 4px;
		}
		.batch-pills-slider::-webkit-scrollbar-thumb {
			background: rgba(115, 103, 240, 0.35);
			border-radius: 4px;
		}
		.batch-pills-slider::-webkit-scrollbar-thumb:hover {
			background: rgba(115, 103, 240, 0.7);
		}
		.btn-batch-tab {
			white-space: nowrap;
			flex-shrink: 0;
		}
	</style>
@endsection
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		@if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				<strong>{{ $message }}</strong>
			</div>
		@endif

		<!-- Global Filter Card (Semua Filter dalam 1 Row Rapi & Responsif) -->
		<div class="card mb-4">
			<div class="card-body py-3">
				<form id="filter-form" class="row g-3 align-items-end">
					<div class="col-lg-3 col-md-6 col-sm-12">
						<label class="form-label fw-semibold mb-1 d-flex align-items-center" style="min-height: 20px;">{{ __('admin.jobs.category') }}</label>
						<select name="category" class="form-control filter-select">
							<option value="">{{ __('admin.datatable.all') }}</option>
							@foreach ($categories as $category)
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-lg-2 col-md-6 col-sm-12">
						<label class="form-label fw-semibold mb-1 d-flex align-items-center" style="min-height: 20px;">{{ __('admin.jobs.type') }}</label>
						<select name="type" class="form-control filter-select">
							<option value="">{{ __('admin.datatable.all') }}</option>
							@foreach (\App\Enums\JobType::getWithLabels() as $value => $label)
								<option value="{{ $value }}">{{ $label }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-lg-2 col-md-6 col-sm-12">
						<label class="form-label fw-semibold mb-1 d-flex align-items-center" style="min-height: 20px;">{{ __('admin.jobs.min_education') }}</label>
						<select name="min_education" class="form-control filter-select">
							<option value="">{{ __('admin.datatable.all') }}</option>
							@foreach (\App\Enums\EducationLevel::cases() as $level)
								<option value="{{ $level->value }}">{{ $level->label() }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12">
						<div class="d-flex align-items-center justify-content-between mb-1" style="min-height: 20px;">
							<label class="form-label fw-semibold mb-0">
								<i class="ti ti-cash me-1 text-primary"></i>Gaji:
							</label>
							<span id="salary-range-label" class="badge bg-label-primary px-2 py-0 fw-bold" style="font-size: 11px;">Rp 0 - Rp 100 Jt</span>
						</div>
						<div class="d-flex align-items-center px-1" style="height: 38px;">
							<div id="salary-slider" class="w-100"></div>
						</div>
						<input type="hidden" name="salary_min" id="salary_min" value="">
						<input type="hidden" name="salary_max" id="salary_max" value="">
					</div>
					<div class="col-lg-2 col-md-6 col-sm-12">
						<div class="d-flex gap-2" style="height: 38px;">
							<button type="button" id="btn-reset-filters" class="btn btn-outline-secondary flex-grow-1" style="height: 38px;" title="Reset Filter">
								<i class="ti ti-refresh me-1"></i> Reset
							</button>
							<button type="submit" class="btn btn-primary flex-grow-1" style="height: 38px;">
								<i class="ti ti-filter me-1"></i>{{ __('admin.datatable.filter') }}
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<!-- Batch Nav Tabs / Pills Slider (Scrollable Horizontal jika banyak batch) -->
		@if($batches->isNotEmpty())
			<div class="batch-tabs-container mb-4">
				<ul class="nav nav-pills batch-pills-slider gap-2 mb-0" id="batch-pills">
					@foreach ($batches as $index => $b)
						@php
							$isActive = ($b->status === 'ACTIVE' || $b->status === '1' || $b->status === 1);
						@endphp
						<li class="nav-item">
							<button type="button"
								class="nav-link btn-batch-tab {{ $index === 0 ? 'active' : '' }}"
								data-batch-id="{{ $b->id }}"
								data-batch-name="{{ $b->name }}"
								data-batch-code="{{ $b->code }}"
								data-batch-status="{{ $isActive ? 'Aktif' : 'Nonaktif' }}"
								data-batch-status-class="{{ $isActive ? 'bg-label-success' : 'bg-label-secondary' }}"
								data-batch-dates="{{ \Carbon\Carbon::parse($b->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($b->end_date)->translatedFormat('d M Y') }}"
								data-batch-quota="{{ $b->quota ?? '-' }}"
								data-create-url="{{ route('admin.jobs.create', ['batch_id' => $b->id]) }}">
								<i class="ti ti-layers-intersect me-1"></i> {{ $b->name }} ({{ $b->code }})
								@if($isActive)
									<span class="badge badge-dot bg-success ms-1" title="Gelombang Aktif"></span>
								@endif
							</button>
						</li>
					@endforeach
				</ul>
			</div>

			<!-- Single Card Table untuk Batch yang Sedang Dipilih -->
			@php
				$firstBatch = $batches->first();
				$firstIsActive = ($firstBatch->status === 'ACTIVE' || $firstBatch->status === '1' || $firstBatch->status === 1);
			@endphp
			<div class="card mb-4">
				<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-3">
					<div class="d-flex align-items-center gap-2">
						<div class="avatar avatar-sm bg-label-primary rounded d-flex align-items-center justify-content-center">
							<i class="ti ti-layers-intersect fs-4"></i>
						</div>
						<div>
							<div class="d-flex align-items-center gap-2">
								<h5 class="mb-0 fw-bold text-dark" id="current-batch-name">{{ $firstBatch->name }}</h5>
								<span class="badge bg-label-primary" id="current-batch-code">{{ $firstBatch->code }}</span>
								<span class="badge {{ $firstIsActive ? 'bg-label-success' : 'bg-label-secondary' }}" id="current-batch-status">
									{{ $firstIsActive ? 'Aktif' : 'Nonaktif' }}
								</span>
							</div>
							<small class="text-muted" id="current-batch-meta">
								<i class="ti ti-calendar me-1"></i>{{ \Carbon\Carbon::parse($firstBatch->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($firstBatch->end_date)->translatedFormat('d M Y') }}
								• Kuota Batch: <strong>{{ $firstBatch->quota ?? '-' }}</strong>
							</small>
						</div>
					</div>
					<div>
						<a href="{{ route('admin.jobs.create', ['batch_id' => $firstBatch->id]) }}" class="btn btn-sm btn-primary" id="btn-add-job">
							<i class="ti ti-plus me-1"></i> {{ __('admin.jobs.add') }}
						</a>
					</div>
				</div>
				<div class="card-datatable table-responsive pt-0">
					<table class="datatables-jobs table">
						<thead>
							<tr>
								<th>{{ __('admin.datatable.no') }}</th>
								<th>{{ __('admin.jobs.title_col') }}</th>
								<th>{{ __('admin.jobs.category') }}</th>
								<th>{{ __('admin.jobs.show_salary') }}</th>
								<th>{{ __('admin.jobs.salary') }}</th>
								<th>{{ __('admin.jobs.type') }}</th>
								<th>{{ __('admin.jobs.min_education') }}</th>
								<th>{{ __('admin.jobs.applicants_quota') }}</th>
								<th class="text-center">{{ __('admin.datatable.action') }}</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		@else
			<div class="card mb-4 p-5 text-center text-muted">
				<i class="ti ti-layers-off fs-1 d-block mb-2"></i>
				<h5 class="text-dark">Belum ada Gelombang (Batch) yang tersedia</h5>
				<p class="mb-3">Silakan buat Gelombang (Batch) rekrutmen terlebih dahulu untuk mulai mempublikasikan lowongan pekerjaan.</p>
				<div>
					<a href="{{ route('admin.batches.create') }}" class="btn btn-primary">
						<i class="ti ti-plus me-1"></i> {{ __('admin.batches.form_create') }}
					</a>
				</div>
			</div>
		@endif
	</div>
@endsection
@section('js')
	<script src="{{ asset('assets/admin/assets/vendor/libs/nouislider/nouislider.js') }}"></script>
	<script>
		function getAttrValue(el, val) {
			if (!val) return '-'
			return $(el).data(val)
		}

		$(function() {
			// Inisialisasi Slider Rentang Gaji (0 - 100jt, step 1jt)
			const slider = document.getElementById('salary-slider');
			const salaryMinInput = document.getElementById('salary_min');
			const salaryMaxInput = document.getElementById('salary_max');
			const salaryLabel = document.getElementById('salary-range-label');

			if (slider && typeof noUiSlider !== 'undefined') {
				noUiSlider.create(slider, {
					start: [0, 100000000],
					connect: true,
					step: 1000000,
					range: {
						'min': 0,
						'max': 100000000
					},
					format: {
						to: function (value) {
							return Math.round(value);
						},
						from: function (value) {
							return Number(value);
						}
					}
				});

				function formatRupiahShort(val) {
					if (val === 0) return 'Rp 0';
					if (val >= 1000000000) return 'Rp ' + (val / 1000000000) + ' M';
					if (val >= 1000000) return 'Rp ' + (val / 1000000) + ' Jt';
					return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
				}

				slider.noUiSlider.on('update', function (values, handle) {
					const minVal = parseInt(values[0]);
					const maxVal = parseInt(values[1]);

					salaryMinInput.value = minVal > 0 ? minVal : '';
					salaryMaxInput.value = maxVal < 100000000 ? maxVal : '';

					salaryLabel.innerText = formatRupiahShort(minVal) + ' - ' + formatRupiahShort(maxVal);
				});

				slider.noUiSlider.on('change', function () {
					if (dt_table) dt_table.ajax.reload();
				});
			}

			$(document).on('click', '.edit', function() {
				let route = getAttrValue(this, 'route')
				window.location.href = route
			})

			function getFilterParams() {
				var params = {};
				$('#filter-form').find('select, input').each(function() {
					var name = $(this).attr('name');
					if (!name) return;
					var val = $(this).val();
					if (val) params[name] = val;
				});
				return params;
			}

			let currentBatchId = '{{ $batches->first()?->id ?? "" }}';

			const $table = $('.datatables-jobs');
			let dt_table = null;

			if ($table.length) {
				dt_table = $table.DataTable({
					ajax: {
						url: "{{ route('admin.jobs.datatables') }}",
						data: function(d) {
							d.batch_id = currentBatchId;
							$.extend(d, getFilterParams());
						}
					},
					columns: [
						{
							data: 'DT_RowIndex',
							name: 'DT_RowIndex',
							searchable: false,
							orderable: false,
							className: 'text-center',
							width: '5%'
						},
						{
							data: 'title'
						},
						{
							data: 'category.name'
						},
						{
							data: 'is_show_salary'
						},
						{
							data: 'salary'
						},
						{
							data: 'type'
						},
						{
							data: 'min_education'
						},
						{
							data: 'quota'
						},
						{
							data: 'action',
							searchable: false,
							orderable: false,
							className: 'text-center'
						}
					],
					columnDefs: [{
						className: 'control',
						orderable: false,
						searchable: false,
						responsivePriority: 2,
						targets: 0,
					}],
					order: [
						[1, 'asc']
					],
					dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
					displayLength: 10,
					lengthMenu: [7, 10, 25, 50, 75, 100],
					language: {
						paginate: {
							next: '<i class="ti ti-chevron-right ti-sm"></i>',
							previous: '<i class="ti ti-chevron-left ti-sm"></i>'
						}
					}
				});
			}

			// Event saat berganti tab Batch
			$('.btn-batch-tab').on('click', function() {
				$('.btn-batch-tab').removeClass('active');
				$(this).addClass('active');

				currentBatchId = $(this).data('batch-id');
				$('#current-batch-name').text($(this).data('batch-name'));
				$('#current-batch-code').text($(this).data('batch-code'));

				const statusText = $(this).data('batch-status');
				const statusClass = $(this).data('batch-status-class');
				$('#current-batch-status').attr('class', 'badge ' + statusClass).text(statusText);

				const dates = $(this).data('batch-dates');
				const quota = $(this).data('batch-quota');
				$('#current-batch-meta').html('<i class="ti ti-calendar me-1"></i>' + dates + ' • Kuota Batch: <strong>' + quota + '</strong>');

				const createUrl = $(this).data('create-url');
				$('#btn-add-job').attr('href', createUrl);

				if (dt_table) dt_table.ajax.reload();
			});

			setTimeout(() => {
				$('.dataTables_filter .form-control').removeClass('form-control-sm');
				$('.dataTables_length .form-select').removeClass('form-select-sm');
			}, 300);

			$(document).on('click', '.delete', function() {
				let value = confirm(window.adminI18n.confirm_delete)
				if (value) {
					let route = getAttrValue(this, 'route')
					window.location.href = route
				}
			})

			$('#filter-form').on('submit', function(e) {
				e.preventDefault();
				if (dt_table) dt_table.ajax.reload();
			});

			$('.filter-select').on('change', function() {
				if (dt_table) dt_table.ajax.reload();
			});

			$('#btn-reset-filters').on('click', function() {
				$('#filter-form')[0].reset();
				if (slider && slider.noUiSlider) {
					slider.noUiSlider.set([0, 100000000]);
				}
				if (salaryMinInput) salaryMinInput.value = '';
				if (salaryMaxInput) salaryMaxInput.value = '';
				if (dt_table) dt_table.ajax.reload();
			});

			$(document).on('change', '.toggle-show-salary', function() {
				const checkbox = $(this)
				const jobId = checkbox.data('id')
				const isShowSalary = checkbox.is(':checked')

				$.ajax({
					url: "{{ url('admin/jobs') }}/" + jobId + "/toggle-salary",
					method: 'PATCH',
					data: {
						_token: '{{ csrf_token() }}',
						is_show_salary: isShowSalary ? 1 : 0,
					},
					error: function() {
						checkbox.prop('checked', !isShowSalary)
						alert(window.adminI18n.toggle_salary_failed)
					}
				})
			})
		});
	</script>
@endsection
