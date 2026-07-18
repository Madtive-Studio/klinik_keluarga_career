<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ __('emails.interview.title') }}</title>
	<style>
		body {
			font-family: 'Arial', sans-serif;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		table th,
		table td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}

		table th {
			background-color: #f2f2f2;
		}
	</style>
</head>

<body style="background-color: #f9f9f9; margin: 0; padding: 20px;">
	<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); overflow: hidden;">
		<div style="background-color: #2F55D4; padding: 20px; text-align: center;">
			<img src="{{ asset('logo-white.png') }}" width="200" alt="Logo Madtive Studio" style="margin: 0 auto;">
		</div>
		<div style="padding-left: 20px; padding-right: 20px; padding-block: 1em; color: #333333;">
			<h2 style="font-size: 20px; text-align: center; color: #2F55D4;">{{ __('emails.interview.heading') }}</h2>
			<p>{{ __('emails.greeting', ['name' => $candidate->name]) }}</p>
			@php
				$jobTypeLabel = \App\Enums\JobType::tryFrom($job->type)?->getLabel() ?? $job->type;
			@endphp
			<p>{!! __('emails.interview.intro', [
				'type' => $jobTypeLabel,
				'title' => $job->title,
				'category' => $job->category->name,
				'batch' => $job->batch->code . ' - ' . $job->batch->name,
			]) !!}</p>
			<p>{{ __('emails.interview.schedule_intro') }}</p>
			<table>
				<tr>
					<th>{{ __('emails.interview.label_title') }}</th>
					<td>{{ $interview->title }}</td>
				</tr>
				<tr>
					<th>{{ __('emails.interview.label_start') }}</th>
					<td>{{ \Carbon\Carbon::parse($interview->start_datetime)->format('d/m/Y H:i') }}</td>
				</tr>
				<tr>
					<th>{{ __('emails.interview.label_end') }}</th>
					<td>{{ \Carbon\Carbon::parse($interview->end_datetime)->format('d/m/Y H:i') }}</td>
				</tr>
				<tr>
					<th>{{ __('emails.interview.label_duration') }}</th>
					<td>{{ __('emails.interview.duration_hours', ['hours' => \Carbon\Carbon::parse($interview->start_datetime)->diffInHours(\Carbon\Carbon::parse($interview->end_datetime))]) }}</td>
				</tr>
				@if ($interview->is_online)
					<tr>
						<th>{{ __('emails.interview.label_link') }}</th>
						<td><a href="{{ $interview->link }}" style="color: #2F55D4;">{{ $interview->link }}</a></td>
					</tr>
					<tr>
						<th>{{ __('emails.interview.label_description') }}</th>
						<td>{{ $interview->description }}</td>
					</tr>
					<tr>
						<th>{{ __('emails.interview.label_note') }}</th>
						<td>{{ __('emails.interview.online_note') }}</td>
					</tr>
				@else
					<tr>
						<th>{{ __('emails.interview.label_address') }}</th>
						<td>{{ $company->address }}</td>
					</tr>
					<tr>
						<th>{{ __('emails.interview.label_description') }}</th>
						<td>{{ $interview->description }}</td>
					</tr>
					<tr>
						<th>{{ __('emails.interview.label_note') }}</th>
						<td>{{ __('emails.interview.offline_note') }}</td>
					</tr>
				@endif
			</table>
			<p style="margin-top: 20px;">{{ __('emails.interview.footer_note') }}</p>
			<p style="margin-top: 20px; color: #666666;">{{ __('emails.interview.ignore_note') }}</p>
			<p>{{ __('emails.regards') }}</p>
			<p><strong>{{ __('emails.team') }}</strong></p>
		</div>
		<div style="background-color: #f1f1f1; padding: 10px; text-align: center; color: #777; font-size: 12px;">
			{{ __('emails.footer', ['year' => now()->year]) }}
		</div>
	</div>
</body>

</html>
