<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran Kamu Berhasil Dikirim</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #2F55D4;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 200px;
            margin: 0 auto;
        }

        .content {
            padding: 20px;
            padding-block: 1em;
            color: #333333;
        }

        .content h2 {
            font-size: 20px;
            text-align: center;
            color: #2F55D4;
        }

        .content p {
            margin-bottom: 10px;
        }

        .content strong {
            color: #2F55D4;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('logo-white.png') }}" alt="" width="200">
        </div>
        <div class="content">
            <h2>Lamaran Kamu Berhasil Dikirim</h2>
            <p>Halo <strong>{{ $candidate->name }}</strong>,</p>
            <p>Terima kasih telah melamar posisi <strong>{{ $job->type }}</strong> - <strong>{{ $job->title }}</strong> di departemen <strong>{{ $job->category->name }}</strong> batch <strong>{{ $job->batch->code }} - {{ $job->batch->name }}</strong>.</p>
            <p>Saat ini, lamaran kamu berstatus <strong style="color: #2F55D4;">SEDANG DILAMAR</strong>.</p>
            <p>Jangan lupa untuk selalu mengecek status lamaran kamu di <a href="{{ route('candidate.my.applications.index') }}">madtive.com</a>.</p>
            <p>Salam, <br>
                Tim Madtive Studio</p>
        </div>
    </div>
</body>

</html>