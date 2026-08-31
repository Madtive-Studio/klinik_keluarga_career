@extends('admin.layouts.main')
@section('content')
	<div class="container-fluid flex-grow-1 container-p-y">
		<!-- Top Stat Cards (4 Cards Grid) -->
		<div class="row g-4 mb-4">
			<div class="col-lg-3 col-sm-6">
				<div class="card h-100 shadow-sm border-0">
					<div class="card-body">
						@if (!empty($activeBatch))
							<span class="badge bg-label-success mb-2">
								<i class="ti ti-circle-check me-1"></i>{{ __('admin.dashboard.active_batch') }}
							</span>
							<h5 class="mb-1 fw-bold text-dark">{{ $activeBatch->code }} - {{ $activeBatch->name }}</h5>
							<small class="text-muted">
								<i class="ti ti-calendar me-1"></i>{{ \Carbon\Carbon::parse($activeBatch->start_date)->translatedFormat('d M') }} {{ __('admin.dashboard.until') }} {{ \Carbon\Carbon::parse($activeBatch->end_date)->translatedFormat('d M Y') }}
							</small>
						@else
							<span class="badge bg-label-secondary mb-2">
								<i class="ti ti-alert-circle me-1"></i>{{ __('admin.dashboard.no_active_batch') }}
							</span>
							<h6 class="mb-1 text-muted">{{ __('admin.dashboard.no_active_batch_desc') }}</h6>
							<a href="{{ route('admin.batches.index') }}" class="small text-primary fw-semibold">
								<i class="ti ti-plus me-1"></i>Kelola Gelombang
							</a>
						@endif
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="card h-100 shadow-sm border-0">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<p class="mb-1 text-muted fw-semibold">{{ __('admin.dashboard.job_list') }}</p>
								<h3 class="mb-0 fw-bold text-dark">{{ $jobList }}</h3>
							</div>
							<div class="avatar avatar-md bg-label-primary rounded p-2">
								<i class="ti ti-briefcase fs-3"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="card h-100 shadow-sm border-0">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<p class="mb-1 text-muted fw-semibold">{{ __('admin.dashboard.applicants') }}</p>
								<h3 class="mb-0 fw-bold text-dark">{{ $applicants }}</h3>
							</div>
							<div class="avatar avatar-md bg-label-info rounded p-2">
								<i class="ti ti-users fs-3"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="card h-100 shadow-sm border-0">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div>
								<p class="mb-1 text-muted fw-semibold">{{ __('admin.dashboard.hired') }}</p>
								<h3 class="mb-0 fw-bold text-success">{{ $hired }}</h3>
							</div>
							<div class="avatar avatar-md bg-label-success rounded p-2">
								<i class="ti ti-user-check fs-3"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Main Section: Recruitment Chart & Upcoming Interview Reminder -->
		<div class="row g-4">
			<!-- Left: Recruitment Chart (col-lg-7) -->
			<div class="col-lg-7 col-12">
				<div class="card h-100 shadow-sm border-0">
					<div class="card-header border-bottom pb-3">
						<h5 class="card-title mb-1 fw-bold">{{ __('admin.dashboard.chart_title') }}</h5>
						<p class="card-subtitle text-muted mb-0 small">{{ __('admin.dashboard.chart_subtitle') }}</p>
					</div>
					<div class="card-body pt-3">
						<div id="dashboardRecruitmentChart"></div>
					</div>
				</div>
			</div>

			<!-- Right: Upcoming Interview Schedules Reminder (col-lg-5) -->
			<div class="col-lg-5 col-12">
				<div class="card h-100 shadow-sm border-0">
					<div class="card-header d-flex align-items-center justify-content-between border-bottom pb-3">
						<div>
							<h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
								<i class="ti ti-calendar-event text-primary"></i>
								{{ __('admin.dashboard.upcoming_interviews') }}
							</h5>
							<small class="text-muted">{{ __('admin.dashboard.upcoming_interviews_sub') }}</small>
						</div>
						<div>
							<a href="{{ route('admin.schedule-interviews.index') }}" class="btn btn-xs btn-outline-primary">
								{{ __('admin.dashboard.view_all') }} <i class="ti ti-chevron-right ms-1"></i>
							</a>
						</div>
					</div>
					<div class="card-body p-0">
						@if($upcomingInterviews->isNotEmpty())
							<ul class="list-group list-group-flush">
								@foreach($upcomingInterviews as $interview)
									@php
										$isFuture = \Carbon\Carbon::parse($interview->start_datetime)->isFuture();
										$isToday = \Carbon\Carbon::parse($interview->start_datetime)->isToday();
									@endphp
									<li class="list-group-item px-4 py-3 border-bottom">
										<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
											<div class="d-flex align-items-center gap-2">
												<div class="avatar avatar-sm bg-label-primary rounded-circle d-flex align-items-center justify-content-center fw-bold">
													{{ strtoupper(substr($interview->candidate->name ?? 'P', 0, 1)) }}
												</div>
												<div>
													<h6 class="mb-0 fw-semibold text-dark">{{ $interview->candidate->name ?? 'Kandidat' }}</h6>
													<small class="text-muted">{{ $interview->job->title ?? '-' }}</small>
												</div>
											</div>
											<div>
												@if($interview->is_online)
													<span class="badge bg-label-info"><i class="ti ti-video me-1"></i>Online</span>
												@else
													<span class="badge bg-label-secondary"><i class="ti ti-building me-1"></i>Offline</span>
												@endif
											</div>
										</div>
										<div class="d-flex align-items-center justify-content-between mt-2 pt-1 small text-muted">
											<span>
												<i class="ti ti-clock me-1 text-primary"></i>
												<strong>{{ \Carbon\Carbon::parse($interview->start_datetime)->translatedFormat('d M Y, H:i') }} WIB</strong>
												@if($isToday)
													<span class="badge bg-warning text-dark ms-1">Hari Ini</span>
												@elseif($isFuture)
													<span class="badge bg-label-primary ms-1">{{ \Carbon\Carbon::parse($interview->start_datetime)->diffForHumans() }}</span>
												@endif
											</span>
											@if($interview->is_online && $interview->link)
												<a href="{{ $interview->link }}" target="_blank" class="btn btn-xs btn-primary">
													<i class="ti ti-link me-1"></i> Link Room
												</a>
											@endif
										</div>
									</li>
								@endforeach
							</ul>
						@else
							<div class="p-5 text-center text-muted">
								<i class="ti ti-calendar-off fs-1 d-block mb-2 text-muted"></i>
								<p class="mb-2 fw-semibold text-dark">{{ __('admin.dashboard.no_upcoming_interviews') }}</p>
								<a href="{{ route('admin.schedule-interviews.create') }}" class="btn btn-sm btn-outline-primary">
									<i class="ti ti-plus me-1"></i>Buat Jadwal Wawancara
								</a>
							</div>
						@endif
					</div>
				</div>
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
