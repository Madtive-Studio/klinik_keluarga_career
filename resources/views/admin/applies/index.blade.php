@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		@if ($message = Session::get('success'))
			<div class="alert alert-success" role="alert">
				<strong>{{ $message }}</strong>
			</div>
		@endif

		<ul class="nav nav-pills flex-wrap gap-2 mb-4">
			<li class="nav-item">
				<a class="nav-link {{ empty($status) ? 'active' : '' }}" href="{{ route('admin.applies.index') }}">Semua</a>
			</li>
			@foreach ($statuses as $value => $label)
				<li class="nav-item">
					<a class="nav-link {{ $status === $value ? 'active' : '' }}" href="{{ route('admin.applies.index', ['status' => $value]) }}">{{ $label }}</a>
				</li>
			@endforeach
		</ul>

		<div class="card">
			<div class="card-datatable table-responsive pt-0">
				<table class="datatables-basic table">
					<thead>
						<tr>
							<th>No</th>
							<th>Name</th>
							<th>Job</th>
							<th>Batch</th>
							<th>CV</th>
							<th>Score</th>
							<th>Rekomendasi</th>
							<th>Status</th>
							<th>Action</th>
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
			var dt_basic_table = $('.datatables-basic'),
				dt_complex_header_table = $('.dt-complex-header'),
				dt_row_grouping_table = $('.dt-row-grouping'),
				dt_multilingual_table = $('.dt-multilingual'),
				dt_basic;

			if (dt_basic_table.length) {
				dt_basic = dt_basic_table.DataTable({
					ajax: "{{ route('admin.applies.datatables') }}?status=" + encodeURIComponent('{{ $status }}'),
					columns: [{
							data: 'DT_RowIndex',
							name: 'DT_RowIndex',
							searchable: false,
							sortable: false,
							className: 'text-center',
							width: '5%'
						},
						{
							data: 'candidate.name'
						},
						{
							data: 'job.title'
						},
						{
							data: 'batch.name'
						},
						{
							data: 'document.name'
						},
						{
							data: 'auto_score',
							className: 'text-center'
						},
						{
							data: 'score_recommendation',
							orderable: false,
							searchable: false
						},
						{
							data: 'status'
						},
						{
							data: 'action'
						}
					],
					order: [
						[5, 'desc']
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
					buttons: [],
					initComplete: function(settings, json) {
						$('.card-header').after('<hr class="my-0">');
					}
				});
				$('div.head-label').html('<h5 class="card-title mb-0">Applies Datatable</h5>');
			}


			setTimeout(() => {
				$('.dataTables_filter .form-control').removeClass('form-control-sm');
				$('.dataTables_length .form-select').removeClass('form-select-sm');
			}, 300);
		});
	</script>
@endsection
