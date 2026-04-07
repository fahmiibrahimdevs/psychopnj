# Psychorobotic - Organization Management System

A Laravel-based web application designed for comprehensive organization management, including member tracking, event attendance, inventory management, and financial record keeping.

## 📋 Prerequisites

Ensure your server meets the following requirements before installation:

- **PHP**: >= 8.1
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Composer**: Dependency Manager for PHP
- **Node.js**: >= 16.x & NPM
- **Web Server**: Nginx (Recommended) or Apache

---

## 🚀 Installation Guide

Follow these steps to set up the project on your local machine or server.

### 1. Clone the Repository

```bash
git clone https://github.com/fahmiibrahimdevs/psychorobotic.git
cd psychorobotic
```

### 2. Install Dependencies

Install back-end and front-end dependencies:

```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install
```

### 3. Environment Configuration

Copy the example environment file and configure your settings:

```bash
cp .env.example .env
```

Open the `.env` file and update your database credentials:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Generate the application key:

```bash
php artisan key:generate
```

### 4. Database Setup

Run the migrations and seeders to set up the database schema and default data:

```bash
php artisan migrate --seed
```

### 5. Build Assets

Compile the frontend assets:

```bash
npm run build
```

---

## 📂 Storage Configuration

This project supports cloud object storage with Cloudflare R2 for public uploads.

### 1. Configure Cloudflare R2

Set these variables in `.env`:

```bash
FILESYSTEM_DISK=public

R2_ACCESS_KEY_ID=your_access_key
R2_SECRET_ACCESS_KEY=your_secret_key
R2_DEFAULT_REGION=auto
R2_BUCKET=your_bucket_name
R2_ENDPOINT=https://<accountid>.r2.cloudflarestorage.com
R2_URL=https://<your-public-domain>.r2.dev
R2_ROOT=psychorobotic
R2_USE_PATH_STYLE_ENDPOINT=false
```

### 2. Migrate Existing Local Files to R2

Preview files first:

```bash
php artisan storage:migrate-r2 --dry-run
```

Run the migration:

```bash
php artisan storage:migrate-r2
```

Optional: remove local files after successful upload:

```bash
php artisan storage:migrate-r2 --delete-local
```

### 3. File Permissions (Linux/Server)

Ensure the web server user (usually `www-data` or `nginx`) has write access to storage directories:

```bash
# Set ownership to web server user (Critical for 755 permissions)
sudo chown -R www-data:www-data storage bootstrap/cache

# Set directory permissions to 755 (Owner: rwx, Group: rx, Others: rx)
sudo chmod -R 755 storage bootstrap/cache
```

---

## 🌐 Nginx Configuration

Below is a recommended Nginx server block configuration for this project.

1. Create a new configuration file:

    ```bash
    sudo nano /etc/nginx/sites-available/psychorobotic
    ```

2. Paste the following configuration (adjust `server_name` and `root` path):

    ```nginx
    server {
        listen 80;
        server_name your-domain.com; # Or localhost
        root /var/www/projects/laravel/psychorobotic/public;

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
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock; # Check your PHP version socket
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }

        # Increase upload size
        client_max_body_size 64M;
    }
    ```

3. Enable the site and restart Nginx:
    ```bash
    sudo ln -s /etc/nginx/sites-available/psychorobotic /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl restart nginx
    ```

---

## 🛠️ Additional Configuration

### Google Sheets Integration

If the application uses Google Sheets features (e.g., config imports), ensure you set the ID in `.env`:

```ini
GOOGLE_SHEETS_BARANG_ID=your_sheet_id_here
```

### Image Compression

Adjust the image compression threshold if needed:

```ini
IMAGE_COMPRESS_SIZE_KB=250
```

---

## ▶️ Running Locally (Development)

If you are developing locally without Nginx, you can use the built-in PHP server:

```bash
php artisan serve
```

Access the app at: [http://localhost:8000](http://localhost:8000)

---

## 📜 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
