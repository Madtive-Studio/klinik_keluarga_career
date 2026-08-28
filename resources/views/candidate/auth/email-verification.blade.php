<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ __('emails.activation.title') }}</title>
	<style>
		body {
			font-family: 'Arial', sans-serif;
		}
	</style>
</head>

<body style="background-color: #f9f9f9; margin: 0; padding: 20px;">
	<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); overflow: hidden;">
		<div style="background-color: #2F55D4; padding: 20px; text-align: center;">
			<img src="{{ isset($message) ? $message->embed(public_path('assets/logo/letter-logo-white.png')) : asset('assets/logo/letter-logo-white.png') }}" width="200" alt="Klinik Keluarga" style="margin: 0 auto; display: block;">
		</div>
		<div style="padding-left: 20px; padding-right: 20px; padding-block: 1em; color: #333333;">
			<h2 style="font-size: 20px; text-align: center; color: #2F55D4;">{{ __('emails.activation.heading') }}</h2>
			<p>{{ __('emails.greeting', ['name' => $candidate->name]) }}</p>
			<p>{{ __('emails.activation.thanks') }}</p>
			<p>{{ __('emails.activation.instruction') }}</p>

			<div style="text-align: center; margin-top: 20px;">
				<a href="{{ $verificationUrl }}" style="display: inline-block; padding: 12px 24px; background-color: #2F55D4; color: #ffffff; text-decoration: none; font-size: 16px; border-radius: 5px;">{{ __('emails.activation.button') }}</a>
			</div>

			<p>{{ __('emails.activation.or_link') }}</p>
			<a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>

			<p style="margin-top: 20px; color: #666666;">{{ __('emails.activation.ignore_note') }}</p>
			<p>{{ __('emails.regards') }}</p>
			<p><strong>{{ __('emails.team') }}</strong></p>
		</div>
		<div style="background-color: #f1f1f1; padding: 10px; text-align: center; color: #777; font-size: 12px;">
			{{ __('emails.footer', ['year' => now()->year]) }}
		</div>
	</div>
</body>

</html>
