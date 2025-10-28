<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::first();

        if (! $author) {
            $this->command->error('Tidak ada user di database. Jalankan UserSeeder terlebih dahulu.');

            return;
        }

        $pages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'content' => '<h2>Selamat Datang di Platform Kami</h2>
<p>Kami adalah perusahaan teknologi yang berdedikasi untuk menyediakan solusi digital terbaik bagi pelanggan kami. Dengan pengalaman lebih dari 10 tahun di industri teknologi informasi, kami telah membantu ratusan perusahaan dalam transformasi digital mereka.</p>

<h3>Visi Kami</h3>
<p>Menjadi pemimpin dalam inovasi teknologi digital yang memberikan dampak positif bagi masyarakat dan bisnis di Indonesia.</p>

<h3>Misi Kami</h3>
<ul>
<li>Menghadirkan solusi teknologi yang inovatif dan terpercaya</li>
<li>Memberikan layanan berkualitas tinggi dengan fokus pada kepuasan pelanggan</li>
<li>Mengembangkan produk yang mudah digunakan dan memberikan nilai tambah</li>
<li>Membangun ekosistem digital yang berkelanjutan</li>
</ul>

<h3>Tim Kami</h3>
<p>Tim kami terdiri dari profesional berpengalaman di bidang teknologi, desain, dan bisnis. Kami percaya bahwa kolaborasi dan inovasi adalah kunci kesuksesan dalam menghadirkan solusi terbaik untuk pelanggan.</p>

<p>Dengan keahlian yang beragam, mulai dari pengembangan software, desain UI/UX, hingga strategi digital marketing, kami siap membantu Anda mencapai tujuan bisnis melalui teknologi.</p>

