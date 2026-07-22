@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		@if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				<strong>{{ $message }}</strong>
			</div>
		@endif

		<div class="card mb-4">
			<div class="card-body">
			<form id="filter-form" class="row g-3 align-items-end">
				<div class="col-md-2">
					<label class="form-label">{{ __('admin.jobs.batch') }}</label>
					<select name="batch_id" class="form-control filter-select">
						<option value="">{{ __('admin.datatable.all') }}</option>
						@foreach ($batches as $batch)
							<option value="{{ $batch->id }}">{{ $batch->code }} - {{ $batch->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('admin.jobs.category') }}</label>
					<select name="category" class="form-control filter-select">
						<option value="">{{ __('admin.datatable.all') }}</option>
						@foreach ($categories as $category)
							<option value="{{ $category->id }}">{{ $category->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('admin.jobs.type') }}</label>
					<select name="type" class="form-control filter-select">
						<option value="">{{ __('admin.datatable.all') }}</option>
						@foreach (\App\Enums\JobType::getWithLabels() as $value => $label)
							<option value="{{ $value }}">{{ $label }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('admin.jobs.salary_min') }}</label>
					<input type="text" inputmode="numeric" name="salary_min" class="form-control filter-input filter-salary" placeholder="Min" autocomplete="off">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('admin.jobs.salary_max') }}</label>
					<input type="text" inputmode="numeric" name="salary_max" class="form-control filter-input filter-salary" placeholder="Max" autocomplete="off">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('admin.jobs.min_education') }}</label>
					<select name="min_education" class="form-control filter-select">
						<option value="">{{ __('admin.datatable.all') }}</option>
						@foreach (\App\Enums\EducationLevel::cases() as $level)
							<option value="{{ $level->value }}">{{ $level->label() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-12 d-flex justify-content-end">
					<button type="submit" class="btn btn-primary">
						<i class="ti ti-filter me-1"></i>{{ __('admin.datatable.filter') }}
					</button>
				</div>
			</form>
			</div>
		</div>

		<div class="card">
			<div class="card-datatable table-responsive pt-0">
				<table class="datatables-basic table">
					<thead>
						<tr>
							<th>{{ __('admin.datatable.no') }}</th>
							<th>{{ __('admin.jobs.batch') }}</th>
							<th>{{ __('admin.jobs.title_col') }}</th>
							<th>{{ __('admin.jobs.category') }}</th>
							<th>{{ __('admin.jobs.show_salary') }}</th>
							<th>{{ __('admin.jobs.salary') }}</th>
							<th>{{ __('admin.jobs.type') }}</th>
							<th>{{ __('admin.jobs.min_education') }}</th>
							<th>{{ __('admin.jobs.applicants_quota') }}</th>
							<th>{{ __('admin.datatable.action') }}</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
@endsection
@section('js')
	<script>
		function getAttrValue(el, val) {
			if (!val) return '-'
			return $(el).data(val)
		}

		$(function() {
			const formUI = $('#form-add-new-record')
			$(document).on('click', '.edit', function() {
				let route = getAttrValue(this, 'route')
				window.location.href = route
			})

			var dt_basic_table = $('.datatables-basic'),
				dt_complex_header_table = $('.dt-complex-header'),
				dt_row_grouping_table = $('.dt-row-grouping'),
				dt_multilingual_table = $('.dt-multilingual'),
				dt_basic;

			function getFilterParams() {
				var params = {};
				$('#filter-form').find('select, input').each(function() {
					if ($(this).hasClass('filter-salary')) return;
					var name = $(this).attr('name');
					if (!name) return;
					var val = $(this).val();
					if (val) params[name] = val;
				});
				return params;
			}

			if (dt_basic_table.length) {
				dt_basic = dt_basic_table.DataTable({
					ajax: {
						url: "{{ route('admin.jobs.datatables') }}",
						data: function(d) {
							$.extend(d, getFilterParams());
						}
					},
					columns: [{
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
							data: 'batch.code'
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
							data: 'action'
						}
					],
					columnDefs: [{
						className: 'control',
						orderable: false,
						searchable: false,
						responsivePriority: 2,
						targets: 0,
					}, ],
					order: [
						[0, 'asc']
					],
					dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-6 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
					displayLength: 7,
					lengthMenu: [7, 10, 25, 50, 75, 100],
					language: {
						paginate: {
							next: '<i class="ti ti-chevron-right ti-sm"></i>',
							previous: '<i class="ti ti-chevron-left ti-sm"></i>'
						}
					},
					buttons: [{
						text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">{{ __('admin.jobs.add') }}</span>',
						className: 'create-new btn btn-primary waves-effect waves-light'
					}],
					initComplete: function(settings, json) {
						$('.card-header').after('<hr class="my-0">');
					}
				});
				$('div.head-label').html('<h5 class="card-title mb-0">{{ __('admin.jobs.datatable') }}</h5>');
			}


			setTimeout(() => {
				$('.dataTables_filter .form-control').removeClass('form-control-sm');
				$('.dataTables_length .form-select').removeClass('form-select-sm');
			}, 300);

			$('.create-new').click(function() {
				window.location.href = "{{ route('admin.jobs.create') }}"
			})

			$(document).on('click', '.delete', function() {
				let value = confirm(window.adminI18n.confirm_delete)
				if (value) {
					let route = getAttrValue(this, 'route')
					window.location.href = route
				}
			})

			$('.filter-salary').each(function() {
				var input = this;
				var hidden = document.createElement('input');
				hidden.type = 'hidden';
				hidden.name = input.name;
				hidden.className = 'filter-salary-hidden';
				input.name = input.name + '_display';
				input.parentNode.appendChild(hidden);

				input.addEventListener('input', function() {
					var digits = this.value.replace(/\D/g, '');
					hidden.value = digits;
					this.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
				});

				input.addEventListener('blur', function() {
					if (!this.value) hidden.value = '';
				});

				if (this.value) {
					var digits = this.value.replace(/\D/g, '');
					hidden.value = digits;
					this.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
				}
			});

			$('#filter-form').on('submit', function(e) {
				e.preventDefault();
				dt_basic.ajax.reload();
			});

			$('.filter-select, .filter-input').on('change', function() {
				dt_basic.ajax.reload();
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
