@extends('admin.layouts.main')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<a href="{{ route('admin.candidates.index') }}" class="btn btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i> {{ __('admin.jobs.back_to_list') }}
			</a>
		</div>

		<div class="row">
			<!-- Left Column: Dossier Profile & History -->
			<div class="col-md-7 mb-4">
				<!-- Candidate Profile Card -->
				<div class="card mb-4">
					<div class="card-header d-flex align-items-center justify-content-between border-bottom pb-3">
						<div class="d-flex align-items-center gap-3">
							<div class="avatar avatar-lg bg-label-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4">
								{{ strtoupper(substr($candidate->name ?? 'P', 0, 2)) }}
							</div>
							<div>
								<h5 class="mb-1 fw-bold">{{ $candidate->name }}</h5>
								<span class="text-muted small">
									<i class="ti ti-calendar me-1"></i>Terdaftar sejak: <strong>{{ date('d M Y, H:i', strtotime($candidate->created_at)) }} WIB</strong>
								</span>
							</div>
						</div>
						<div>
							@if($candidate->email_verified_at)
								<span class="badge bg-label-success px-3 py-2">
									<i class="ti ti-circle-check me-1"></i>Email Verified
								</span>
							@else
								<span class="badge bg-label-secondary px-3 py-2">
									<i class="ti ti-clock me-1"></i>Unverified
								</span>
							@endif
						</div>
					</div>

					<div class="card-body pt-4">
						@php
							$profile = $candidate->profile;
						@endphp

						<!-- Section 1: Biodata & Kontak Pelamar -->
						<div class="mb-4">
							<h6 class="text-muted text-uppercase fw-semibold mb-3 d-flex align-items-center gap-2" style="font-size: 13px; letter-spacing: 0.5px;">
								<i class="ti ti-id text-primary"></i> {{ __('admin.applies.candidate_personal_info') }}
							</h6>
							<div class="row g-3">
								<div class="col-sm-6">
									<small class="text-muted d-block mb-1">{{ __('common.email') }}</small>
									<span class="fw-semibold">{{ $candidate->email }}</span>
								</div>
								<div class="col-sm-6">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.phone_wa') }}</small>
									<span class="fw-semibold">
										@if(!empty($candidate->phone))
											<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $candidate->phone) }}" target="_blank" class="text-success text-decoration-none">
												<i class="ti ti-brand-whatsapp me-1"></i>{{ $candidate->phone }}
											</a>
										@else
											-
										@endif
									</span>
								</div>
								<div class="col-sm-6">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.birth_info') }}</small>
									<span class="fw-semibold">
										@if(!empty($candidate->birth_date))
											{{ \Carbon\Carbon::parse($candidate->birth_date)->translatedFormat('d F Y') }}
											<span class="badge bg-label-secondary ms-1">{{ \Carbon\Carbon::parse($candidate->birth_date)->age }} tahun</span>
										@else
											-
										@endif
									</span>
								</div>
								<div class="col-sm-6">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.domicile') }}</small>
									<span class="fw-semibold">{{ $profile?->city ?? '-' }}@if(!empty($profile?->province)), {{ $profile->province }}@endif</span>
								</div>
								<div class="col-12">
									<small class="text-muted d-block mb-1">{{ __('candidate.auth.address') }}</small>
									<span class="fw-semibold">{{ $candidate->address ?? '-' }}</span>
								</div>
							</div>
						</div>

						<hr class="my-4">

						<!-- Section 2: Riwayat Pendidikan & Pengalaman -->
						<div class="mb-4">
							<h6 class="text-muted text-uppercase fw-semibold mb-3 d-flex align-items-center gap-2" style="font-size: 13px; letter-spacing: 0.5px;">
								<i class="ti ti-school text-primary"></i> {{ __('admin.applies.candidate_education_info') }}
							</h6>
							<div class="row g-3">
								<div class="col-sm-8">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.education_details') }}</small>
									<div>
										<span class="badge bg-label-primary me-1">{{ $profile?->education_level ?? '-' }}</span>
										<strong class="text-dark">{{ $profile?->major ?? '-' }}</strong>
										<span class="text-muted">({{ $profile?->university ?? '-' }})</span>
									</div>
								</div>
								<div class="col-sm-4">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.gpa') }}</small>
									<span class="fw-bold text-primary fs-6">{{ $profile?->gpa ?? '-' }}</span>
								</div>
								<div class="col-sm-6">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.experience_years') }}</small>
									<span class="fw-semibold">
										{{ $profile?->years_of_experience ? $profile->years_of_experience . ' tahun' : 'Fresh Graduate / 0 thn' }}
									</span>
								</div>
								<div class="col-sm-6">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.last_experience') }}</small>
									<span class="fw-semibold">
										{{ $profile?->last_position ?? '-' }}@if(!empty($profile?->last_company)) di {{ $profile->last_company }}@endif
									</span>
								</div>
								<div class="col-sm-6">
									<small class="text-muted d-block mb-1">{{ __('admin.applies.expected_salary') }}</small>
									<span class="fw-bold text-success">
										{{ $profile?->expected_salary ? 'Rp ' . number_format($profile->expected_salary, 0, ',', '.') : 'Negosiasi / Mengikuti Standar' }}
									</span>
								</div>
							</div>
						</div>

						<hr class="my-4">

						<!-- Section 3: Keahlian Pelamar (Skills) -->
						<div>
							<h6 class="text-muted text-uppercase fw-semibold mb-3 d-flex align-items-center gap-2" style="font-size: 13px; letter-spacing: 0.5px;">
								<i class="ti ti-tags text-primary"></i> {{ __('admin.applies.candidate_skills_info') }}
							</h6>
							@if($candidate->skills && $candidate->skills->isNotEmpty())
								<div class="d-flex flex-wrap gap-2">
									@foreach($candidate->skills as $skill)
										<span class="badge bg-label-primary fs-6 py-2 px-3">
											<i class="ti ti-check me-1"></i>{{ $skill->name }}
										</span>
									@endforeach
								</div>
							@else
								<span class="text-muted small italic">{{ __('admin.applies.no_skills') }}</span>
							@endif
						</div>

					</div>
				</div>

				<!-- Section 4: Riwayat Pengajuan Lamaran Kerja -->
				<div class="card mb-4">
					<div class="card-header d-flex align-items-center justify-content-between pb-3">
						<h5 class="card-title mb-0 d-flex align-items-center gap-2">
							<i class="ti ti-history text-primary"></i>
							Riwayat Lamaran Pekerjaan ({{ $candidate->applies->count() }})
						</h5>
					</div>
					<div class="card-body p-0">
						@if($candidate->applies->isNotEmpty())
							<div class="table-responsive">
								<table class="table table-hover table-striped mb-0">
									<thead class="table-light">
										<tr>
											<th>Lowongan Pekerjaan</th>
											<th>Batch</th>
											<th>Tanggal Melamar</th>
											<th>Status</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody>
										@foreach($candidate->applies as $apply)
											@php
												$statusBadges = [
													'IN REVIEW' => 'bg-label-info',
													'NOT SUITABLE' => 'bg-label-danger',
													'SHORTLISTED' => 'bg-label-success',
													'HIRED' => 'bg-label-primary',
												];
												$badgeClass = $statusBadges[$apply->status] ?? 'bg-label-secondary';
											@endphp
											<tr>
												<td>
													<strong class="d-block">{{ $apply->job->title ?? '-' }}</strong>
													<small class="text-muted">{{ $apply->job->code ?? '' }} | {{ $apply->job->category->name ?? '-' }}</small>
												</td>
												<td>
													<span class="badge bg-label-secondary">{{ $apply->batch->name ?? '-' }}</span>
												</td>
												<td>
													<small>{{ date('d M Y', strtotime($apply->created_at)) }}</small>
												</td>
												<td>
													<span class="badge {{ $badgeClass }}">{{ $apply->status }}</span>
												</td>
												<td class="text-center">
													<a href="{{ route('admin.applies.show', $apply->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Evaluasi Lamaran">
														<i class="ti ti-eye"></i>
													</a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						@else
							<div class="p-4 text-center text-muted">
								<i class="ti ti-briefcase-off d-block fs-1 mb-2"></i>
								<p class="mb-0">Pelamar ini belum pernah mengajukan lamaran pekerjaan ke lowongan mana pun.</p>
							</div>
						@endif
					</div>
				</div>
			</div>

			<!-- Right Column: Document Repository & Account Summary -->
			<div class="col-md-5 mb-4">
				<!-- Document Repository Card -->
				<div class="card mb-4">
					<div class="card-header d-flex align-items-center justify-content-between pb-3">
						<h5 class="card-title mb-0 d-flex align-items-center gap-2">
							<i class="ti ti-files text-primary"></i>
							{{ __('candidate.documents.title') }} ({{ $candidate->documents->count() }})
						</h5>
					</div>
					<div class="card-body p-3">
						@if($candidate->documents->isNotEmpty())
							<div class="list-group list-group-flush">
								@foreach ($candidate->documents as $doc)
									@php
										$docUrl = Illuminate\Support\Facades\Storage::url($doc->file);
										$extension = strtolower(pathinfo($doc->file, PATHINFO_EXTENSION));
										$docTypeLabel = $doc->type?->getLabel() ?? $doc->type;
										$docBadgeClass = $doc->type?->getBadgeClass() ?? 'badge-secondary';
									@endphp
									<div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
										<div class="me-2 text-truncate" style="max-width: 220px;">
											<div class="d-flex align-items-center gap-2">
												<i class="ti ti-file-text text-primary fs-4"></i>
												<div>
													<span title="{{ $doc->name }}" class="fw-semibold text-truncate d-block" style="max-width: 180px;">{{ $doc->name }}</span>
													<span class="badge {{ $docBadgeClass }} mt-1">{{ $docTypeLabel }}</span>
												</div>
											</div>
										</div>
										<div class="d-flex align-items-center gap-1">
											<button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-preview-doc"
												data-url="{{ $docUrl }}"
												data-title="{{ $doc->name }}"
												data-type="{{ $docTypeLabel }}"
												data-ext="{{ $extension }}"
												title="{{ __('admin.applies.preview') }}">
												<i class="ti ti-eye"></i>
											</button>
											<a href="{{ $docUrl }}" download target="_blank" class="btn btn-sm btn-icon btn-outline-secondary" title="Download">
												<i class="ti ti-download"></i>
											</a>
										</div>
									</div>
								@endforeach
							</div>
						@else
							<div class="text-center py-4 text-muted">
								<i class="ti ti-file-off d-block fs-1 mb-2"></i>
								<p class="mb-0 small">{{ __('admin.applies.no_documents') }}</p>
							</div>
						@endif
					</div>
				</div>

				<!-- Quick Summary Card -->
				<div class="card mb-4">
					<div class="card-header pb-2">
						<h5 class="card-title mb-0 d-flex align-items-center gap-2">
							<i class="ti ti-info-circle text-primary"></i> Ringkasan Akun Pelamar
						</h5>
					</div>
					<div class="card-body pt-2">
						<ul class="list-unstyled mb-0">
							<li class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
								<span class="text-muted">Status Akun:</span>
								<strong>{{ $candidate->email_verified_at ? 'Aktif & Terverifikasi' : 'Belum Verifikasi Email' }}</strong>
							</li>
							<li class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
								<span class="text-muted">Total Berkas Dokumen:</span>
								<strong>{{ $candidate->documents->count() }} Berkas</strong>
							</li>
							<li class="d-flex justify-content-between align-items-center">
								<span class="text-muted">Total Lamaran Diajukan:</span>
								<strong>{{ $candidate->applies->count() }} Lamaran</strong>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Document Preview Modal -->
	<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" id="docPreviewDialog" style="max-width: 900px;">
			<div class="modal-content">
				<div class="modal-header px-4 py-3 border-bottom">
					<h5 class="modal-title d-flex align-items-center gap-2 mb-0">
						<i class="ti ti-file-text text-primary fs-4"></i>
						<span id="docPreviewTitle" class="fw-bold fs-6 text-truncate" style="max-width: 550px;">{{ __('admin.applies.preview_title') }}</span>
						<span id="docPreviewBadge" class="badge bg-primary"></span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-3 p-md-4" id="docPreviewBody" style="max-height: 75vh; overflow-y: auto;">
					<!-- Dynamic Preview Content -->
				</div>
				<div class="modal-footer px-4 py-3 border-top justify-content-between">
					<a id="docPreviewExternalLink" href="#" target="_blank" class="btn btn-outline-secondary">
						<i class="ti ti-external-link me-1"></i> {{ __('admin.applies.open_new_tab') }}
					</a>
					<a id="docPreviewDownloadBtn" href="#" download class="btn btn-primary">
						<i class="ti ti-download me-1"></i> Download
					</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
	<script>
		function setModalSize(isLargePreview) {
			const $dialog = $('#docPreviewDialog');
			if (isLargePreview) {
				$dialog.removeClass('modal-md').addClass('modal-lg').css('max-width', '900px');
			} else {
				$dialog.removeClass('modal-lg').addClass('modal-md').css('max-width', '540px');
			}
		}

		$(document).on('click', '.btn-preview-doc', function(e) {
			e.preventDefault();
			const url = $(this).data('url');
			const title = $(this).data('title');
			const typeLabel = $(this).data('type');
			const ext = String($(this).data('ext')).toLowerCase();

			$('#docPreviewTitle').text(title);
			$('#docPreviewBadge').text(typeLabel);
			$('#docPreviewExternalLink').attr('href', url);
			$('#docPreviewDownloadBtn').attr('href', url);

			const imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'svg'];
			const officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

			if (ext === 'pdf') {
				setModalSize(true);
				$('#docPreviewBody').html(`
					<div class="rounded overflow-hidden p-1">
						<iframe src="${url}" style="width:100%; height:62vh; border:none; border-radius: 6px;" frameborder="0"></iframe>
					</div>
				`);
			} else if (imageExtensions.includes(ext)) {
				setModalSize(true);
				$('#docPreviewBody').html(`
					<div class="text-center py-2">
						<div class="p-2 d-inline-block mw-100">
							<img src="${url}" class="img-fluid rounded" style="max-height: 58vh; object-fit: contain;" alt="${title}">
						</div>
					</div>
				`);
			} else if (ext === 'csv' || ext === 'txt') {
				setModalSize(true);
				$('#docPreviewBody').html(`
					<div class="p-2">
						<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
							<span class="fw-bold text-primary"><i class="ti ti-table me-1"></i> Data Table Preview (${ext.toUpperCase()})</span>
							<small class="text-muted">Parsed automatically</small>
						</div>
						<div class="table-responsive" style="max-height: 52vh;">
							<div class="text-center py-4" id="csvLoadingState">
								<div class="spinner-border text-primary" role="status"></div>
								<p class="mt-2 text-muted small">Memuat isi berkas...</p>
							</div>
							<table class="table table-bordered table-striped table-hover table-sm d-none mb-0" id="csvPreviewTable">
								<thead class="table-primary" id="csvPreviewThead"></thead>
								<tbody id="csvPreviewTbody"></tbody>
							</table>
						</div>
					</div>
				`);

				fetch(url)
					.then(response => response.text())
					.then(text => {
						const lines = text.trim().split('\n');
						if (lines.length === 0) {
							$('#csvLoadingState').html('<p class="text-muted">Berkas kosong.</p>');
							return;
						}
						let theadHtml = '';
						let tbodyHtml = '';

						lines.forEach((line, index) => {
							const cells = line.split(/,(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/).map(c => c.trim().replace(/^"|"$/g, ''));
							if (index === 0) {
								theadHtml += '<tr>' + cells.map(cell => `<th>${cell}</th>`).join('') + '</tr>';
							} else {
								tbodyHtml += '<tr>' + cells.map(cell => `<td>${cell}</td>`).join('') + '</tr>';
							}
						});

						$('#csvPreviewThead').html(theadHtml);
						$('#csvPreviewTbody').html(tbodyHtml);
						$('#csvLoadingState').addClass('d-none');
						$('#csvPreviewTable').removeClass('d-none');
					})
					.catch(() => {
						$('#csvLoadingState').html('<p class="text-danger small">Gagal memproses pratinjau tabel. Silakan unduh dokumen.</p>');
					});
			} else if (officeExtensions.includes(ext)) {
				const isOfficeExcel = ['xls', 'xlsx'].includes(ext);
				const isOfficeWord = ['doc', 'docx'].includes(ext);
				const isOfficePpt = ['ppt', 'pptx'].includes(ext);

				let officeIconClass = 'ti-file-text text-primary';
				let officeBadgeClass = 'bg-primary';
				let officeTypeName = 'Microsoft Word Document';

				if (isOfficeExcel) {
					officeIconClass = 'ti-file-spreadsheet text-success';
					officeBadgeClass = 'bg-success';
					officeTypeName = 'Microsoft Excel Spreadsheet';
				} else if (isOfficePpt) {
					officeIconClass = 'ti-presentation text-warning';
					officeBadgeClass = 'bg-warning';
					officeTypeName = 'Microsoft PowerPoint Presentation';
				}

				const isLocalhost = ['localhost', '127.0.0.1'].includes(window.location.hostname);

				if (isLocalhost) {
					setModalSize(false);
					$('#docPreviewBody').html(`
						<div class="p-3 text-center">
							<div class="mb-3">
								<i class="ti ${officeIconClass} d-block mx-auto mb-2" style="font-size: 3.5rem;"></i>
								<h6 class="fw-bold mb-1 text-truncate">${title}</h6>
								<span class="badge ${officeBadgeClass} mb-2">${officeTypeName} (.${ext.toUpperCase()})</span>
							</div>
							<div class="alert alert-info border-0 text-start p-3 mb-4">
								<div class="d-flex align-items-start gap-2">
									<i class="ti ti-info-circle text-info fs-5 mt-1 flex-shrink-0"></i>
									<div>
										<strong>Informasi Pratinjau Server Lokal:</strong><br>
										<small class="text-muted">Berkas format Microsoft Office (.${ext.toUpperCase()}) di lingkungan lokal (localhost/127.0.0.1) dapat diunduh atau dibuka langsung di perangkat Anda. Di server produksi (domain publik), pratinjau otomatis aktif via Google Docs Viewer.</small>
									</div>
								</div>
							</div>
							<div class="d-flex align-items-center justify-content-center gap-2">
								<a href="${url}" target="_blank" class="btn btn-outline-primary btn-sm">
									<i class="ti ti-external-link me-1"></i> Buka Berkas
								</a>
								<a href="${url}" download class="btn btn-primary btn-sm">
									<i class="ti ti-download me-1"></i> Unduh Berkas
								</a>
							</div>
						</div>
					`);
				} else {
					setModalSize(true);
					const absoluteUrl = window.location.origin + url;
					const googleDocsUrl = `https://docs.google.com/gview?url=${encodeURIComponent(absoluteUrl)}&embedded=true`;
					$('#docPreviewBody').html(`
						<div class="rounded overflow-hidden p-1">
							<iframe src="${googleDocsUrl}" style="width:100%; height:62vh; border:none; border-radius: 6px;" frameborder="0"></iframe>
						</div>
					`);
				}
			} else {
				setModalSize(false);
				$('#docPreviewBody').html(`
					<div class="text-center py-5 px-3">
						<i class="ti ti-file-description text-primary d-block mb-3" style="font-size: 3.5rem;"></i>
						<h6 class="mb-2 text-truncate">${title}</h6>
						<p class="text-muted small mb-4">Format file (.${ext}) dapat diunduh atau dibuka langsung di jendela baru browser.</p>
						<a href="${url}" target="_blank" class="btn btn-primary btn-sm me-2">
							<i class="ti ti-external-link me-1"></i> Buka di Jendela Baru
						</a>
						<a href="${url}" download class="btn btn-outline-secondary btn-sm">
							<i class="ti ti-download me-1"></i> Unduh Berkas
						</a>
					</div>
				`);
			}

			$('#docPreviewModal').modal('show');
		});
	</script>
@endsection
