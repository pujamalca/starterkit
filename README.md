# Laravel Starter Kit

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-4.x-F59E0B?style=for-the-badge&logo=data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTI0IDBMMCA0OEg0OEwyNCAwWiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

**Starter kit Laravel yang lengkap dengan Filament Admin Panel, RESTful API, Content Management System, dan Frontend Modern**

[Demo](#) • [Documentation](#) • [Report Bug](https://github.com/yourusername/starter-kit/issues) • [Request Feature](https://github.com/yourusername/starter-kit/issues)

</div>

---

## 📋 Table of Contents

- [Fitur Unggulan](#-fitur-unggulan)
- [Tech Stack](#-tech-stack)
- [Prasyarat](#-prasyarat)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Usage](#-usage)
- [API Documentation](#-api-documentation)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Struktur Project](#-struktur-project)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Fitur Unggulan

### 🎨 Frontend Public
- **Homepage Modern** - Hero section, features showcase, latest blog posts, dan CTA section
- **Blog System** - Listing dengan pagination, search, filter by category
- **Post Detail** - Full content, comments, related posts, social sharing
- **Responsive Design** - Mobile-first dengan Tailwind CSS
- **SEO Optimized** - Meta tags, Open Graph, canonical URLs

### ⚙️ Admin Panel (Filament)
- **Dashboard Analytics** - Overview website statistics
- **User Management** - CRUD users dengan role dan permission
- **Content Management**
  - Posts dengan rich editor, SEO fields, scheduling
  - Pages untuk halaman statis
  - Categories & Tags management
  - Comments moderation
  - Media Library terintegrasi
- **Settings Module** - Konfigurasi website, email, social media
- **Activity Logging** - Audit trail semua aktivitas user
- **Database Backup** - Manual & scheduled backups (JSON, CSV, SQL)

### 🔌 RESTful API
- **Authentication** - Laravel Sanctum dengan token abilities
- **Versioning** - API v1 dengan namespace terstruktur
- **Resources** - Posts, Categories, Pages, Comments
- **Rate Limiting** - Throttling per endpoint
- **Swagger Documentation** - OpenAPI spec dengan L5-Swagger

### 🔒 Security & Authorization
- **Spatie Permission** - Role-based access control (RBAC)
- **Token Abilities** - Granular API permissions
- **Middleware Stack** - Authentication, logging, locale, JSON response
- **CORS Ready** - Konfigurasi untuk API external access

### 📊 Additional Features
- **Job Queues** - Async processing untuk view counting, notifications
- **Caching** - Page caching, settings caching dengan TTL
- **Search** - Full-text search di posts
- **Notifications** - Comment notifications (email ready)
- **Multi-language Ready** - Locale switching setup
- **Testing Suite** - Feature tests & unit tests siap pakai

---

## 🛠 Tech Stack

| Category | Technology |
|----------|-----------|
| **Backend** | Laravel 12.x, PHP 8.3+ |
| **Admin Panel** | Filament 4.x |
| **Frontend** | Tailwind CSS 4.x, Alpine.js (via Filament) |
| **Database** | MariaDB / MySQL / PostgreSQL |
| **Authentication** | Laravel Sanctum |
| **Authorization** | Spatie Laravel Permission |
| **Media** | Spatie Media Library |
| **Activity Log** | Spatie Activity Log |
| **Build Tool** | Vite 7.x |
| **Testing** | PHPUnit, Laravel Dusk (optional) |
| **API Docs** | Swagger / OpenAPI (L5-Swagger) |

---

## 📦 Prasyarat

Pastikan sistem Anda memiliki:

- **PHP** >= 8.3
- **Composer** >= 2.7
- **Node.js** >= 20.x & NPM >= 10.x
- **Database** (MariaDB / MySQL / PostgreSQL)
- **Web Server** (Nginx / Apache)
- **Redis** (optional, untuk caching & sessions)

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/starter-kit.git
cd starter-kit
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit `.env` dan sesuaikan database credentials:

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starterkit
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeders

```bash
# Run migrations
php artisan migrate

# Run settings migration
php artisan settings:migrate

# Seed database dengan data awal
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## ⚙️ Konfigurasi

### Kredensial Admin Default

Setelah seeding, gunakan kredensial berikut untuk login ke admin panel:

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | admin@example.com | password |
| **Content Editor** | editor@example.com | password |

⚠️ **Penting:** Segera ubah password setelah login pertama!

### Storage Link

Untuk media files dapat diakses public:

```bash
php artisan storage:link
```

### Queue Worker (Production)

Untuk menjalankan queued jobs:

```bash
# Development
php artisan queue:work

# Production (systemd service recommended)
# Lihat section Deployment
```

### Scheduler (Production)

Tambahkan ke crontab untuk scheduled tasks:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 💻 Usage

### Mengakses Frontend

- **Homepage:** `http://localhost:8000`
- **Blog Listing:** `http://localhost:8000/blog`
- **Post Detail:** `http://localhost:8000/blog/{slug}`
- **Static Pages:** `http://localhost:8000/pages/{slug}`

### Mengakses Admin Panel

```
URL: http://localhost:8000/admin
Login dengan kredensial admin
```

**Features Admin:**
- Dashboard dengan analytics
- User management
- Content management (Posts, Pages, Categories, Tags)
- Comment moderation
- Media library
- Settings configuration
- Activity logs
- Database backups

### Menggunakan API

Base URL: `http://localhost:8000/api/v1`

**Authentication:**

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
  }'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password",
    "device_name": "mobile"
  }'

# Response akan memberikan access_token
```

**Menggunakan Token:**

```bash
curl -X GET http://localhost:8000/api/v1/posts \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📚 API Documentation

### Endpoints Overview

#### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/register` | Register user baru |
| POST | `/api/v1/auth/login` | Login dan dapatkan token |
| POST | `/api/v1/auth/logout` | Logout (revoke token) |
| GET | `/api/v1/auth/profile` | Get user profile |

#### Posts

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/v1/posts` | List published posts | ❌ |
| GET | `/api/v1/posts/{slug}` | Get post detail | ❌ |
| POST | `/api/v1/posts` | Create post | ✅ (posts:write) |
| PUT | `/api/v1/posts/{id}` | Update post | ✅ (posts:write) |
| DELETE | `/api/v1/posts/{id}` | Delete post | ✅ (posts:write) |

#### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/categories` | List categories |
| GET | `/api/v1/categories/{slug}` | Get category |
| GET | `/api/v1/categories/{slug}/posts` | Get posts by category |

#### Comments

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/v1/posts/{slug}/comments` | List comments | ❌ |
| POST | `/api/v1/posts/{slug}/comments` | Create comment | ❌ |
| POST | `/api/v1/comments/{id}/approve` | Approve comment | ✅ |
| DELETE | `/api/v1/comments/{id}` | Delete comment | ✅ |

### Swagger Documentation

Akses interactive API documentation:

```
http://localhost:8000/api/documentation
```

---

## 🧪 Testing

### Run All Tests

```bash
composer test
# atau
php artisan test
```

### Run Specific Test Suite

```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# Specific test file
php artisan test tests/Feature/Api/PostCrudTest.php
```

### Test Coverage

```bash
php artisan test --coverage
```

### Testing Best Practices

- Semua test menggunakan `RefreshDatabase` trait
- Database di-seed otomatis via `TestCase`
- Permission cache di-flush di `setUp()`
- Gunakan factories untuk test data

**Contoh Test:**

```php
public function test_user_can_create_post_via_api(): void
{
    $user = User::factory()->create();
    $token = $user->createToken('test', ['posts:write'])->plainTextToken;

    $response = $this->withToken($token)
        ->postJson('/api/v1/posts', [
            'title' => 'Test Post',
            'content' => 'Content here',
            // ...
        ]);

    $response->assertCreated();
}
```

---

## 🚢 Deployment

### Production Checklist

#### 1. Environment

```bash
# Set to production
APP_ENV=production
APP_DEBUG=false

# Set proper app URL
APP_URL=https://yourdomain.com

# Configure database
DB_CONNECTION=mariadb
# ... database credentials
```

#### 2. Optimize Application

```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

#### 3. Build Assets

```bash
npm run build
```

#### 4. File Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 5. Queue Worker (systemd)

Create `/etc/systemd/system/laravel-queue.service`:

```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/starterkit/artisan queue:work --queue=default --tries=3 --timeout=90
WorkingDirectory=/var/www/starterkit

[Install]
WantedBy=multi-user.target
```

Enable service:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now laravel-queue
sudo systemctl status laravel-queue
```

#### 6. Scheduler (Cron)

Add to crontab:

```bash
* * * * * cd /var/www/starterkit && php artisan schedule:run >> /dev/null 2>&1
```

#### 7. Web Server

**Nginx Configuration:**

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/starterkit/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 8. SSL Certificate (Let's Encrypt)

```bash
sudo certbot --nginx -d yourdomain.com
```

---

## 📁 Struktur Project

```
starterkit/
├── app/
│   ├── Filament/
│   │   └── Admin/
│   │       ├── Pages/         # Custom admin pages
│   │       ├── Resources/     # Filament resources
│   │       └── Widgets/       # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/       # API controllers
│   │   │   ├── BlogController.php
│   │   │   └── PageController.php
│   │   ├── Middleware/        # Custom middleware
│   │   ├── Requests/          # Form requests
│   │   └── Resources/         # API resources
│   ├── Models/                # Eloquent models
│   ├── Services/              # Business logic
│   └── Jobs/                  # Queue jobs
├── config/                    # Configuration files
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript
│   └── views/
│       ├── blog/             # Blog views
│       ├── layouts/          # Layout templates
│       │   ├── app.blade.php
│       │   └── partials/
│       ├── pages/            # Page views
│       └── welcome.blade.php # Homepage
├── routes/
│   ├── api.php              # API routes
│   └── web.php              # Web routes
├── tests/
│   ├── Feature/             # Feature tests
│   └── Unit/                # Unit tests
├── .env.example
├── composer.json
├── package.json
├── phpunit.xml
├── tailwind.config.js
├── vite.config.js
└── README.md
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standard
- Write tests for new features
- Update documentation
- Use conventional commits

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Your Name**

- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [Filament](https://filamentphp.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Spatie](https://spatie.be) for awesome Laravel packages

---

<div align="center">

**⭐ Star this repository if you find it helpful!**

Made with ❤️ using Laravel & Filament

</div>
