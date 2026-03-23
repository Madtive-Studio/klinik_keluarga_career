<?php

namespace App\Http\Controllers\Candidate\Jobs;

use App\Http\Controllers\Controller;
use App\Services\VacancyService;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function __construct(
        private VacancyService $service
    ) {}

    /**
     * Daftar semua lowongan pekerjaan
     * GET /candidate/jobs/vacancies
     */
    public function index(Request $request)
    {
        $data = $this->service->getVacancyList(
            $request->get('q'),
            $request->get('kategori'),
            $request->get('jenis'),
        );

        return view('candidate.jobs.vacancies.index', $data);
    }

    /**
     * Detail satu lowongan pekerjaan
     * GET /candidate/jobs/vacancies/{uuid}
     */
    public function show(string $uuid)
    {
        $data = $this->service->getVacancyDetail($uuid);

        return view('candidate.jobs.vacancies.detail', $data);
    }
}