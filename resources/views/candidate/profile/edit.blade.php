@extends('candidate.layouts.main', ['navbarType' => 'candidate'])
@section('title', __('candidate.profile.title'))
@section('content')
	<section class="section pt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-10">
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

					<div class="card border rounded">
						<div class="card-body p-4">
							<h4 class="mb-1">{{ __('candidate.profile.title') }}</h4>
							<p class="text-muted">{{ __('candidate.profile.subtitle') }}</p>

							<form method="POST" action="{{ route('candidate.my.profile.update') }}">
								@csrf
								@method('PUT')

								<h5 class="mt-4 mb-3">{{ __('candidate.profile.education') }}</h5>
								<div class="row">
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.education_level') }} *</label>
										<select name="education_level" class="form-control" required>
											<option value="">{{ __('common.select') }}</option>
											@foreach ($educationLevels as $level)
												<option value="{{ $level->value }}" @selected(old('education_level', $candidate->profile?->education_level) === $level->value)>{{ $level->label() }}</option>
											@endforeach
										</select>
										@error('education_level') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.major') }}</label>
										<input type="text" name="major" class="form-control" value="{{ old('major', $candidate->profile?->major) }}">
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.university') }}</label>
										<input type="text" name="university" class="form-control" value="{{ old('university', $candidate->profile?->university) }}">
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.gpa') }}</label>
										<input type="number" step="0.01" min="0" max="4" name="gpa" class="form-control" value="{{ old('gpa', $candidate->profile?->gpa) }}">
									</div>
								</div>

								<h5 class="mt-2 mb-3">{{ __('candidate.profile.experience') }}</h5>
								<div class="row">
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.years_of_experience') }} *</label>
										<input type="number" min="0" max="50" name="years_of_experience" class="form-control" required value="{{ old('years_of_experience', $candidate->profile?->years_of_experience ?? 0) }}">
										@error('years_of_experience') <small class="text-danger">{{ $message }}</small> @enderror
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.last_position') }}</label>
										<input type="text" name="last_position" class="form-control" value="{{ old('last_position', $candidate->profile?->last_position) }}">
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.last_company') }}</label>
										<input type="text" name="last_company" class="form-control" value="{{ old('last_company', $candidate->profile?->last_company) }}">
									</div>
								</div>

								<h5 class="mt-2 mb-3">{{ __('candidate.profile.location_preferences') }}</h5>
								<div class="row">
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.city') }}</label>
										<input type="text" name="city" class="form-control" value="{{ old('city', $candidate->profile?->city) }}">
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.province') }}</label>
										<input type="text" name="province" class="form-control" value="{{ old('province', $candidate->profile?->province) }}">
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.expected_salary') }}</label>
										<input type="number" min="0" name="expected_salary" class="form-control" value="{{ old('expected_salary', $candidate->profile?->expected_salary) }}">
									</div>
									<div class="col-md-4 mb-3">
										<label class="form-label">{{ __('candidate.profile.availability_date') }}</label>
										<input type="date" name="availability_date" class="form-control" value="{{ old('availability_date', optional($candidate->profile?->availability_date)->format('Y-m-d')) }}">
									</div>
								</div>

								<h5 class="mt-2 mb-3">{{ __('candidate.profile.skill') }}</h5>
								<div id="skills-wrapper">
									@php
										$skills = old('skills', $candidate->skills->map(fn ($skill) => ['name' => $skill->name, 'level' => $skill->level])->toArray());
										if ($skills === []) {
											$skills = [['name' => '', 'level' => 'basic']];
										}
									@endphp
									@foreach ($skills as $index => $skill)
										<div class="row skill-row mb-2">
											<div class="col-md-7">
												<input type="text" name="skills[{{ $index }}][name]" class="form-control" placeholder="{{ __('candidate.profile.skill_name_placeholder') }}" value="{{ $skill['name'] ?? '' }}">
											</div>
											<div class="col-md-4">
												<select name="skills[{{ $index }}][level]" class="form-control">
													@foreach ($skillLevels as $level)
														<option value="{{ $level }}" @selected(($skill['level'] ?? 'basic') === $level)>{{ ucfirst($level) }}</option>
													@endforeach
												</select>
											</div>
											<div class="col-md-1 d-flex align-items-center">
												<button type="button" class="btn btn-sm btn-outline-danger remove-skill">&times;</button>
											</div>
										</div>
									@endforeach
								</div>
								<button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-skill">{{ __('candidate.profile.add_skill_btn') }}</button>

								<div class="mt-4">
									<button type="submit" class="btn btn-primary">{{ __('candidate.profile.save_profile') }}</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@section('js')
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			let skillIndex = {{ count($skills) }};
			const wrapper = document.getElementById('skills-wrapper');

			document.getElementById('add-skill').addEventListener('click', function () {
				const row = document.createElement('div');
				row.className = 'row skill-row mb-2';
				row.innerHTML = `
					<div class="col-md-7">
						<input type="text" name="skills[${skillIndex}][name]" class="form-control" placeholder="Nama skill">
					</div>
					<div class="col-md-4">
						<select name="skills[${skillIndex}][level]" class="form-control">
							@foreach ($skillLevels as $level)
								<option value="{{ $level }}">{{ ucfirst($level) }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-1 d-flex align-items-center">
						<button type="button" class="btn btn-sm btn-outline-danger remove-skill">&times;</button>
					</div>
				`;
				wrapper.appendChild(row);
				skillIndex++;
			});

			wrapper.addEventListener('click', function (event) {
				if (event.target.classList.contains('remove-skill')) {
					const rows = wrapper.querySelectorAll('.skill-row');
					if (rows.length > 1) {
						event.target.closest('.skill-row').remove();
					}
				}
			});
		});
	</script>
@endsection
