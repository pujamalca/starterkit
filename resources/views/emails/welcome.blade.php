<x-mail::message>
# Selamat Datang, {{ $userName }}! 👋

Terima kasih telah bergabung dengan **{{ config('app.name') }}**. Kami sangat senang memiliki Anda sebagai bagian dari komunitas kami!

## Apa Selanjutnya?

Berikut beberapa hal yang bisa Anda lakukan:

### 📚 Jelajahi Blog
Baca artikel dan tutorial terbaru dari kami.

<x-mail::button :url="$blogUrl">
Kunjungi Blog
</x-mail::button>

### 🔐 Login ke Dashboard
Akses panel admin untuk mengelola konten Anda.

<x-mail::button :url="$loginUrl" color="success">
Login Sekarang
</x-mail::button>

## Butuh Bantuan?

Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi kami. Tim support kami siap membantu Anda!

<x-mail::panel>
💡 **Tips**: Jelajahi fitur-fitur yang tersedia di admin panel untuk memaksimalkan pengalaman Anda.
</x-mail::panel>

Salam hangat,<br>
Tim {{ config('app.name') }}
</x-mail::message>
