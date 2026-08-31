@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		@if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				<strong>{{ $message }}</strong>
			</div>
		@endif
		@php
			$defaultStartDate = now()->startOfMonth()->format('Y-m-d');
			$defaultEndDate = now()->endOfMonth()->format('Y-m-d');
		@endphp
		<!-- Date Range Filter Card -->
		<div class="card mb-4 border-0 shadow-sm">
			<div class="card-body py-3">
				<form id="form-filter-date" class="row g-3 align-items-end">
					<div class="col-md-4 col-sm-6">
						<label class="form-label fw-semibold text-dark mb-1">
							<i class="ti ti-calendar me-1 text-primary"></i>{{ __('admin.schedule_interviews.start_date_filter') }}
						</label>
						<input type="date" class="form-control" id="filter_start_date" name="start_date" value="{{ $defaultStartDate }}">
					</div>
					<div class="col-md-4 col-sm-6">
						<label class="form-label fw-semibold text-dark mb-1">
							<i class="ti ti-calendar-event me-1 text-primary"></i>{{ __('admin.schedule_interviews.end_date_filter') }}
						</label>
						<input type="date" class="form-control" id="filter_end_date" name="end_date" value="{{ $defaultEndDate }}">
					</div>
					<div class="col-md-4 col-12 d-flex gap-2">
						<button type="button" id="btn-filter" class="btn btn-primary flex-grow-1">
							<i class="ti ti-filter me-1"></i> {{ __('admin.datatable.filter') }}
						</button>
						<button type="button" id="btn-reset" class="btn btn-outline-secondary" title="Reset ke Bulan Ini">
							<i class="ti ti-refresh me-1"></i> Reset
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
							<th>{{ __('admin.schedule_interviews.batch') }}</th>
							<th>{{ __('admin.schedule_interviews.title_col') }}</th>
							<th>{{ __('admin.schedule_interviews.candidate') }}</th>
							<th>{{ __('admin.schedule_interviews.job') }}</th>
							<th>{{ __('admin.schedule_interviews.start_datetime') }}</th>
							<th>{{ __('admin.schedule_interviews.end_datetime') }}</th>
							<th>{{ __('admin.schedule_interviews.is_online') }}</th>
							<th>{{ __('admin.schedule_interviews.link') }}</th>
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

			if (dt_basic_table.length) {
				dt_basic = dt_basic_table.DataTable({
					ajax: {
						url: "{{ route('admin.schedule-interviews.datatables') }}",
						data: function(d) {
							d.start_date = $('#filter_start_date').val();
							d.end_date = $('#filter_end_date').val();
						}
					},
					columns: [{
							data: 'DT_RowIndex',
							name: 'DT_RowIndex',
							searchable: false,
							sortable: false,
							className: 'text-center',
							width: '5%'
						},
						{
							data: 'batch.code'
						},
						{
							data: 'title'
						},
						{
							data: 'candidate.name'
						},
						{
							data: 'job.title'
						},
						{
							data: 'start_datetime'
						},
						{
							data: 'end_datetime'
						},
						{
							data: 'is_online'
						},
						{
							data: 'link'
						},
						{
							data: 'action'
						}
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
						text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">{{ __('admin.datatable.create') }}</span>',
						className: 'create-new btn btn-primary waves-effect waves-light'
					}],
					initComplete: function(settings, json) {
						$('.card-header').after('<hr class="my-0">');
					}
				});
				$('div.head-label').html('<h5 class="card-title mb-0">{{ __('admin.schedule_interviews.title') }}</h5>');
			}

			const defaultStartDate = '{{ $defaultStartDate }}';
			const defaultEndDate = '{{ $defaultEndDate }}';

			$('#btn-filter').on('click', function() {
				if (dt_basic) {
					dt_basic.ajax.reload();
				}
			});

			$('#btn-reset').on('click', function() {
				$('#filter_start_date').val(defaultStartDate);
				$('#filter_end_date').val(defaultEndDate);
				if (dt_basic) {
					dt_basic.ajax.reload();
				}
			});

			setTimeout(() => {
				$('.dataTables_filter .form-control').removeClass('form-control-sm');
				$('.dataTables_length .form-select').removeClass('form-select-sm');
			}, 300);

			$('.create-new').click(function() {
				window.location.href = "{{ route('admin.schedule-interviews.create') }}"
			})

			$(document).on('click', '.delete', function() {
				let value = confirm(window.adminI18n.confirm_delete)
				if (value) {
					let route = getAttrValue(this, 'route')
					window.location.href = route
				}
			})
		});
	</script>
@endsection
