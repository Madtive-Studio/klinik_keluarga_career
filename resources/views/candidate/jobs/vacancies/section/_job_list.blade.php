@forelse ($jobs as $key => $job)
    <div class="col-lg-12 mt-4 pt-2">
        <div class="job-list-box border rounded">
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col-lg-2">
                        <div class="company-logo-img">
                            <img src="{{ asset('assets/candidate/images/job-placeholder.png') }}" width="100" alt="" class="img-fluid mx-auto d-block rounded">
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-9">
                        <div class="job-list-desc">
                            <h6 class="mb-2"><a href="{{ route('candidate.jobs.vacancies.show' , $job->uuid) }}" class="text-dark">{{ $job->code }} - {{ $job->title }}</a></h6>
                            <p class="text-muted mb-0">{{ $job->category->name }}</p>
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item me-3">
                                    <p class="text-muted mb-0"><i class="mdi mdi-map-marker me-2"></i>Cianjur, Jawa Barat</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="job-list-button-sm text-end">
                            <span class="badge badge-success">{{ $job->type }}</span>
                            <div class="mt-3">
                                <a href="{{ route('candidate.jobs.vacancies.apply', $job->uuid) }}" class="btn btn-sm btn-primary">
                                    Lamar Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="text-center mx-auto mt-3">Tidak ada data</p>
@endforelse