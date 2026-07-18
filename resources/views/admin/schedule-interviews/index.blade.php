@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		@if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				<strong>{{ $message }}</strong>
			</div>
		@endif
		<div class="card">
			<div class="card-datatable table-responsive pt-0">
				<table class="datatables-basic table">
					<thead>
						<tr>
							<th>{{ __('admin.datatable.no') }}</th>
							<th>{{ __('admin.schedule_interviews.apply_id') }}</th>
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
					ajax: "{{ route('admin.schedule-interviews.datatables') }}",
					columns: [{
							data: 'DT_RowIndex',
							name: 'DT_RowIndex',
							searchable: false,
							sortable: false,
							className: 'text-center',
							width: '5%'
						},
						{
							data: 'apply.uuid'
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
