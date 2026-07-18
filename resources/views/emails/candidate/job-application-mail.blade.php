@extends('emails.layouts.candidate-transactional')

@section('title', $pageTitle)

@section('content')
    <h2>{{ $heading }}</h2>
    <p>{{ __('emails.greeting', ['name' => $candidate->name]) }}</p>

    @if ($variant === 'application_submitted')
        <p>{!! __('emails.application_submitted.body', [
            'type' => $jobTypeLabel ?? $job->type,
            'title' => $job->title,
            'category' => $job->category?->name ?? '-',
            'batch' => ($job->batch?->code ?? '-') . ' — ' . ($job->batch?->name ?? '-'),
        ]) !!}</p>
        <p>{!! __('emails.application_submitted.status') !!}</p>
    @else
        <p>{!! __('emails.status_updated.body', [
            'type' => $jobTypeLabel ?? $job->type,
            'title' => $job->title,
            'category' => $job->category?->name ?? '-',
            'batch' => ($job->batch?->code ?? '-') . ' — ' . ($job->batch?->name ?? '-'),
        ]) !!}</p>
        <p>{!! __('emails.status_updated.current_status', ['status' => $statusLabel]) !!}</p>
    @endif

    <p>{!! __('emails.check_applications', [
        'link' => '<a href="' . route('candidate.my.applications.index') . '">' . __('emails.my_applications_link') . '</a>',
    ]) !!}</p>
    <p>{{ __('emails.regards') }}<br>{{ __('emails.team') }}</p>
@endsection
