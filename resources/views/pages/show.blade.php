<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->seo_title ?? $page->title }}</title>
    @if ($page->seo_description)
        <meta name="description" content="{{ $page->seo_description }}">
    @endif
    @if ($page->canonical_url)
        <link rel="canonical" href="{{ $page->canonical_url }}">
    @endif
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.7; margin: 0; padding: 2rem; background-color: #f9fafb;">
    <main style="max-width: 760px; margin: 0 auto; background: #fff; padding: 3rem; border-radius: 1.5rem; box-shadow: 0 20px 45px rgba(15,23,42,0.08);">
        <header style="margin-bottom: 2rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: .5rem; color: #0f172a;">{{ $page->title }}</h1>
            @if ($page->published_at)
                <p style="color: #64748b; font-size: .95rem;">
                    Dipublikasikan {{ $page->published_at->translatedFormat('d F Y') }}
                </p>
            @endif
        </header>

        <article style="color: #1e293b; font-size: 1.05rem;">
            {!! $page->content !!}
        </article>
    </main>
</body>
</html>
