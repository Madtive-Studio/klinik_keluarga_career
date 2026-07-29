@forelse ($jobs as $key => $job)
    @php
        $salaryDisplay = $job->is_show_salary ? $job->salary_display : '-';
        $minEducation = $job->relationLoaded('criteria') && $job->criteria
            ? \App\Enums\EducationLevel::labelOf($job->criteria->min_education)
            : '-';
        $jobTypeLabel = \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type;
    @endphp
    <div class="col-lg-12 mt-4 pt-2">
        <div class="job-list-box border rounded">

            {{-- MOBILE LAYOUT --}}
            <div class="p-3 d-md-none">
                <div class="row">
                    <div class="col-4">
                        <div class="company-logo-img">
                            <a href="{{ route('candidate.jobs.vacancies.show' , $job->uuid) }}">
                                <img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="img-fluid mx-auto d-block rounded" style="max-width: 100px;">
                            </a>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="job-list-desc">
                            <h6 class="mb-2"><a href="{{ route('candidate.jobs.vacancies.show' , $job->uuid) }}" class="text-dark">{{ $job->code }} - {{ $job->title }}</a></h6>
                            <p class="text-muted mb-0">{{ $job->category->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-top">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @if ($job->is_show_salary)
                            <small class="text-muted"><i class="mdi mdi-currency-usd me-1"></i>{{ $salaryDisplay }}</small>
                        @endif
                        <small class="text-muted"><i class="mdi mdi-school me-1"></i>{{ __('candidate.jobs.min_education') }} {{ $minEducation }}</small>
                        <small class="text-muted"><i class="mdi mdi-map-marker me-1"></i>Cianjur, Jawa Barat</small>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-success">{{ $jobTypeLabel }}</span>
                        <a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="btn btn-sm btn-primary">
                            {{ __('candidate.jobs.apply_now') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- DESKTOP LAYOUT --}}
            <div class="p-3 d-none d-md-block">
                <div class="row align-items-center">
                    <div class="col-lg-2">
                        <div class="company-logo-img">
                            <a href="{{ route('candidate.jobs.vacancies.show' , $job->uuid) }}">
                                <img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="img-fluid mx-auto d-block rounded" style="max-width: 100px;">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-9">
                        <div class="job-list-desc">
                            <h6 class="mb-2"><a href="{{ route('candidate.jobs.vacancies.show' , $job->uuid) }}" class="text-dark">{{ $job->code }} - {{ $job->title }}</a></h6>
                            <p class="text-muted mb-0">{{ $job->category->name }}</p>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @if ($job->is_show_salary)
                                    <small class="text-muted"><i class="mdi mdi-currency-usd me-1"></i>{{ $salaryDisplay }}</small>
                                @endif
                                <small class="text-muted"><i class="mdi mdi-school me-1"></i>{{ __('candidate.jobs.min_education') }} {{ $minEducation }}</small>
                                <small class="text-muted"><i class="mdi mdi-map-marker me-1"></i>Cianjur, Jawa Barat</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="text-end">
                            <span class="badge bg-success">{{ $jobTypeLabel }}</span>
                            <div class="mt-2">
                                <a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="btn btn-sm btn-primary">
                                    {{ __('candidate.jobs.apply_now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@empty
    <p class="text-center mx-auto mt-3">{{ __('common.no_data') }}</p>
@endforelse