<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Status Lamaran Kamu: Tidak Sesuai</title>
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
			<h2 style="font-size: 20px; text-align: center; color: #FF0000;">Status Lamaran Kamu: Tidak Sesuai</h2>
			<p>Halo <strong>{{ $candidate->name }}</strong>,</p>
			<p>Terima kasih atas minat kamu untuk melamar posisi <strong>{{ $job->type }}</strong> - <strong>{{ $job->title }}</strong> di departemen <strong>{{ $job->category->name }}</strong> batch
				<strong>{{ $job->batch->code }} - {{ $job->batch->name }}</strong>.
			</p>
			<p>Namun, setelah kami tinjau, kami merasa bahwa lamaran kamu belum sesuai dengan kriteria yang kami cari untuk posisi tersebut.</p>
			<p>Kami menghargai usaha kamu dan berharap kamu dapat menemukan peluang yang lebih sesuai.</p>

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
