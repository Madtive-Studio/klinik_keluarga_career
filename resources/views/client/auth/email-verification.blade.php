<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Verifikasi Email Kamu</title>
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
			<h2 style="font-size: 20px; text-align: center; color: #2F55D4;">Verifikasi Email Kamu</h2>
			<p>Halo <strong>{{ $candidate->name }}</strong>,</p>
			<p>Terima kasih telah mendaftar di <strong>Karir | Madtive Studio</strong></p>
			<p>Klik tombol di bawah ini untuk memverifikasi email Kamu:</p>

			<div style="text-align: center; margin-top: 20px;">
				<a href="{{ $verificationUrl }}" style="display: inline-block; padding: 12px 24px; background-color: #2F55D4; color: #ffffff; text-decoration: none; font-size: 16px; border-radius: 5px;">Verifikasi Email</a>
			</div>

			<p>Atau gunakan link berikut :</p>
			<a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>

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
