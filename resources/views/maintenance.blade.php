<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $brandName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light dark;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(120% 120% at 50% 10%, #fef3c7 0%, #f3f4f6 45%, #e5e7eb 100%);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #111827;
            padding: 1.5rem;
        }
        .card {
            max-width: 480px;
            width: 100%;
            border-radius: 1.5rem;
            backdrop-filter: blur(18px);
            background-color: rgba(255, 255, 255, 0.75);
            box-shadow: 0 25px 50px -12px rgba(251, 191, 36, 0.35);
            padding: 2.5rem;
            text-align: center;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 9999px;
            padding: 0.4rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #b45309;
            background: rgba(251, 191, 36, 0.18);
            margin-bottom: 1.25rem;
        }
        h1 {
            font-size: clamp(1.8rem, 3vw + 1rem, 2.4rem);
            margin: 0;
            margin-bottom: 1rem;
            font-weight: 700;
            letter-spacing: -0.03em;
        }
        p {
            margin: 0 0 1.5rem;
            line-height: 1.6;
            color: #4b5563;
        }
        .contact {
            font-weight: 600;
            color: #9a3412;
            text-decoration: none;
        }
        .contact:hover {
            text-decoration: underline;
        }
        footer {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #6b7280;
        }
        @media (prefers-color-scheme: dark) {
            body {
                background: radial-gradient(100% 100% at 50% 0%, #1f2937 0%, #111827 100%);
                color: #f9fafb;
            }
            .card {
                background-color: rgba(17, 24, 39, 0.9);
                box-shadow: 0 25px 40px -15px rgba(251, 191, 36, 0.2);
            }
            p {
                color: #d1d5db;
            }
            footer {
                color: #9ca3af;
            }
        }
    </style>
</head>
<body>
    <article class="card" role="status" aria-live="polite">
        <span class="badge">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-12a.75.75 0 00-1.5 0v4.25a.75.75 0 00.22.53l2.5 2.5a.75.75 0 101.06-1.06l-2.28-2.28V6z" clip-rule="evenodd" /></svg>
            Mode Pemeliharaan Aktif
        </span>
        <h1>{{ $brandName }}</h1>
        <p>
            Kami sedang melakukan pemeliharaan terjadwal untuk meningkatkan layanan. Silakan kembali beberapa saat lagi.
        </p>
        <p>
            Bila Anda membutuhkan bantuan segera, hubungi tim kami di
            <a class="contact" href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.
        </p>
        <footer>&copy; {{ now()->year }} {{ $brandName }}. Semua hak cipta.</footer>
    </article>
</body>
</html>
