@extends('emails.layouts.candidate-transactional')

@section('title', $pageTitle)

@section('content')
    <h2>{{ $heading }}</h2>
    <p>Halo <strong>{{ $candidate->name }}</strong>,</p>

    @if ($variant === 'application_submitted')
        <p>Terima kasih telah melamar posisi <strong>{{ $job->type }}</strong> — <strong>{{ $job->title }}</strong> di departemen <strong>{{ $job->category?->name ?? '-' }}</strong> batch <strong>{{ $job->batch?->code ?? '-' }} — {{ $job->batch?->name ?? '-' }}</strong>.</p>
        <p>Saat ini, lamaran kamu berstatus <strong style="color: #2F55D4;">SEDANG DILAMAR</strong>.</p>
    @else
        <p>Status lamaran kamu untuk posisi <strong>{{ $job->type }}</strong> — <strong>{{ $job->title }}</strong> di departemen <strong>{{ $job->category?->name ?? '-' }}</strong> batch <strong>{{ $job->batch?->code ?? '-' }} — {{ $job->batch?->name ?? '-' }}</strong> telah diperbarui.</p>
        <p>Status saat ini: <strong style="color: #2F55D4;">{{ $statusLabel }}</strong>.</p>
    @endif

    <p>Jangan lupa untuk selalu mengecek status lamaran kamu di <a href="{{ route('candidate.my.applications.index') }}">lamaran saya</a>.</p>
    <p>Salam,<br>Tim Madtive Studio</p>
@endsection
