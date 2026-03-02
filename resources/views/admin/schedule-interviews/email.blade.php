<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Undangan Wawancara</title>
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
			<h2 style="font-size: 20px; text-align: center; color: #2F55D4;">Selamat! Kamu Diundang ke Tahap Wawancara</h2>
			<p>Halo <strong>{{ $candidate->name }}</strong>,</p>
			<p>Selamat! Kamu telah lolos proses seleksi untuk posisi <strong>{{ $job->type }}</strong> - <strong>{{ $job->title }}</strong> di departemen <strong>{{ $job->category->name }}</strong> batch <strong>{{ $job->batch->code }} - {{ $job->batch->name }}</strong>.</p>
			<p>Kami mengundang kamu untuk mengikuti wawancara yang dijadwalkan pada:</p>
			<table>
				<tr>
					<th>Judul</th>
					<td>{{ $interview->title }}</td>
				</tr>
				<tr>
					<th>Waktu Mulai</th>
					<td>{{ \Carbon\Carbon::parse($interview->start_datetime)->format('d/m/Y H:i') }}</td>
				</tr>
				<tr>
					<th>Waktu Selesai</th>
					<td>{{ \Carbon\Carbon::parse($interview->end_datetime)->format('d/m/Y H:i') }}</td>
				</tr>
				<tr>
					<th>Durasi</th>
					<td>{{ \Carbon\Carbon::parse($interview->start_datetime)->diffInHours(\Carbon\Carbon::parse($interview->end_datetime)) }} jam</td>
				</tr>
				@if ($interview->is_online)
					<tr>
						<th>Link Zoom/Gmeet</th>
						<td><a href="{{ $interview->link }}" style="color: #2F55D4;">{{ $interview->link }}</a></td>
					</tr>
					<tr>
						<th>Deskripsi</th>
						<td>{{ $interview->description }}</td>
					</tr>
					<tr>
						<th>Catatan</th>
						<td>Pastikan untuk menguji koneksi internetmu dan perangkat sebelum wawancara.</td>
					</tr>
				@else
					<tr>
						<th>Alamat</th>
						<td>{{ $company->address }}</td>
					</tr>
					<tr>
						<th>Deskripsi</th>
						<td>{{ $interview->description }}</td>
					</tr>
					<tr>
						<th>Catatan</th>
						<td>Harap datang tepat waktu ke alamat yang tertera di atas.</td>
					</tr>
				@endif
			</table>
			<p style="margin-top: 20px;">Harap pastikan untuk hadir tepat waktu sesuai jadwal wawancara di atas. Jika kamu memiliki pertanyaan atau kendala, silakan hubungi kami.</p>
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
