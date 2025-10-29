<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IndonesianContentSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user untuk dijadikan author
        if (User::count() < 3) {
            User::factory()->count(3)->create();
        }

        $authors = User::inRandomOrder()->take(5)->get();

        $this->seedCategories();
        $this->seedTags();
        $this->seedPosts($authors);
        $this->seedPages($authors);
    }

    protected function seedCategories(): void
    {
        if (Category::exists()) {
            $this->command->info('Kategori sudah ada, melewati...');
            return;
        }

        $categories = [
            [
                'name' => 'Teknologi',
                'description' => 'Artikel seputar teknologi, gadget, dan inovasi terbaru',
                'icon' => 'heroicon-o-cpu-chip',
                'color' => '#3B82F6',
                'is_featured' => true,
            ],
            [
                'name' => 'Bisnis',
                'description' => 'Tips dan strategi bisnis, keuangan, dan entrepreneurship',
                'icon' => 'heroicon-o-briefcase',
                'color' => '#10B981',
                'is_featured' => true,
            ],
            [
                'name' => 'Lifestyle',
                'description' => 'Gaya hidup, kesehatan, dan tips kehidupan sehari-hari',
                'icon' => 'heroicon-o-heart',
                'color' => '#F59E0B',
                'is_featured' => true,
            ],
            [
                'name' => 'Pendidikan',
                'description' => 'Artikel edukatif, tutorial, dan pembelajaran',
                'icon' => 'heroicon-o-academic-cap',
                'color' => '#8B5CF6',
                'is_featured' => false,
            ],
            [
                'name' => 'Travel',
                'description' => 'Destinasi wisata, tips perjalanan, dan petualangan',
                'icon' => 'heroicon-o-map-pin',
                'color' => '#EF4444',
                'is_featured' => false,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $index => $categoryData) {
            $createdCategories[] = Category::create(array_merge($categoryData, [
                'is_active' => true,
                'sort_order' => ($index + 1) * 10,
            ]));
        }

        // Buat sub-kategori
        $subCategories = [
            ['name' => 'Programming', 'parent' => 'Teknologi', 'icon' => 'heroicon-o-code-bracket'],
            ['name' => 'AI & Machine Learning', 'parent' => 'Teknologi', 'icon' => 'heroicon-o-sparkles'],
            ['name' => 'Startup', 'parent' => 'Bisnis', 'icon' => 'heroicon-o-rocket-launch'],
            ['name' => 'Investasi', 'parent' => 'Bisnis', 'icon' => 'heroicon-o-chart-bar'],
            ['name' => 'Kesehatan', 'parent' => 'Lifestyle', 'icon' => 'heroicon-o-heart'],
        ];

        foreach ($subCategories as $subCat) {
            $parent = collect($createdCategories)->firstWhere('name', $subCat['parent']);
            if ($parent) {
                Category::create([
                    'name' => $subCat['name'],
                    'description' => "Sub-kategori dari {$subCat['parent']}",
                    'parent_id' => $parent->id,
                    'icon' => $subCat['icon'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Kategori berhasil dibuat!');
    }

    protected function seedTags(): void
    {
        if (Tag::exists()) {
            $this->command->info('Tag sudah ada, melewati...');
            return;
        }

        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'Vue.js', 'React',
            'Filament', 'Tailwind CSS', 'MySQL', 'API',
            'Tutorial', 'Tips & Trik', 'Best Practices',
            'E-commerce', 'Digital Marketing', 'SEO',
            'Produktivitas', 'Remote Work', 'Freelance',
            'Wisata Indonesia', 'Kuliner', 'Fotografi',
        ];

        foreach ($tags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
                'description' => "Tag untuk konten terkait {$tagName}",
                'type' => 'post',
            ]);
        }

        $this->command->info('Tag berhasil dibuat!');
    }

    protected function seedPosts($authors): void
    {
        if (Post::exists()) {
            $this->command->info('Post sudah ada, melewati...');
            return;
        }

        $categories = Category::all();
        $tags = Tag::all();

        $posts = [
            [
                'title' => 'Panduan Lengkap Memulai Belajar Laravel untuk Pemula',
                'category' => 'Programming',
                'excerpt' => 'Laravel adalah framework PHP yang powerful dan mudah dipelajari. Artikel ini akan memandu Anda langkah demi langkah untuk memulai perjalanan belajar Laravel.',
                'content' => "# Pengenalan Laravel

Laravel adalah salah satu framework PHP yang paling populer di dunia. Dibuat oleh Taylor Otwell pada tahun 2011, Laravel telah menjadi pilihan utama developer untuk membangun aplikasi web modern.

## Mengapa Memilih Laravel?

Laravel menawarkan berbagai keunggulan:
- **Sintaks yang Elegan**: Kode yang mudah dibaca dan ditulis
- **Ekosistem yang Kuat**: Banyak package dan library pendukung
- **Dokumentasi Lengkap**: Dokumentasi resmi yang sangat baik
- **Komunitas Besar**: Dukungan komunitas yang aktif di Indonesia

## Persiapan Awal

Sebelum memulai, pastikan Anda sudah menginstall:
1. PHP 8.1 atau lebih baru
2. Composer
3. Database (MySQL/PostgreSQL)
4. IDE (VS Code/PHPStorm)

## Membuat Project Pertama

Jalankan perintah berikut di terminal:

```bash
composer create-project laravel/laravel nama-project
cd nama-project
php artisan serve
```

Selamat! Aplikasi Laravel pertama Anda sudah berjalan di http://localhost:8000

## Langkah Selanjutnya

Setelah berhasil menginstall, Anda bisa mulai belajar:
- Routing dan Controller
- Blade Templating
- Database dengan Eloquent ORM
- Authentication
- Dan masih banyak lagi!

Selamat belajar dan jangan menyerah!",
                'tags' => ['Laravel', 'PHP', 'Tutorial'],
                'is_featured' => true,
                'view_count' => 1250,
            ],
            [
                'title' => 'Tips Meningkatkan Produktivitas Kerja di Era Remote Working',
                'category' => 'Lifestyle',
                'excerpt' => 'Bekerja dari rumah memiliki tantangan tersendiri. Simak 10 tips efektif untuk meningkatkan produktivitas saat work from home.',
                'content' => "# Work From Home: Tantangan dan Solusinya

Pandemi telah mengubah cara kita bekerja. Remote working yang awalnya dianggap privilege, kini menjadi kebutuhan. Namun, bekerja dari rumah ternyata tidak semudah yang dibayangkan.

## 10 Tips Meningkatkan Produktivitas

### 1. Buat Rutinitas Pagi yang Konsisten
Bangun di jam yang sama setiap hari, mandi, dan berpakaian seperti akan ke kantor. Ini membantu otak Anda bersiap untuk bekerja.

### 2. Siapkan Workspace Khusus
Pisahkan area kerja dengan area istirahat. Jangan bekerja di kasur atau sofa.

### 3. Gunakan Teknik Pomodoro
Bekerja fokus selama 25 menit, istirahat 5 menit. Ulangi 4 kali, lalu istirahat panjang 15-30 menit.

### 4. Atur To-Do List Harian
Buat daftar prioritas setiap pagi. Fokus pada 3 tugas penting yang harus diselesaikan hari itu.

### 5. Matikan Notifikasi yang Tidak Perlu
Medsos dan chat pribadi bisa menunggu. Fokus pada pekerjaan saat jam kerja.

### 6. Jaga Komunikasi dengan Tim
Lakukan daily standup via video call. Jangan sampai isolasi membuat Anda kehilangan konteks pekerjaan.

### 7. Olahraga Teratur
Sisipkan workout ringan atau stretching setiap 2-3 jam. Tubuh sehat, pikiran jernih.

### 8. Batasi Jam Kerja
Jangan sampai overwork. Tentukan jam berakhir dan patuhi itu.

### 9. Gunakan Tools yang Tepat
Manfaatkan project management tools seperti Trello, Asana, atau Notion untuk tracking pekerjaan.

### 10. Self-Care adalah Prioritas
Jangan lupakan kesehatan mental. Take breaks, do hobbies, dan jangan ragu untuk meminta bantuan.

## Kesimpulan

Remote working adalah marathon, bukan sprint. Temukan ritme yang cocok untuk Anda dan terus adjust. Yang penting: **balance**.

Produktivitas bukan tentang bekerja lebih lama, tapi bekerja lebih cerdas.",
                'tags' => ['Produktivitas', 'Remote Work', 'Tips & Trik'],
                'is_featured' => true,
                'view_count' => 890,
            ],
            [
                'title' => '5 Destinasi Wisata Tersembunyi di Jawa Barat yang Wajib Dikunjungi',
                'category' => 'Travel',
                'excerpt' => 'Jawa Barat menyimpan banyak destinasi wisata indah yang belum banyak diketahui. Yuk, jelajahi 5 tempat tersembunyi yang instagramable!',
                'content' => "# Jelajah Jawa Barat: Hidden Gems yang Memukau

Jawa Barat tidak hanya Bandung dan Pangandaran. Masih banyak tempat indah yang belum terjamah wisatawan massal. Berikut 5 destinasi tersembunyi yang wajib masuk bucket list Anda.

## 1. Situ Patenggang, Ciwidey

Danau yang dikelilingi kebun teh ini menawarkan pemandangan yang menenangkan. Suasana sejuk dan udara segar membuat tempat ini cocok untuk healing.

**Tips Berkunjung:**
- Datang pagi hari untuk menghindari kabut
- Sewa perahu untuk keliling danau
- Jangan lupa kunjungi Batu Cinta!

## 2. Curug Cimahi (Rainbow Waterfall)

Air terjun setinggi 87 meter ini mendapat julukan Rainbow Waterfall karena sering muncul pelangi di sekitar air terjun saat pagi hari.

**Akses:**
- Dari Bandung sekitar 1 jam
- Tersedia parkir luas
- Trek turun cukup menantang

## 3. Pantai Pangumbahan, Sukabumi

Pantai konservasi penyu ini menawarkan pengalaman unik: melepas tukik (bayi penyu) ke laut!

**Aktivitas:**
- Melepas tukik (musim tertentu)
- Camping di tepi pantai
- Melihat sunrise yang menakjubkan

## 4. Kampung Wisata Cai Ranca Upas

Perkemahan dengan pemandangan rusa liar yang berkeliaran bebas. Unik dan menyenangkan untuk keluarga.

**Fasilitas:**
- Area camping
- Penangkaran rusa
- Kolam air panas alami

## 5. Tebing Keraton, Bandung

Spot sunrise/sunset terbaik di Bandung dengan pemandangan kota dari ketinggian.

**Best Time:**
- Sunrise: 05.00 - 06.30
- Sunset: 17.00 - 18.30
- Bawa jaket, dingin!

## Tips Travel ke Jawa Barat

1. **Transportasi**: Sewa mobil lebih praktis
2. **Akomodasi**: Book via online travel agent untuk harga terbaik
3. **Cuaca**: Siapkan payung dan jaket
4. **Uang Tunai**: Tidak semua tempat terima cashless
5. **Internet**: Provider XL dan Telkomsel paling stabil

## Itinerary Rekomendasi (3D2N)

**Day 1**: Bandung - Tebing Keraton (sunset) - Hotel
**Day 2**: Situ Patenggang - Ranca Upas - Cimahi Waterfall
**Day 3**: Sukabumi - Pantai Pangumbahan - Pulang

Happy traveling! Jangan lupa share foto-foto indahnya di medsos ya! 📸",
                'tags' => ['Wisata Indonesia', 'Travel', 'Fotografi'],
                'is_featured' => false,
                'view_count' => 2100,
            ],
            [
                'title' => 'Strategi Digital Marketing untuk UMKM di Tahun 2025',
                'category' => 'Bisnis',
                'excerpt' => 'UMKM harus go digital untuk bertahan. Pelajari strategi digital marketing yang efektif dan terjangkau untuk bisnis kecil.',
                'content' => "# Digital Marketing untuk UMKM: Panduan Praktis

Di era digital ini, UMKM yang tidak go online adalah UMKM yang tertinggal. Tapi tenang, digital marketing tidak harus mahal dan rumit!

## Mengapa UMKM Butuh Digital Marketing?

Statistik bicara:
- 87% konsumen Indonesia cari produk online dulu sebelum beli
- 73% UMKM yang go digital meningkat omzetnya
- Biaya marketing online 62% lebih murah dari konvensional

## 5 Strategi Digital Marketing untuk UMKM

### 1. Manfaatkan Media Sosial dengan Maksimal

**Instagram:**
- Post produk dengan foto berkualitas
- Gunakan Instagram Stories untuk behind-the-scenes
- Manfaatkan fitur Shopping
- Konsisten posting minimal 3x seminggu

**TikTok:**
- Platform gratis dengan jangkauan organik tinggi
- Buat konten edukatif atau entertaining
- Ikuti trending sounds dan challenges

**WhatsApp Business:**
- Fitur katalog untuk display produk
- Auto-reply untuk respon cepat
- WhatsApp Status untuk promosi

### 2. SEO Lokal untuk Google My Business

Daftarkan bisnis Anda di Google My Business:
- Muncul di Google Maps
- Dapat review dari customer
- Gratis dan efektif untuk bisnis lokal

Tips optimasi:
- Lengkapi semua informasi (jam buka, alamat, telepon)
- Upload foto produk/toko
- Balas semua review
- Update info jika ada promo

### 3. Content Marketing yang Menjual

Buat konten yang:
- **Edukatif**: Tutorial, tips, how-to
- **Entertaining**: Lucu, relatable
- **Inspiratif**: Success story, testimoni

Jangan hard selling terus! Ikuti rumus 80/20:
- 80% konten value (edukasi/hiburan)
- 20% konten jualan

### 4. Kolaborasi dengan Micro-Influencer

Budget terbatas? Pilih micro-influencer (5k-50k followers):
- Engagement rate lebih tinggi
- Lebih terjangkau
- Audience lebih tersegmen

Tips kolaborasi:
- Pilih influencer yang sesuai niche
- Barter produk untuk review
- Pastikan konten authentic, bukan terlalu scripted

### 5. Email Marketing (Yes, Masih Efektif!)

Kumpulkan database customer:
- Tawarkan discount untuk subscribe newsletter
- Kirim promo eksklusif via email
- Update produk baru

Tools gratis: Mailchimp (free up to 500 contacts)

## Tools Digital Marketing Gratis/Murah

1. **Canva**: Design grafis mudah
2. **CapCut**: Edit video untuk TikTok/Reels
3. **Google Analytics**: Track website visitors
4. **Meta Business Suite**: Manage IG & FB
5. **Buffer/Later**: Schedule social media posts

## Kesalahan yang Harus Dihindari

❌ Posting tidak konsisten
❌ Mengabaikan customer chat/comment
❌ Terlalu banyak hard selling
❌ Tidak tracking metrik/analytics
❌ Copy konten kompetitor

## Metrik yang Harus Dipantau

- **Engagement Rate**: Like, comment, share
- **Reach**: Berapa orang lihat konten Anda
- **Conversion Rate**: Berapa yang beli setelah lihat iklan
- **ROI**: Return on Investment dari budget ads

## Action Plan 30 Hari

**Minggu 1:**
- Setup Google My Business
- Optimasi profile Instagram/Facebook
- Buat content calendar

**Minggu 2:**
- Post konten konsisten (3-5x/minggu)
- Engage dengan followers
- Riset hashtag yang tepat

**Minggu 3:**
- Buat konten video (Reels/TikTok)
- Kolaborasi dengan UMKM lain
- Mulai kumpulin database email

**Minggu 4:**
- Analisa performa konten
- Adjust strategi
- Planning bulan depan

## Kesimpulan

Digital marketing bukan tentang budget besar, tapi tentang konsistensi dan kreativitas. Mulai dari yang gratis dulu, scale up kalau sudah ada hasil.

**Remember**: Rome wasn't built in a day. Begitu juga digital presence Anda. Keep learning, keep improving!",
                'tags' => ['Digital Marketing', 'E-commerce', 'Startup', 'SEO'],
                'is_featured' => true,
                'view_count' => 1560,
            ],
            [
                'title' => 'Mengenal Artificial Intelligence dan Dampaknya pada Industri Kreatif',
                'category' => 'AI & Machine Learning',
                'excerpt' => 'AI semakin merambah industri kreatif. Apakah AI akan menggantikan peran manusia? Simak analisis lengkapnya.',
                'content' => "# AI dalam Industri Kreatif: Ancaman atau Peluang?

Kemunculan ChatGPT, Midjourney, dan berbagai tools AI generatif telah menggemparkan dunia kreatif. Ada yang excited, ada yang khawatir kehilangan pekerjaan. Mari kita bahas secara objektif.

## Apa Itu AI Generatif?

AI Generatif adalah teknologi yang mampu membuat konten baru (text, image, audio, video) berdasarkan input yang diberikan.

**Contoh Tools Populer:**
- **ChatGPT**: Generate text, code, ide
- **Midjourney/DALL-E**: Generate gambar
- **Runway**: Generate/edit video
- **ElevenLabs**: Generate voice/audio

## Bagaimana AI Mengubah Industri Kreatif?

### 1. Content Creation

**Before AI:**
- Nulis artikel: 3-4 jam
- Design poster: 2-3 jam
- Brainstorming ide: 1-2 jam

**With AI:**
- Generate draft artikel: 5 menit
- Generate design concept: 1 menit
- Get 20 ide kreatif: 30 detik

Tapi ingat: AI generate, **manusia yang curate dan refine**.

### 2. Photography & Illustration

AI bisa generate gambar berkualitas tinggi dalam hitungan detik. Tapi AI tidak bisa:
- Memahami brief klien secara mendalam
- Berikan direction yang spesifik
- Tangkap momen candid yang unik
- Provide human touch dan emosi

### 3. Copywriting & Content Marketing

AI sangat membantu untuk:
- Generate outline artikel
- Rewrite/paraphrase konten
- Brainstorming headline
- SEO optimization

Tapi manusia tetap needed untuk:
- Brand voice yang unik
- Storytelling yang menyentuh
- Strategic thinking
- Understanding cultural context

### 4. Music & Audio Production

AI bisa compose musik latar, tapi belum bisa:
- Bikin lagu dengan depth emosional tinggi
- Perform live dengan feel
- Improvisasi sesuai mood

## AI: Tool, Bukan Replacement

Paradigma yang benar:
> AI tidak akan menggantikan manusia. Tapi manusia yang menggunakan AI akan menggantikan manusia yang tidak.

AI adalah **amplifier** kemampuan manusia, bukan pengganti.

## Skill yang Tetap Relevan (AI-Proof)

1. **Creative Thinking**: Ide original dan out-of-the-box
2. **Emotional Intelligence**: Memahami audience secara emosional
3. **Strategic Planning**: Big picture thinking
4. **Human Connection**: Networking dan kolaborasi
5. **Adaptability**: Belajar tools baru dengan cepat

## Bagaimana Kreator Bisa Adapt?

### 1. Learn to Use AI Tools
Jangan melawan, tapi embrace. Pelajari cara gunakan AI untuk boost produktivitas.

### 2. Focus on Human Touch
AI bisa technical, tapi tidak bisa replicate human experience dan intuisi.

### 3. Develop Unique Style
Personal branding dan signature style yang unik sulit ditiru AI.

### 4. Ethical Consideration
Transparent tentang penggunaan AI dalam karya. Build trust dengan audience.

## Case Study: Desainer yang Sukses Adopsi AI

**Sarah, Graphic Designer:**
- **Before AI**: 5 design projects/bulan
- **After AI**: 15 projects/bulan
- **Revenue**: Naik 200%

**Strategi Sarah:**
1. Gunakan AI untuk generate initial concepts
2. Refine dan customize sesuai brand client
3. Fokus pada high-value strategic work
4. Charge premium untuk creative direction

## Masa Depan Industri Kreatif

Prediksi 5 tahun ke depan:
- ✅ Hybrid workflow (AI + Human) jadi standar
- ✅ Demand untuk creative strategist meningkat
- ✅ Technical execution jadi lebih efficient
- ✅ Emphasis on originality dan innovation
- ❌ Pure execution jobs berkurang

## Tips untuk Para Kreator

1. **Jangan Takut**: AI adalah tool, bukan kompetitor
2. **Stay Curious**: Selalu explore teknologi baru
3. **Invest in Skills**: Soft skills semakin penting
4. **Build Portfolio**: Showcase karya yang AI can't do
5. **Network**: Community dan kolaborasi lebih penting dari sebelumnya

## Kesimpulan

AI dalam industri kreatif adalah inevitability. Pertanyaannya bukan \"apakah AI akan ambil alih?\", tapi \"bagaimana kita bisa kolaborasi dengan AI untuk hasil yang lebih baik?\"

The future is not human vs AI. The future is human + AI.

**Pro tip**: Start learning AI tools hari ini. Gratis banyak, tutorial juga melimpah. Yang penting: action!",
                'tags' => ['AI & Machine Learning', 'Tutorial', 'Best Practices'],
                'is_featured' => false,
                'view_count' => 980,
            ],
        ];

        foreach ($posts as $index => $postData) {
            $category = $categories->firstWhere('name', $postData['category']);
            if (!$category) {
                $category = $categories->random();
            }

            $post = Post::create([
                'category_id' => $category->id,
                'author_id' => $authors->random()->id,
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'type' => 'article',
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 30)),
                'is_featured' => $postData['is_featured'],
                'is_sticky' => $index === 0, // Hanya post pertama yang sticky
                'view_count' => $postData['view_count'],
                'reading_time' => ceil(str_word_count($postData['content']) / 200), // Asumsi 200 kata per menit
                'seo_title' => Str::substr($postData['title'], 0, 60),
                'seo_description' => Str::substr($postData['excerpt'], 0, 160),
            ]);

            // Attach tags
            $tagNames = $postData['tags'];
            $postTags = $tags->filter(function ($tag) use ($tagNames) {
                return in_array($tag->name, $tagNames);
            })->pluck('id');

            if ($postTags->isNotEmpty()) {
                $post->tags()->attach($postTags);
            }

            // Buat komentar untuk setiap post
            $this->seedCommentsForPost($post, $authors);
        }

        $this->command->info('Post berhasil dibuat!');
    }

    protected function seedPages($authors): void
    {
        if (Page::exists()) {
            $this->command->info('Page sudah ada, melewati...');
            return;
        }

        $pages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'content' => "# Tentang Kami

Selamat datang di platform kami!

## Misi Kami

Kami berkomitmen untuk menyediakan konten berkualitas yang informatif, inspiratif, dan bermanfaat bagi pembaca di Indonesia.

## Visi Kami

Menjadi sumber informasi terpercaya dan terdepan dalam berbagai topik mulai dari teknologi, bisnis, lifestyle, hingga pendidikan.

## Tim Kami

Kami adalah tim content creator, developer, dan digital marketer yang passionate dalam berbagi pengetahuan dan pengalaman.

## Hubungi Kami

Punya pertanyaan atau ingin berkolaborasi? Jangan ragu untuk menghubungi kami melalui halaman kontak.",
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'content' => "# Hubungi Kami

Kami senang mendengar dari Anda! Ada pertanyaan, saran, atau ingin berkolaborasi?

## Informasi Kontak

**Email**: info@example.com
**Telepon**: +62 812-3456-7890
**Alamat**: Jakarta, Indonesia

## Jam Operasional

Senin - Jumat: 09.00 - 17.00 WIB
Sabtu: 09.00 - 13.00 WIB
Minggu: Libur

## Media Sosial

Ikuti kami di:
- Instagram: @example
- Twitter: @example
- LinkedIn: Example Company
- Facebook: Example

Kami akan merespon pesan Anda dalam waktu 1x24 jam.",
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => "# Kebijakan Privasi

Terakhir diperbarui: " . now()->format('d F Y') . "

## Pendahuluan

Kami menghormati privasi Anda dan berkomitmen untuk melindungi data pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.

## Informasi yang Kami Kumpulkan

Kami dapat mengumpulkan informasi berikut:
- Nama dan informasi kontak
- Data penggunaan website
- Cookie dan teknologi tracking lainnya

## Bagaimana Kami Menggunakan Informasi Anda

Informasi yang dikumpulkan digunakan untuk:
- Menyediakan dan meningkatkan layanan kami
- Berkomunikasi dengan Anda
- Personalisasi konten
- Analisis dan riset

## Keamanan Data

Kami mengimplementasikan langkah-langkah keamanan yang sesuai untuk melindungi data pribadi Anda dari akses, perubahan, atau penghapusan yang tidak sah.

## Cookie

Website kami menggunakan cookie untuk meningkatkan pengalaman pengguna. Anda dapat mengatur browser Anda untuk menolak cookie, tetapi beberapa fitur website mungkin tidak berfungsi dengan baik.

## Hak Anda

Anda memiliki hak untuk:
- Mengakses data pribadi Anda
- Meminta koreksi data yang tidak akurat
- Meminta penghapusan data Anda
- Menolak pemrosesan data Anda

## Perubahan Kebijakan

Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan akan diposting di halaman ini dengan tanggal \"Terakhir diperbarui\" yang baru.

## Kontak

Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami di privacy@example.com",
            ],
            [
                'title' => 'Syarat dan Ketentuan',
                'slug' => 'syarat-dan-ketentuan',
                'content' => "# Syarat dan Ketentuan

Terakhir diperbarui: " . now()->format('d F Y') . "

## Penerimaan Syarat

Dengan mengakses dan menggunakan website ini, Anda menyetujui untuk terikat dengan Syarat dan Ketentuan ini.

## Penggunaan Website

Anda setuju untuk:
- Menggunakan website ini hanya untuk tujuan yang sah
- Tidak melanggar hak kekayaan intelektual
- Tidak mengunggah konten yang melanggar hukum

## Hak Kekayaan Intelektual

Semua konten di website ini, termasuk teks, gambar, logo, dan desain, adalah hak milik kami atau pemberi lisensi kami dan dilindungi oleh undang-undang hak cipta.

## Konten Pengguna

Dengan mengirimkan konten ke website kami (seperti komentar), Anda memberikan kami hak non-eksklusif untuk menggunakan, mereproduksi, dan menampilkan konten tersebut.

## Tautan ke Website Lain

Website kami mungkin berisi tautan ke website pihak ketiga. Kami tidak bertanggung jawab atas konten atau kebijakan privasi website tersebut.

## Pembatasan Tanggung Jawab

Kami tidak bertanggung jawab atas kerugian atau kerusakan yang timbul dari penggunaan website ini.

## Perubahan Syarat

Kami berhak untuk mengubah Syarat dan Ketentuan ini kapan saja. Perubahan akan berlaku segera setelah diposting di website.

## Hukum yang Berlaku

Syarat dan Ketentuan ini diatur oleh hukum Republik Indonesia.

## Kontak

Untuk pertanyaan tentang Syarat dan Ketentuan ini, hubungi kami di legal@example.com",
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create([
                'author_id' => $authors->random()->id,
                'title' => $pageData['title'],
                'slug' => $pageData['slug'],
                'content' => $pageData['content'],
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => Str::substr($pageData['title'], 0, 60),
                'seo_description' => Str::substr(strip_tags($pageData['content']), 0, 160),
            ]);
        }

        $this->command->info('Page berhasil dibuat!');
    }

    protected function seedCommentsForPost(Post $post, $authors): void
    {
        $indonesianComments = [
            'Artikel yang sangat bermanfaat! Terima kasih sudah sharing ilmunya.',
            'Mantap banget penjelasannya, langsung praktek nih!',
            'Keren! Ditunggu artikel selanjutnya ya.',
            'Informatif sekali, sangat membantu untuk pemula seperti saya.',
            'Wah ini yang saya cari-cari! Thanks min.',
            'Penjelasannya mudah dipahami, good job!',
            'Boleh tanya, untuk case yang lebih kompleks gimana ya?',
            'Bookmark dulu, nanti dipelajari lebih detail.',
            'Artikel yang sangat inspiring, keep up the good work!',
            'Tutorial yang clear dan to the point, suka!',
        ];

        $commentTotal = rand(3, 8);

        for ($i = 0; $i < $commentTotal; $i++) {
            $useGuest = (bool) rand(0, 1);
            $isApproved = (bool) rand(0, 10) > 2; // 80% approved

            $commentData = [
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
                'content' => $indonesianComments[array_rand($indonesianComments)],
                'is_approved' => $isApproved,
                'is_featured' => $isApproved && ((bool) rand(0, 10) === 0), // 10% featured dari yang approved
                'likes_count' => rand(0, 50),
                'created_at' => $post->published_at->addDays(rand(1, 5)),
            ];

            if ($useGuest) {
                $guestNames = ['Budi Santoso', 'Ani Wijaya', 'Dedi Pratama', 'Siti Nurhaliza', 'Eko Saputra', 'Rina Kusuma'];
                $comment = Comment::create(array_merge($commentData, [
                    'guest_name' => $guestNames[array_rand($guestNames)],
                    'guest_email' => 'guest' . rand(1, 999) . '@example.com',
                ]));
            } else {
                $comment = Comment::create(array_merge($commentData, [
                    'user_id' => $authors->random()->id,
                ]));
            }

            // Buat balasan untuk beberapa komentar
            if ($isApproved && rand(0, 2) === 0) { // 33% chance untuk dapat reply
                $replyTotal = rand(1, 3);
                $replies = [
                    'Terima kasih atas feedbacknya!',
                    'Senang artikel ini bisa membantu 😊',
                    'Untuk pertanyaan lebih detail bisa DM ya!',
                    'Stay tuned untuk update artikel berikutnya!',
                ];

                for ($j = 0; $j < $replyTotal; $j++) {
                    Comment::create([
                        'commentable_type' => Post::class,
                        'commentable_id' => $post->id,
                        'user_id' => $post->author_id, // Author yang reply
                        'parent_id' => $comment->id,
                        'content' => $replies[array_rand($replies)],
                        'is_approved' => true,
                        'is_featured' => false,
                        'likes_count' => rand(0, 20),
                        'created_at' => $comment->created_at->addHours(rand(1, 24)),
                    ]);
                }
            }
        }
    }
}
