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
	</style>
@endsection
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		@if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				<strong>{{ $message }}</strong>
			</div>
		@endif

		<!-- Global Filter Card (Rentang Gaji Slider 0 - 100 Juta, Step 1 Juta) -->
		<div class="card mb-4">
			<div class="card-body">
				<form id="filter-form" class="row g-3 align-items-end">
					<div class="col-md-4 col-sm-6">
						<label class="form-label fw-semibold">{{ __('admin.jobs.category') }}</label>
						<select name="category" class="form-control filter-select">
							<option value="">{{ __('admin.datatable.all') }}</option>
							@foreach ($categories as $category)
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4 col-sm-6">
						<label class="form-label fw-semibold">{{ __('admin.jobs.type') }}</label>
						<select name="type" class="form-control filter-select">
							<option value="">{{ __('admin.datatable.all') }}</option>
							@foreach (\App\Enums\JobType::getWithLabels() as $value => $label)
								<option value="{{ $value }}">{{ $label }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4 col-sm-6">
						<label class="form-label fw-semibold">{{ __('admin.jobs.min_education') }}</label>
						<select name="min_education" class="form-control filter-select">
							<option value="">{{ __('admin.datatable.all') }}</option>
							@foreach (\App\Enums\EducationLevel::cases() as $level)
								<option value="{{ $level->value }}">{{ $level->label() }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-8 col-12">
						<div class="d-flex align-items-center justify-content-between mb-2">
							<label class="form-label fw-semibold mb-0">
								<i class="ti ti-cash me-1 text-primary"></i>Rentang Gaji:
							</label>
							<span id="salary-range-label" class="badge bg-label-primary fs-6 px-3 py-1 fw-bold">Rp 0 - Rp 100.000.000</span>
						</div>
						<div class="px-2 pt-2 pb-1">
							<div id="salary-slider"></div>
						</div>
						<input type="hidden" name="salary_min" id="salary_min" value="">
						<input type="hidden" name="salary_max" id="salary_max" value="">
					</div>
					<div class="col-md-4 col-12 d-flex justify-content-end gap-2 align-items-center">
						<button type="button" id="btn-reset-filters" class="btn btn-outline-secondary">
							<i class="ti ti-refresh me-1"></i> Reset
						</button>
						<button type="submit" class="btn btn-primary">
							<i class="ti ti-filter me-1"></i>{{ __('admin.datatable.filter') }}
						</button>
					</div>
				</form>
			</div>
		</div>

		<!-- Daftar Tabel Berdasarkan Jumlah Batch yang Tersedia (N Batch == N Tabel) -->
		@forelse ($batches as $batch)
			@php
				$isActive = ($batch->status === 'ACTIVE' || $batch->status === '1' || $batch->status === 1);
			@endphp
			<div class="card mb-4">
				<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-3">
					<div class="d-flex align-items-center gap-2">
						<div class="avatar avatar-sm bg-label-primary rounded d-flex align-items-center justify-content-center">
							<i class="ti ti-layers-intersect fs-4"></i>
						</div>
						<div>
							<div class="d-flex align-items-center gap-2">
								<h5 class="mb-0 fw-bold text-dark">{{ $batch->name }}</h5>
								<span class="badge bg-label-primary">{{ $batch->code }}</span>
								@if($isActive)
									<span class="badge bg-label-success">Aktif</span>
								@else
									<span class="badge bg-label-secondary">Nonaktif</span>
								@endif
							</div>
							<small class="text-muted">
								<i class="ti ti-calendar me-1"></i>{{ \Carbon\Carbon::parse($batch->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($batch->end_date)->translatedFormat('d M Y') }}
								• Kuota Batch: <strong>{{ $batch->quota ?? '-' }}</strong>
							</small>
						</div>
					</div>
					<div>
						<a href="{{ route('admin.jobs.create', ['batch_id' => $batch->id]) }}" class="btn btn-sm btn-primary">
							<i class="ti ti-plus me-1"></i> {{ __('admin.jobs.add') }}
						</a>
					</div>
				</div>
				<div class="card-datatable table-responsive pt-0">
					<table class="datatables-jobs table" data-batch-id="{{ $batch->id }}">
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
		@empty
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
		@endforelse
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

				slider.noUiSlider.on('update', function (values, handle) {
					const minVal = parseInt(values[0]);
					const maxVal = parseInt(values[1]);

					salaryMinInput.value = minVal > 0 ? minVal : '';
					salaryMaxInput.value = maxVal < 100000000 ? maxVal : '';

					const formattedMin = 'Rp ' + new Intl.NumberFormat('id-ID').format(minVal);
					const formattedMax = 'Rp ' + new Intl.NumberFormat('id-ID').format(maxVal);
					salaryLabel.innerText = formattedMin + ' - ' + formattedMax;
				});

				slider.noUiSlider.on('change', function () {
					reloadAllTables();
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

			var dtTables = [];

			$('.datatables-jobs').each(function() {
				var $table = $(this);
				var batchId = $table.data('batch-id');

				var dt = $table.DataTable({
					ajax: {
						url: "{{ route('admin.jobs.datatables') }}",
						data: function(d) {
							d.batch_id = batchId;
							$.extend(d, getFilterParams());
						}
					},
					columns: [
						{
							data: null,
							searchable: false,
							orderable: false,
							className: 'text-center',
							width: '5%',
							render: function (data, type, row, meta) {
								return meta.row + meta.settings._iDisplayStart + 1;
							}
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
					displayLength: 7,
					lengthMenu: [7, 10, 25, 50, 75, 100],
					language: {
						paginate: {
							next: '<i class="ti ti-chevron-right ti-sm"></i>',
							previous: '<i class="ti ti-chevron-left ti-sm"></i>'
						}
					}
				});

				dtTables.push(dt);
			});

			function reloadAllTables() {
				dtTables.forEach(function(dt) {
					dt.ajax.reload();
				});
			}

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
				reloadAllTables();
			});

			$('.filter-select').on('change', function() {
				reloadAllTables();
			});

			$('#btn-reset-filters').on('click', function() {
				$('#filter-form')[0].reset();
				if (slider && slider.noUiSlider) {
					slider.noUiSlider.set([0, 100000000]);
				}
				if (salaryMinInput) salaryMinInput.value = '';
				if (salaryMaxInput) salaryMaxInput.value = '';
				reloadAllTables();
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
