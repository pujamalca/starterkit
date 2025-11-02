<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // FAQ Section
        $this->migrator->add('landing_page.show_faq', true);
        $this->migrator->add('landing_page.faq_title', 'Pertanyaan yang Sering Diajukan');
        $this->migrator->add('landing_page.faq_subtitle', 'Temukan jawaban untuk pertanyaan umum tentang platform kami');

        // Default FAQ data (seed)
        $defaultFaqs = [
            [
                'question' => 'Apa itu Laravel Starter Kit?',
                'answer' => 'Laravel Starter Kit adalah template aplikasi web yang sudah dilengkapi dengan fitur-fitur dasar seperti autentikasi, manajemen pengguna, blog, dan pengaturan aplikasi. Ini membantu Anda memulai proyek Laravel dengan lebih cepat tanpa harus membangun fitur-fitur dasar dari awal.',
            ],
            [
                'question' => 'Bagaimana cara menginstal aplikasi ini?',
                'answer' => 'Anda dapat menginstal aplikasi ini dengan mengikuti langkah-langkah di dokumentasi. Secara singkat: clone repository, jalankan composer install, konfigurasi file .env, generate key aplikasi, jalankan migrasi database, dan jalankan seeder untuk data awal.',
            ],
            [
                'question' => 'Apakah aplikasi ini gratis?',
                'answer' => 'Ya, aplikasi ini adalah open source dan gratis untuk digunakan. Anda dapat menggunakannya untuk proyek pribadi maupun komersial tanpa batasan. Kami sangat menghargai kontribusi dari komunitas untuk terus mengembangkan aplikasi ini.',
            ],
            [
                'question' => 'Fitur apa saja yang tersedia?',
                'answer' => 'Aplikasi ini dilengkapi dengan berbagai fitur seperti autentikasi pengguna, manajemen role dan permission, sistem blog dengan kategori dan tag, manajemen halaman statis, pengaturan aplikasi melalui admin panel, media library, dan masih banyak lagi.',
            ],
            [
                'question' => 'Bagaimana cara mendapatkan dukungan?',
                'answer' => 'Anda dapat mendapatkan dukungan melalui beberapa cara: membaca dokumentasi lengkap di website kami, mengajukan issue di GitHub repository, atau bergabung dengan komunitas kami di Discord. Kami juga menyediakan forum diskusi untuk berbagi pengalaman dengan pengguna lain.',
            ],
            [
                'question' => 'Apakah saya bisa customize aplikasi ini?',
                'answer' => 'Tentu saja! Aplikasi ini dirancang untuk mudah dikustomisasi sesuai kebutuhan Anda. Anda dapat mengubah tampilan, menambah fitur baru, memodifikasi yang sudah ada, atau bahkan mengintegrasikan dengan layanan third-party. Source code sepenuhnya terbuka dan dapat Anda modifikasi.',
            ],
        ];

        $this->migrator->add('landing_page.faqs', json_encode($defaultFaqs));
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('landing_page.show_faq');
        $this->migrator->deleteIfExists('landing_page.faq_title');
        $this->migrator->deleteIfExists('landing_page.faq_subtitle');
        $this->migrator->deleteIfExists('landing_page.faqs');
    }
};
