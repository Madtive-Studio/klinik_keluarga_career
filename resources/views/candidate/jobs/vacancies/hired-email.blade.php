<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Status Lamaran Kamu: Diterima</title>
	<style>
		body {
			font-family: 'Arial', sans-serif;
		}
	</style>
</head>

<body style="background-color: #f9f9f9; margin: 0; padding: 20px;">
	<div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); overflow: hidden;">
		<div style="background-color: #2F55D4; padding: 20px; text-align: center;">
			<img src="{{ asset('logo-white.png') }}" width="200" alt="" style="margin: 0 auto;">
		</div>
		<div style="padding-left: 20px; padding-right: 20px; padding-block: 1em; color: #333333;">
			<h2 style="font-size: 20px; text-align: center; color: #2F55D4;">Status Lamaran Kamu: Diterima</h2>
			<p>Halo <strong>{{ $candidate->name }}</strong>,</p>
			<p>Selamat! Kamu telah diterima untuk posisi <strong>{{ $job->type }}</strong> - <strong>{{ $job->title }}</strong> di departemen <strong>{{ $job->category->name }}</strong> batch
				<strong>{{ $job->batch->code }} - {{ $job->batch->name }}</strong>
				.
			</p>
			<p>Tim kami akan segera menghubungi kamu untuk informasi lebih lanjut mengenai proses onboarding.</p>

			<p style="margin-top: 20px; color: #666666;">Jika Kamu tidak mendaftar di Karir | Madtive Studio, abaikan email ini.</p>
			<p>Salam,</p>
			<p><strong>Madtive Studio</strong></p>
		</div>
		<div style="background-color: #f1f1f1; padding: 10px; text-align: center; color: #777; font-size: 12px;">
			&copy; {{ now()->year }} Madtive Studio. All rights reserved.
		</div>
	</div>
</body>

</html>
