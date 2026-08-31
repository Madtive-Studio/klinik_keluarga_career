@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.profile.title'))
@section('content')
	@php
		$profile = $candidate->profile;
		$hasSkills = $candidate->skills && $candidate->skills->isNotEmpty();
		$completionFields = ['education_level', 'major', 'university', 'years_of_experience', 'city', 'province', 'expected_salary'];
		$filledCount = collect($completionFields)->filter(function ($field) use ($profile) {
			$value = $profile?->{$field};
			return $value !== null && $value !== '';
		})->count();
		if ($hasSkills) $filledCount++;
		$completionPercent = (int) round(($filledCount / (count($completionFields) + 1)) * 100);
		$selectedProvince = old('province', $profile?->province);
		$selectedCity = old('city', $profile?->city);
		$expectedSalaryValue = old('expected_salary', $profile?->expected_salary);
		$expectedSalaryDisplay = filled($expectedSalaryValue)
			? number_format((int) $expectedSalaryValue, 0, ',', '.')
			: '';
	@endphp
	<section class="section pt-5 profile-page">
		<div class="container">
			@if ($message = Session::get('warning'))
				<div class="alert alert-warning" role="alert">
					<p class="mb-0">{!! $message !!}</p>
				</div>
			@endif
			@if ($message = Session::get('error'))
				<div class="alert alert-danger" role="alert">
					<p class="mb-0">{!! $message !!}</p>
				</div>
			@endif
			@if ($message = Session::get('success'))
				<div class="alert alert-success" role="alert">
					<p class="mb-0">{{ $message }}</p>
				</div>
			@endif

			<div class="row">
				<div class="col-lg-4 mb-4 mb-lg-0">
					<div class="profile-sidebar card border-0 shadow-sm">
						<div class="profile-sidebar__header text-center text-white">
							<div class="profile-sidebar__avatar mx-auto mb-3">
								<i class="mdi mdi-account-circle"></i>
							</div>
							<h5 class="mb-1">{{ $candidate->name }}</h5>
							<p class="mb-0 small opacity-75">{{ $candidate->email }}</p>
						</div>
						<div class="card-body">
							<p class="text-muted small mb-2">{{ __('candidate.profile.subtitle') }}</p>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="small fw-bold">{{ __('candidate.profile.completion') }}</span>
								<span id="profile-completion-percent" class="small text-primary fw-bold">{{ $completionPercent }}%</span>
							</div>
							<div class="progress profile-progress mb-3" style="height: 8px;">
								<div id="profile-completion-bar" class="progress-bar bg-primary" role="progressbar" style="width: {{ $completionPercent }}%;"></div>
							</div>
							<ul class="list-unstyled profile-checklist mb-0">
								<li id="checklist-education" data-section="education" class="{{ filled($profile?->education_level) ? 'is-complete' : '' }}">
									<i class="mdi {{ filled($profile?->education_level) ? 'mdi-check-circle' : 'mdi-circle-outline' }}"></i>
									{{ __('candidate.profile.education') }}
								</li>
								<li id="checklist-experience" data-section="experience" class="{{ filled($profile?->years_of_experience) || $profile?->years_of_experience === 0 ? 'is-complete' : '' }}">
									<i class="mdi {{ filled($profile?->years_of_experience) || $profile?->years_of_experience === 0 ? 'mdi-check-circle' : 'mdi-circle-outline' }}"></i>
									{{ __('candidate.profile.experience') }}
								</li>
								<li id="checklist-location" data-section="location" class="{{ filled($profile?->province) && filled($profile?->city) ? 'is-complete' : '' }}">
									<i class="mdi {{ filled($profile?->province) && filled($profile?->city) ? 'mdi-check-circle' : 'mdi-circle-outline' }}"></i>
									{{ __('candidate.profile.location_preferences') }}
								</li>
								<li id="checklist-skills" data-section="skills" class="{{ $hasSkills ? 'is-complete' : '' }}">
									<i class="mdi {{ $hasSkills ? 'mdi-check-circle' : 'mdi-circle-outline' }}"></i>
									{{ __('candidate.profile.skills') }}
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="col-lg-8">
					<form method="POST" action="{{ route('candidate.my.profile.update') }}" id="profile-form">
						@csrf
						@method('PUT')

						<div class="profile-section-card card border-0 shadow-sm mb-4">
							<div class="card-body p-4">
								<div class="profile-section-card__title">
									<span class="profile-section-card__icon"><i class="mdi mdi-account-cog-outline"></i></span>
									<div>
										<h5 class="mb-0">Informasi Akun</h5>
										<p class="text-muted small mb-0">Kelola nama lengkap, username, dan nomor HP Anda</p>
									</div>
								</div>
								<div class="row mt-4">
									<div class="col-md-6 mb-3">
										<label class="form-label">Nama Lengkap *</label>
										<input type="text" name="name" class="form-control" value="{{ old('name', $candidate->name) }}" required>
										@error('name') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-6 mb-3">
										<label class="form-label">Username *</label>
										<input type="text" name="username" class="form-control" value="{{ old('username', $candidate->username) }}" required>
										@error('username') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-6 mb-3">
										<label class="form-label">Email (Terverifikasi)</label>
										<input type="email" class="form-control bg-light" value="{{ $candidate->email }}" readonly>
									</div>
									<div class="col-md-6 mb-3">
										<label class="form-label">Nomor Telepon *</label>
										<input type="tel" inputmode="numeric" name="phone" class="form-control" value="{{ old('phone', $candidate->phone) }}" required>
										@error('phone') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
								</div>
							</div>
						</div>

						<div class="profile-section-card card border-0 shadow-sm mb-4">
							<div class="card-body p-4">
								<div class="profile-section-card__title">
									<span class="profile-section-card__icon"><i class="mdi mdi-school"></i></span>
									<div>
										<h5 class="mb-0">{{ __('candidate.profile.education') }}</h5>
										<p class="text-muted small mb-0">{{ __('candidate.profile.education_hint') }}</p>
									</div>
								</div>
								<div class="row mt-4">
									<div class="col-md-6 mb-3">
										<label class="form-label">{{ __('candidate.profile.education_level') }} *</label>
										<select name="education_level" class="form-control profile-track-field" data-progress-field required>
											<option value="">{{ __('common.select') }}</option>
											@foreach ($educationLevels as $level)
												<option value="{{ $level->value }}" @selected(old('education_level', $profile?->education_level) === $level->value)>{{ $level->label() }}</option>
											@endforeach
										</select>
										@error('education_level') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-6 mb-3">
										<label class="form-label">{{ __('candidate.profile.major') }}</label>
										<input type="text" name="major" class="form-control profile-track-field" data-progress-field value="{{ old('major', $profile?->major) }}">
									</div>
									<div class="col-md-8 mb-3">
										<label class="form-label">{{ __('candidate.profile.university') }}</label>
										<input type="text" name="university" class="form-control profile-track-field" data-progress-field value="{{ old('university', $profile?->university) }}">
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.gpa') }}</label>
										<input type="number" step="0.01" min="0" max="4" name="gpa" class="form-control profile-track-field" data-progress-field value="{{ old('gpa', $profile?->gpa) }}">
									</div>
								</div>
							</div>
						</div>

						<div class="profile-section-card card border-0 shadow-sm mb-4">
							<div class="card-body p-4">
								<div class="profile-section-card__title">
									<span class="profile-section-card__icon"><i class="mdi mdi-briefcase-outline"></i></span>
									<div>
										<h5 class="mb-0">{{ __('candidate.profile.experience') }}</h5>
										<p class="text-muted small mb-0">{{ __('candidate.profile.experience_hint') }}</p>
									</div>
								</div>
								<div class="row mt-4">
									<div class="col-md-3 mb-3">
										<label class="form-label">{{ __('candidate.profile.years_of_experience') }} *</label>
										<input type="number" min="0" max="50" name="years_of_experience" class="form-control profile-track-field" data-progress-field required value="{{ old('years_of_experience', $profile?->years_of_experience ?? '') }}">
										@error('years_of_experience') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.last_position') }}</label>
										<input type="text" name="last_position" class="form-control profile-track-field" data-progress-field value="{{ old('last_position', $profile?->last_position) }}">
									</div>
									<div class="col-md-5 mb-3">
										<label class="form-label">{{ __('candidate.profile.last_company') }}</label>
										<input type="text" name="last_company" class="form-control profile-track-field" data-progress-field value="{{ old('last_company', $profile?->last_company) }}">
									</div>
								</div>
							</div>
						</div>

						<div class="profile-section-card card border-0 shadow-sm mb-4">
							<div class="card-body p-4">
								<div class="profile-section-card__title">
									<span class="profile-section-card__icon"><i class="mdi mdi-map-marker-outline"></i></span>
									<div>
										<h5 class="mb-0">{{ __('candidate.profile.location_preferences') }}</h5>
										<p class="text-muted small mb-0">{{ __('candidate.profile.location_hint') }}</p>
									</div>
								</div>
								<div class="row mt-4">
									<div class="col-md-6 mb-3">
										<label class="form-label">{{ __('candidate.profile.province') }}</label>
										<select name="province" id="province-select" class="form-control profile-track-field" data-progress-field data-selected="{{ $selectedProvince }}">
											<option value="">{{ __('candidate.profile.select_province') }}</option>
										</select>
										@error('province') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-6 mb-3">
										<label class="form-label">{{ __('candidate.profile.city') }}</label>
										<select name="city" id="city-select" class="form-control profile-track-field" data-progress-field data-selected="{{ $selectedCity }}" disabled>
											<option value="">{{ __('candidate.profile.select_city') }}</option>
										</select>
										@error('city') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-6 mb-3">
										<label class="form-label">{{ __('candidate.profile.expected_salary') }}</label>
										<input type="text"
											inputmode="numeric"
											id="expected_salary_display"
											class="form-control salary-amount-input profile-track-field"
											data-progress-field
											placeholder="{{ __('candidate.profile.expected_salary_placeholder') }}"
											value="{{ $expectedSalaryDisplay }}"
											autocomplete="off">
										<input type="hidden" name="expected_salary" id="expected_salary" value="{{ $expectedSalaryValue }}">
										@error('expected_salary') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
								</div>
							</div>
						</div>

						<div class="profile-section-card card border-0 shadow-sm mb-4">
							<div class="card-body p-4">
								<div class="profile-section-card__title">
									<span class="profile-section-card__icon"><i class="mdi mdi-tag-multiple-outline"></i></span>
									<div>
										<h5 class="mb-0">{{ __('candidate.profile.skills') }}</h5>
										<p class="text-muted small mb-0">{{ __('candidate.profile.skills_hint') }}</p>
									</div>
								</div>
								<div class="row mt-4">
									<div class="col-12 mb-3">
										<label class="form-label fw-semibold">{{ __('candidate.profile.skills_label') }}</label>
										<div class="input-group">
											<input type="text" id="skill-text-input" class="form-control" placeholder="{{ __('candidate.profile.skills_placeholder') }}" autocomplete="off">
											<button type="button" class="btn btn-primary" id="btn-add-skill">
												<i class="mdi mdi-plus me-1"></i>{{ __('candidate.profile.skills_add_btn') }}
											</button>
										</div>
										<small class="text-muted d-block mt-1">
											<i class="mdi mdi-information-outline me-1"></i>Ketik 1 keahlian lalu tekan <strong>Enter</strong> atau klik tombol <strong>Tambah</strong>.
										</small>
										@error('skills') <small class="text-danger">{{ $message }}</small> @enderror
									</div>

									<div class="col-12">
										<label class="form-label fw-semibold mb-2">Daftar Keahlian Anda:</label>
										<div id="skills-badge-list" class="d-flex flex-wrap gap-2 p-3 rounded bg-light border" style="min-height: 55px;">
											@php
												$currentSkills = old('skills', $candidate->skills->pluck('name')->toArray());
											@endphp
											@forelse($currentSkills as $skillName)
												@if(filled($skillName))
													<div class="skill-tag badge bg-primary text-white d-inline-flex align-items-center gap-2 px-3 py-2" style="font-size: 13px; border-radius: 6px;">
														<span>{{ $skillName }}</span>
														<input type="hidden" name="skills[]" value="{{ $skillName }}">
														<button type="button" class="btn-close btn-close-white btn-remove-skill" style="font-size: 9px;" aria-label="Remove"></button>
													</div>
												@endif
											@empty
												<span class="text-muted small italic empty-skill-msg">{{ __('candidate.profile.skills_empty_list') }}</span>
											@endforelse
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="profile-form-actions d-flex justify-content-end">
							<button type="submit" class="btn btn-primary px-4">
								<i class="mdi mdi-content-save-outline me-1"></i>{{ __('candidate.profile.save_profile') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<style>
		.profile-page .profile-sidebar {
			overflow: hidden;
		}

		.profile-sidebar__header {
			background: linear-gradient(135deg, #2f55d4 0%, #4f78ea 100%);
			padding: 2rem 1.5rem 1.5rem;
		}

		.profile-sidebar__avatar {
			width: 84px;
			height: 84px;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.15);
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.profile-sidebar__avatar i {
			font-size: 3.5rem;
			line-height: 1;
		}

		.profile-checklist li {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			padding: 0.45rem 0;
			color: #6c757d;
			font-size: 0.92rem;
			transition: color 0.2s ease;
		}

		.profile-checklist li.is-complete {
			color: #2f55d4;
		}

		.profile-checklist li i {
			font-size: 1.1rem;
			transition: transform 0.2s ease;
		}

		.profile-checklist li.is-complete i {
			transform: scale(1.05);
		}

		.profile-progress .progress-bar {
			transition: width 0.35s ease;
		}

		.profile-section-card__title {
			display: flex;
			align-items: flex-start;
			gap: 0.85rem;
		}

		.profile-section-card__icon {
			width: 42px;
			height: 42px;
			border-radius: 12px;
			background: rgba(47, 85, 212, 0.1);
			color: #2f55d4;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.profile-section-card__icon i {
			font-size: 1.35rem;
			line-height: 1;
		}

		.profile-form-actions {
			position: sticky;
			bottom: 1rem;
			z-index: 5;
		}

		.profile-page .form-control:focus,
		.profile-page select.form-control:focus {
			border-color: #2f55d4;
			box-shadow: 0 0 0 0.15rem rgba(47, 85, 212, 0.15);
		}
	</style>
@endsection

@section('js')
	@php
		$profileI18n = [
			'select_province' => __('candidate.profile.select_province'),
			'select_city' => __('candidate.profile.select_city'),
			'loading_cities' => __('candidate.profile.loading_cities'),
			'load_province_failed' => __('candidate.profile.load_province_failed'),
			'load_city_failed' => __('candidate.profile.load_city_failed'),
		];
	@endphp
	<script>
		const profileI18n = @json($profileI18n);
		const WILAYAH_API = 'https://www.emsifa.com/api-wilayah-indonesia/api';

		document.addEventListener('DOMContentLoaded', function () {
			const profileForm = document.getElementById('profile-form');
			const provinceSelect = document.getElementById('province-select');
			const citySelect = document.getElementById('city-select');
			const provinceMap = new Map();
			const progressBar = document.getElementById('profile-completion-bar');
			const progressPercent = document.getElementById('profile-completion-percent');
			const checklistEducation = document.getElementById('checklist-education');
			const checklistExperience = document.getElementById('checklist-experience');
			const checklistLocation = document.getElementById('checklist-location');
			const progressFieldNames = [
				'education_level',
				'major',
				'university',
				'gpa',
				'years_of_experience',
				'last_position',
				'last_company',
				'province',
				'city',
				'expected_salary',
			];

			function parseSalaryDigits(value) {
				return String(value || '').replace(/\D/g, '');
			}

			function formatSalaryAmount(value) {
				const digits = parseSalaryDigits(value);
				if (!digits) {
					return '';
				}

				return new Intl.NumberFormat('id-ID').format(parseInt(digits, 10));
			}

			function syncSalaryHiddenInput() {
				const displayInput = document.getElementById('expected_salary_display');
				const hiddenInput = document.getElementById('expected_salary');
				if (!displayInput || !hiddenInput) {
					return;
				}

				const digits = parseSalaryDigits(displayInput.value);
				hiddenInput.value = digits;
			}

			function getFieldValue(name) {
				if (name === 'expected_salary') {
					return document.getElementById('expected_salary')?.value || '';
				}

				const field = profileForm.querySelector('[name="' + name + '"]');
				return field ? String(field.value || '').trim() : '';
			}

			function isProgressFieldFilled(name) {
				const value = getFieldValue(name);
				return value !== '';
			}

			function setChecklistState(element, isComplete) {
				if (!element) {
					return;
				}

				element.classList.toggle('is-complete', isComplete);
				const icon = element.querySelector('i');
				if (icon) {
					icon.className = 'mdi ' + (isComplete ? 'mdi-check-circle' : 'mdi-circle-outline');
				}
			}

			function updateLiveProgress() {
				const filledCount = progressFieldNames.filter(isProgressFieldFilled).length;
				const percent = Math.round((filledCount / progressFieldNames.length) * 100);

				if (progressBar) {
					progressBar.style.width = percent + '%';
				}

				if (progressPercent) {
					progressPercent.textContent = percent + '%';
				}

				setChecklistState(checklistEducation, isProgressFieldFilled('education_level'));
				setChecklistState(checklistExperience, isProgressFieldFilled('years_of_experience'));
				setChecklistState(
					checklistLocation,
					isProgressFieldFilled('province') && isProgressFieldFilled('city')
				);
			}

			const salaryDisplayInput = document.getElementById('expected_salary_display');
			if (salaryDisplayInput) {
				salaryDisplayInput.addEventListener('input', function () {
					const digits = parseSalaryDigits(this.value);
					this.value = digits ? formatSalaryAmount(digits) : '';
					syncSalaryHiddenInput();
					updateLiveProgress();
				});
			}

			profileForm.querySelectorAll('.profile-track-field[data-progress-field]').forEach(function (field) {
				field.addEventListener('input', updateLiveProgress);
				field.addEventListener('change', updateLiveProgress);
			});

			profileForm.addEventListener('submit', function () {
				syncSalaryHiddenInput();
			});

			function resetCitySelect(message) {
				citySelect.innerHTML = '<option value="">' + message + '</option>';
				citySelect.disabled = true;
				updateLiveProgress();
			}

			async function loadCities(provinceId, selectedCity) {
				if (!provinceId) {
					resetCitySelect(profileI18n.select_city);
					return;
				}

				resetCitySelect(profileI18n.loading_cities);

				try {
					const response = await fetch(WILAYAH_API + '/regencies/' + provinceId + '.json');
					if (!response.ok) {
						throw new Error('Failed to load cities');
					}

					const cities = await response.json();
					citySelect.innerHTML = '<option value="">' + profileI18n.select_city + '</option>';

					cities.forEach(function (city) {
						const option = document.createElement('option');
						option.value = city.name;
						option.textContent = city.name;
						if (selectedCity && selectedCity === city.name) {
							option.selected = true;
						}
						citySelect.appendChild(option);
					});

					citySelect.disabled = false;
				} catch (error) {
					resetCitySelect(profileI18n.load_city_failed);
				}

				updateLiveProgress();
			}

			async function loadProvinces() {
				const selectedProvince = provinceSelect.dataset.selected || '';

				try {
					const response = await fetch(WILAYAH_API + '/provinces.json');
					if (!response.ok) {
						throw new Error('Failed to load provinces');
					}

					const provinces = await response.json();
					provinceSelect.innerHTML = '<option value="">' + profileI18n.select_province + '</option>';

					provinces.forEach(function (province) {
						provinceMap.set(province.name, province.id);
						const option = document.createElement('option');
						option.value = province.name;
						option.textContent = province.name;
						if (selectedProvince && selectedProvince === province.name) {
							option.selected = true;
						}
						provinceSelect.appendChild(option);
					});

					if (selectedProvince && provinceMap.has(selectedProvince)) {
						await loadCities(provinceMap.get(selectedProvince), citySelect.dataset.selected || '');
					}
				} catch (error) {
					provinceSelect.innerHTML = '<option value="">' + profileI18n.load_province_failed + '</option>';
				}

				updateLiveProgress();
			}

			provinceSelect.addEventListener('change', function () {
				citySelect.dataset.selected = '';
				loadCities(provinceMap.get(this.value), '');
			});

			// Skills Dynamic Tag List
			const skillInput = document.getElementById('skill-text-input');
			const btnAddSkill = document.getElementById('btn-add-skill');
			const skillsList = document.getElementById('skills-badge-list');

			function addSkillFromInput() {
				if (!skillInput || !skillsList) return;
				const val = skillInput.value.trim();
				if (!val) return;

				const existingInputs = Array.from(skillsList.querySelectorAll('input[name="skills[]"]'));
				const existingNames = existingInputs.map(function(i) { return i.value.toLowerCase(); });
				if (existingNames.includes(val.toLowerCase())) {
					skillInput.value = '';
					return;
				}

				const emptyMsg = skillsList.querySelector('.empty-skill-msg');
				if (emptyMsg) emptyMsg.remove();

				const badge = document.createElement('div');
				badge.className = 'skill-tag badge bg-primary text-white d-inline-flex align-items-center gap-2 px-3 py-2';
				badge.style.fontSize = '13px';
				badge.style.borderRadius = '6px';

				const textSpan = document.createElement('span');
				textSpan.textContent = val;

				const hiddenInput = document.createElement('input');
				hiddenInput.type = 'hidden';
				hiddenInput.name = 'skills[]';
				hiddenInput.value = val;

				const removeBtn = document.createElement('button');
				removeBtn.type = 'button';
				removeBtn.className = 'btn-close btn-close-white btn-remove-skill';
				removeBtn.style.fontSize = '9px';
				removeBtn.setAttribute('aria-label', 'Remove');

				badge.appendChild(textSpan);
				badge.appendChild(hiddenInput);
				badge.appendChild(removeBtn);

				skillsList.appendChild(badge);
				skillInput.value = '';
				skillInput.focus();

				updateSkillsProgress();
			}

			if (btnAddSkill && skillInput) {
				btnAddSkill.addEventListener('click', function(e) {
					e.preventDefault();
					addSkillFromInput();
				});

				skillInput.addEventListener('keydown', function(e) {
					if (e.key === 'Enter') {
						e.preventDefault();
						addSkillFromInput();
					}
				});
			}

			if (skillsList) {
				skillsList.addEventListener('click', function(e) {
					if (e.target.classList.contains('btn-remove-skill') || e.target.closest('.btn-remove-skill')) {
						const tag = e.target.closest('.skill-tag');
						if (tag) {
							tag.remove();
							const remaining = skillsList.querySelectorAll('.skill-tag');
							if (remaining.length === 0) {
								skillsList.innerHTML = '<span class="text-muted small italic empty-skill-msg">' + @json(__('candidate.profile.skills_empty_list')) + '</span>';
							}
							updateSkillsProgress();
						}
					}
				});
			}

			function updateSkillsProgress() {
				const skillTags = skillsList ? skillsList.querySelectorAll('.skill-tag') : [];
				const checklist = document.getElementById('checklist-skills');
				if (checklist) {
					const isComplete = skillTags.length > 0;
					checklist.classList.toggle('is-complete', isComplete);
					const icon = checklist.querySelector('i');
					if (icon) {
						icon.className = isComplete ? 'mdi mdi-check-circle' : 'mdi mdi-circle-outline';
					}
				}
				if (typeof updateLiveProgress === 'function') {
					updateLiveProgress();
				}
			}

			loadProvinces();
			updateLiveProgress();
		});
	</script>
@endsection