<h3>Nilai-Nilai Kami</h3>
<ul>
<li><strong>Inovasi:</strong> Kami selalu mencari cara baru untuk meningkatkan produk dan layanan kami</li>
<li><strong>Integritas:</strong> Kami menjalankan bisnis dengan transparansi dan kejujuran</li>
<li><strong>Kolaborasi:</strong> Kami percaya pada kekuatan kerja sama tim</li>
<li><strong>Kualitas:</strong> Kami berkomitmen untuk memberikan hasil terbaik</li>
</ul>',
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => 'Tentang Kami - Solusi Digital Terpercaya',
                'seo_description' => 'Kenali lebih dekat perusahaan kami, visi, misi, dan tim profesional yang siap membantu transformasi digital bisnis Anda.',
                'seo_keywords' => ['tentang kami', 'profil perusahaan', 'teknologi digital', 'solusi IT'],
            ],
            [
                'title' => 'Kontak Kami',
                'slug' => 'kontak',
                'content' => '<h2>Hubungi Kami</h2>
<p>Kami senang mendengar dari Anda! Jika Anda memiliki pertanyaan, saran, atau ingin berdiskusi tentang proyek Anda, jangan ragu untuk menghubungi kami melalui salah satu cara berikut.</p>

<h3>Informasi Kontak</h3>
<p><strong>Alamat Kantor:</strong><br>
Jl. Sudirman No. 123<br>
Jakarta Pusat 10110<br>
Indonesia</p>

<p><strong>Email:</strong><br>
info@perusahaan.com<br>
support@perusahaan.com</p>

<p><strong>Telepon:</strong><br>
+62 21 1234 5678<br>
+62 21 8765 4321</p>

<p><strong>WhatsApp:</strong><br>
+62 812 3456 7890</p>

<h3>Jam Operasional</h3>
<p><strong>Senin - Jumat:</strong> 09.00 - 18.00 WIB<br>
<strong>Sabtu:</strong> 09.00 - 14.00 WIB<br>
<strong>Minggu & Hari Libur:</strong> Tutup</p>

<h3>Media Sosial</h3>
<p>Ikuti kami di media sosial untuk mendapatkan update terbaru:</p>
<ul>
<li>Facebook: @perusahaan.official</li>
<li>Instagram: @perusahaan.id</li>
<li>Twitter: @perusahaan</li>
<li>LinkedIn: PT Perusahaan Indonesia</li>
</ul>

<h3>Formulir Kontak</h3>
<p>Untuk pertanyaan yang lebih spesifik, Anda dapat mengisi formulir kontak di halaman ini dan tim kami akan merespons dalam waktu 1x24 jam.</p>',
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => 'Kontak Kami - Hubungi Tim Support',
                'seo_description' => 'Hubungi kami untuk konsultasi, pertanyaan, atau diskusi proyek. Tim kami siap membantu Anda.',
                'seo_keywords' => ['kontak', 'hubungi kami', 'alamat kantor', 'customer service'],
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => '<h2>Kebijakan Privasi</h2>
<p>Terakhir diperbarui: ' . now()->format('d F Y') . '</p>

<p>Kami di PT Perusahaan Indonesia menghargai privasi Anda dan berkomitmen untuk melindungi informasi pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.</p>

<h3>1. Informasi yang Kami Kumpulkan</h3>
<p>Kami dapat mengumpulkan berbagai jenis informasi, termasuk:</p>
<ul>
<li><strong>Informasi Identitas:</strong> nama, alamat email, nomor telepon</li>
<li><strong>Informasi Akun:</strong> username, password, preferensi</li>
<li><strong>Informasi Transaksi:</strong> riwayat pembelian, metode pembayaran</li>
<li><strong>Informasi Teknis:</strong> alamat IP, jenis browser, sistem operasi</li>
<li><strong>Informasi Penggunaan:</strong> halaman yang dikunjungi, durasi kunjungan</li>
</ul>

<h3>2. Penggunaan Informasi</h3>
<p>Kami menggunakan informasi yang dikumpulkan untuk:</p>
<ul>
<li>Menyediakan dan meningkatkan layanan kami</li>
<li>Memproses transaksi dan mengirimkan konfirmasi</li>
<li>Berkomunikasi dengan Anda tentang layanan kami</li>
<li>Mengirimkan newsletter dan informasi promosi (jika Anda berlangganan)</li>
<li>Menganalisis penggunaan layanan untuk peningkatan</li>
<li>Mencegah penipuan dan aktivitas ilegal</li>
</ul>

<h3>3. Keamanan Data</h3>
<p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang sesuai untuk melindungi informasi pribadi Anda dari akses, penggunaan, atau pengungkapan yang tidak sah.</p>

<h3>4. Berbagi Informasi</h3>
<p>Kami tidak akan menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga. Kami hanya membagikan informasi Anda dalam situasi berikut:</p>
<ul>
<li>Dengan persetujuan eksplisit Anda</li>
<li>Untuk mematuhi kewajiban hukum</li>
<li>Dengan penyedia layanan yang membantu operasi bisnis kami</li>
<li>Dalam transaksi bisnis seperti merger atau akuisisi</li>
</ul>

<h3>5. Cookie</h3>
<p>Kami menggunakan cookie dan teknologi pelacakan serupa untuk meningkatkan pengalaman pengguna. Anda dapat mengatur browser Anda untuk menolak cookie, namun ini dapat mempengaruhi fungsi situs web.</p>

<h3>6. Hak Anda</h3>
<p>Anda memiliki hak untuk:</p>
<ul>
<li>Mengakses informasi pribadi yang kami simpan</li>
<li>Meminta koreksi informasi yang tidak akurat</li>
<li>Meminta penghapusan informasi pribadi Anda</li>
<li>Menolak pengolahan informasi pribadi Anda</li>
<li>Meminta portabilitas data</li>
</ul>

<h3>7. Perubahan Kebijakan</h3>
<p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Kami akan memberi tahu Anda tentang perubahan signifikan melalui email atau pemberitahuan di situs web kami.</p>

<h3>8. Hubungi Kami</h3>
<p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami di privacy@perusahaan.com</p>',
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => 'Kebijakan Privasi - Perlindungan Data Pengguna',
                'seo_description' => 'Baca kebijakan privasi kami untuk memahami bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.',
                'seo_keywords' => ['kebijakan privasi', 'perlindungan data', 'privacy policy', 'keamanan data'],
            ],
            [
                'title' => 'Syarat dan Ketentuan',
                'slug' => 'syarat-ketentuan',
                'content' => '<h2>Syarat dan Ketentuan Penggunaan</h2>
<p>Terakhir diperbarui: ' . now()->format('d F Y') . '</p>

<p>Selamat datang di platform kami. Dengan mengakses dan menggunakan layanan kami, Anda setuju untuk terikat dengan syarat dan ketentuan berikut.</p>

<h3>1. Penerimaan Ketentuan</h3>
<p>Dengan mengakses atau menggunakan layanan kami, Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak setuju, mohon untuk tidak menggunakan layanan kami.</p>

<h3>2. Perubahan Ketentuan</h3>
<p>Kami berhak untuk mengubah atau memperbarui Syarat dan Ketentuan ini kapan saja tanpa pemberitahuan sebelumnya. Perubahan akan berlaku segera setelah dipublikasikan di situs web kami. Penggunaan berkelanjutan Anda setelah perubahan berarti Anda menerima ketentuan yang diperbarui.</p>

<h3>3. Pendaftaran Akun</h3>
<p>Untuk menggunakan fitur tertentu, Anda mungkin perlu membuat akun. Anda setuju untuk:</p>
<ul>
<li>Memberikan informasi yang akurat dan lengkap</li>
<li>Menjaga keamanan password Anda</li>
<li>Bertanggung jawab atas semua aktivitas di akun Anda</li>
<li>Segera memberi tahu kami jika terjadi penggunaan tidak sah</li>
</ul>

<h3>4. Penggunaan Layanan</h3>
<p>Anda setuju untuk menggunakan layanan kami hanya untuk tujuan yang sah dan sesuai dengan ketentuan ini. Anda tidak diperbolehkan:</p>
<ul>
<li>Melanggar hukum atau peraturan yang berlaku</li>
<li>Melanggar hak kekayaan intelektual kami atau pihak lain</li>
<li>Mengirimkan konten yang berbahaya, cabul, atau menyinggung</li>
<li>Mencoba mengakses sistem kami secara tidak sah</li>
<li>Mengganggu atau merusak layanan kami</li>
<li>Menggunakan bot atau metode otomatis tanpa izin</li>
</ul>

<h3>5. Konten Pengguna</h3>
<p>Anda bertanggung jawab penuh atas konten yang Anda unggah atau bagikan melalui layanan kami. Dengan mengunggah konten, Anda memberikan kami lisensi non-eksklusif untuk menggunakan, memodifikasi, dan menampilkan konten tersebut dalam rangka menyediakan layanan.</p>

<h3>6. Kekayaan Intelektual</h3>
<p>Semua konten, fitur, dan fungsi layanan kami, termasuk namun tidak terbatas pada teks, grafik, logo, dan software, adalah milik kami atau pemberi lisensi kami dan dilindungi oleh hukum kekayaan intelektual.</p>

<h3>7. Batasan Tanggung Jawab</h3>
<p>Layanan kami disediakan "sebagaimana adanya" tanpa jaminan apapun. Kami tidak bertanggung jawab atas:</p>
<ul>
<li>Kerugian langsung, tidak langsung, atau konsekuensial</li>
<li>Kehilangan data atau keuntungan</li>
<li>Gangguan layanan atau kesalahan teknis</li>
<li>Konten atau tindakan pengguna lain</li>
</ul>

<h3>8. Ganti Rugi</h3>
<p>Anda setuju untuk mengganti rugi dan membebaskan kami dari klaim, kerugian, atau biaya yang timbul dari pelanggaran Anda terhadap ketentuan ini atau penggunaan layanan yang tidak sah.</p>

<h3>9. Pemutusan</h3>
<p>Kami berhak untuk menangguhkan atau menghentikan akses Anda ke layanan kami kapan saja, dengan atau tanpa alasan, termasuk jika kami yakin Anda melanggar ketentuan ini.</p>

<h3>10. Hukum yang Berlaku</h3>
<p>Syarat dan Ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Republik Indonesia. Setiap perselisihan akan diselesaikan melalui Pengadilan Jakarta Pusat.</p>

<h3>11. Kontak</h3>
<p>Jika Anda memiliki pertanyaan tentang Syarat dan Ketentuan ini, silakan hubungi kami di legal@perusahaan.com</p>',
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => 'Syarat dan Ketentuan - Aturan Penggunaan Layanan',
                'seo_description' => 'Baca syarat dan ketentuan penggunaan layanan kami. Ketahui hak dan kewajiban Anda sebagai pengguna platform kami.',
                'seo_keywords' => ['syarat ketentuan', 'terms of service', 'aturan penggunaan', 'TOS'],
            ],
            [
                'title' => 'Pertanyaan yang Sering Diajukan (FAQ)',
                'slug' => 'faq',
                'content' => '<h2>Pertanyaan yang Sering Diajukan</h2>
<p>Temukan jawaban untuk pertanyaan yang paling sering ditanyakan tentang layanan kami.</p>

<h3>Umum</h3>

<h4>Q: Apa itu platform ini?</h4>
<p>A: Platform kami adalah solusi digital yang dirancang untuk membantu bisnis dalam transformasi digital mereka. Kami menyediakan berbagai layanan dan tools yang memudahkan pengelolaan bisnis secara online.</p>

<h4>Q: Siapa yang dapat menggunakan platform ini?</h4>
<p>A: Platform kami dapat digunakan oleh siapa saja, mulai dari individu, UMKM, hingga perusahaan besar yang ingin meningkatkan efisiensi operasional melalui teknologi digital.</p>

<h4>Q: Apakah layanan ini berbayar?</h4>
<p>A: Kami menyediakan berbagai paket layanan, mulai dari paket gratis dengan fitur terbatas hingga paket premium dengan fitur lengkap. Anda dapat memilih paket yang sesuai dengan kebutuhan bisnis Anda.</p>

<h3>Akun & Keamanan</h3>

<h4>Q: Bagaimana cara mendaftar?</h4>
<p>A: Klik tombol "Daftar" di halaman utama, isi formulir pendaftaran dengan informasi yang diperlukan, dan verifikasi email Anda. Setelah itu, akun Anda siap digunakan.</p>

<h4>Q: Lupa password, bagaimana?</h4>
<p>A: Klik "Lupa Password" di halaman login, masukkan email terdaftar Anda, dan ikuti instruksi yang dikirim ke email untuk mereset password.</p>

<h4>Q: Apakah data saya aman?</h4>
<p>A: Ya, kami sangat serius dalam menjaga keamanan data Anda. Kami menggunakan enkripsi SSL, backup rutin, dan standar keamanan industri terbaik untuk melindungi informasi Anda.</p>

<h3>Pembayaran & Langganan</h3>

<h4>Q: Metode pembayaran apa yang diterima?</h4>
<p>A: Kami menerima berbagai metode pembayaran termasuk transfer bank, kartu kredit/debit, e-wallet (GoPay, OVO, DANA), dan virtual account.</p>

<h4>Q: Bagaimana cara upgrade paket?</h4>
<p>A: Masuk ke dashboard Anda, pilih menu "Langganan", dan pilih paket yang ingin Anda upgrade. Setelah pembayaran berhasil, fitur premium akan langsung aktif.</p>

<h4>Q: Apakah ada periode trial?</h4>
<p>A: Ya, kami menyediakan trial gratis 14 hari untuk paket premium. Anda dapat mencoba semua fitur tanpa perlu memasukkan informasi kartu kredit.</p>

<h4>Q: Bagaimana kebijakan refund?</h4>
<p>A: Kami menyediakan jaminan uang kembali 30 hari untuk paket berbayar. Jika Anda tidak puas dengan layanan kami dalam 30 hari pertama, kami akan mengembalikan uang Anda sepenuhnya.</p>

<h3>Teknis</h3>

<h4>Q: Browser apa yang didukung?</h4>
<p>A: Platform kami mendukung semua browser modern termasuk Chrome, Firefox, Safari, dan Edge versi terbaru.</p>

<h4>Q: Apakah ada aplikasi mobile?</h4>
<p>A: Ya, kami memiliki aplikasi mobile untuk iOS dan Android yang dapat diunduh melalui App Store dan Google Play Store.</p>

<h4>Q: Bagaimana jika mengalami masalah teknis?</h4>
<p>A: Hubungi tim support kami melalui email support@perusahaan.com atau live chat yang tersedia 24/7. Tim kami akan segera membantu menyelesaikan masalah Anda.</p>

<h3>Dukungan</h3>

<h4>Q: Bagaimana cara menghubungi customer support?</h4>
<p>A: Anda dapat menghubungi kami melalui:
<ul>
<li>Email: support@perusahaan.com</li>
<li>Live Chat di website (24/7)</li>
<li>Telepon: +62 21 1234 5678</li>
<li>WhatsApp: +62 812 3456 7890</li>
</ul>
</p>

<h4>Q: Berapa lama waktu respons support?</h4>
<p>A: Kami berusaha merespons setiap pertanyaan dalam waktu maksimal 24 jam. Untuk masalah urgent, live chat kami tersedia 24/7 dengan respons rata-rata kurang dari 5 menit.</p>

<h4>Q: Apakah ada dokumentasi atau tutorial?</h4>
<p>A: Ya, kami menyediakan dokumentasi lengkap, video tutorial, dan knowledge base yang dapat diakses di menu "Bantuan" pada dashboard Anda.</p>

<h3>Masih Punya Pertanyaan?</h3>
<p>Jika pertanyaan Anda tidak terjawab di sini, jangan ragu untuk menghubungi tim support kami. Kami siap membantu Anda!</p>',
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => 'FAQ - Pertanyaan yang Sering Diajukan',
                'seo_description' => 'Temukan jawaban untuk pertanyaan umum tentang layanan, pembayaran, keamanan, dan dukungan kami.',
                'seo_keywords' => ['FAQ', 'pertanyaan', 'bantuan', 'tanya jawab', 'help'],
            ],
        ];

        foreach ($pages as $pageData) {
            // Convert seo_keywords array to comma-separated string
            if (isset($pageData['seo_keywords']) && is_array($pageData['seo_keywords'])) {
                $pageData['seo_keywords'] = implode(',', $pageData['seo_keywords']);
            }

            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                array_merge($pageData, ['author_id' => $author->id])
            );
        }

        $this->command->info('✓ ' . count($pages) . ' halaman statis berhasil dibuat!');
    }
}
