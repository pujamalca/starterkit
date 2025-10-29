<x-mail::message>
# Komentar Baru di Artikel Anda 💬

Halo!

Ada komentar baru di artikel **{{ $postTitle }}** dari **{{ $commenterName }}**.

## Isi Komentar:

<x-mail::panel>
{{ $commentContent }}
</x-mail::panel>

<div style="color: #6b7280; font-size: 14px; margin-top: 12px;">
📅 Diposting pada: {{ $commentDate }}
</div>

<x-mail::button :url="$postUrl">
Lihat Komentar
</x-mail::button>

## Kelola Komentar

Anda dapat memoderasi komentar ini melalui admin panel untuk menyetujui, membalas, atau menghapusnya.

Terima kasih telah berkontribusi di {{ config('app.name') }}!

Salam,<br>
Tim {{ config('app.name') }}
</x-mail::message>
