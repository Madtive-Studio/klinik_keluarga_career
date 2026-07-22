@extends('admin.layouts.main')
@section('content')
	<div class="container-fluid flex-grow-1 container-p-y">
		<div class="row g-4 mb-4">
			@if (!empty($activeBatch))
				<div class="col-md-3">
					<div class="card h-100">
						<div class="card-body">
							<span class="badge bg-label-success mb-2">{{ __('admin.dashboard.active_batch') }}</span>
							<h5 class="mb-1">{{ $activeBatch->code }} - {{ $activeBatch->name }}</h5>
							<small class="text-muted">{{ \Carbon\Carbon::parse($activeBatch->start_date)->diffForHumans() }} {{ __('admin.dashboard.until') }} {{ \Carbon\Carbon::parse($activeBatch->end_date)->diffForHumans() }}</small>
						</div>
					</div>
				</div>
			@endif
			<div class="col-md-3">
				<div class="card h-100">
					<div class="card-body">
						<p class="mb-1 text-muted">{{ __('admin.dashboard.job_list') }}</p>
						<h3 class="mb-0">{{ $jobList }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card h-100">
					<div class="card-body">
						<p class="mb-1 text-muted">{{ __('admin.dashboard.applicants') }}</p>
						<h3 class="mb-0">{{ $applicants }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card h-100">
					<div class="card-body">
						<p class="mb-1 text-muted">{{ __('admin.dashboard.hired') }}</p>
						<h3 class="mb-0">{{ $hired }}</h3>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h5 class="card-title mb-1">{{ __('admin.dashboard.chart_title') }}</h5>
				<p class="card-subtitle mb-0">{{ __('admin.dashboard.chart_subtitle') }}</p>
			</div>
			<div class="card-body">
				<div id="dashboardRecruitmentChart"></div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const chartEl = document.querySelector('#dashboardRecruitmentChart');
			if (!chartEl || typeof ApexCharts === 'undefined') {
				return;
			}

			const isDark = document.documentElement.classList.contains('dark-style');
			const labelColor = isDark ? '#B2B2C2' : '#677788';

			const options = {
				chart: {
					height: 360,
					type: 'area',
					toolbar: { show: false }
				},
				series: [
					{
						name: @json(__('admin.dashboard.series_candidates')),
						data: @json($candidateSeries)
					},
					{
						name: @json(__('admin.dashboard.series_hired')),
						data: @json($hiredSeries)
					}
				],
				colors: ['#7367F0', '#28C76F'],
				dataLabels: { enabled: false },
				stroke: { curve: 'smooth', width: 3 },
				fill: {
					type: 'gradient',
					gradient: {
						shadeIntensity: 0.8,
						opacityFrom: 0.45,
						opacityTo: 0.05
					}
				},
				xaxis: {
					categories: @json($chartLabels),
					labels: { style: { colors: labelColor } },
					axisBorder: { show: false },
					axisTicks: { show: false }
				},
				yaxis: {
					labels: {
						style: { colors: labelColor },
						formatter: value => Math.round(value)
					}
				},
				legend: {
					position: 'top',
					labels: { colors: labelColor }
				},
				grid: {
					borderColor: isDark ? '#3B3B5C' : '#E7E7EF'
				}
			};

			new ApexCharts(chartEl, options).render();
		});
	</script>
@endsection
