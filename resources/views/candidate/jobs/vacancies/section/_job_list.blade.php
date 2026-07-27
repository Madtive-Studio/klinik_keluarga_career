@forelse ($jobs as $key => $job)
    @php
        $salaryDisplay = $job->is_show_salary ? $job->salary_display : '-';
        $minEducation = $job->relationLoaded('criteria') && $job->criteria
            ? \App\Enums\EducationLevel::labelOf($job->criteria->min_education)
            : '-';
    @endphp
    <div class="col-lg-12 mt-4 pt-2">
        <div class="job-list-box border rounded">
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col-lg-2">
                        <div class="company-logo-img">
                            <a href="{{ route('candidate.jobs.vacancies.show' , $job->uuid) }}">
                                <img src="{{ $job->image_url }}" width="100" alt="{{ $job->title }}" class="img-fluid mx-auto d-block rounded">
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
                        <div class="job-list-button-sm text-end">
                            @php
                                $jobTypeLabel = \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type;
                            @endphp
                            <span class="badge bg-success">{{ $jobTypeLabel }}</span>
                            <div class="mt-3">
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