<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
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

        .footer {
            background-color: #2F55D4;
            padding: 20px;
            text-align: center;
            color: #ffffff;
            margin-top: 20px;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/logo/letter-logo-white.png') }}" alt="Klinik Keluarga" width="200" style="display: block; margin: 0 auto;">
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p>{{ __('emails.footer', ['year' => date('Y')]) }}</p>
        </div>
    </div>
</body>

</html>
