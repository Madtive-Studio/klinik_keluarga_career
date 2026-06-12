<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Klinik Keluarga Career</title>
    <link rel="stylesheet" href="{{ asset('assets/candidate/css/bootstrap.min.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .error-card {
            max-width: 480px;
            width: 100%;
            text-align: center;
            padding: 2.5rem 2rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }
        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: #0d6efd;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #212529;
        }
        .error-message {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <img src="{{ asset('assets/logo/letter-logo.png') }}" alt="Klinik Keluarga" width="180" class="mb-4">
        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
        <a href="{{ url('/candidate') }}" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</body>
</html>
