# ElsaCMS

ElsaCMS is a modern, lightweight, and secure Content Management System built with **CodeIgniter 4**. It is designed to be fast, flexible, and easy to use for developers and content creators.

## Features 🚀

- **Modern Dashboard**: A clean, responsive admin interface for managing your site.
- **Blog System**: Full-featured blog with categories, featured images, and rich text editor (Quill).
- **Related Posts**: Automatically displays relevant articles to better engage readers.
- **User Management**: Secure authentication with Role-Based Access Control (RBAC) and **Password Recovery** (Forgot Password).
- **Dynamic Settings**: Customize site identity, logo, hero section, and social media links directly from the admin panel.
- **SEO & Social Ready**: Built-in Open Graph (OG) tags and Twitter Cards for beautiful social media previews.
- **Mobile Optimized**: Fully responsive frontend and backend design.
- **Email System**: Integrated **PHPMailer** for reliable verify/reset emails via SMTP.

## Requirements 📋

- PHP **8.1** or higher.
- extensions: `intl`, `mbstring`, `json`.
- Composer.
- MySQL, PostgreSQL, or SQLite.

## Installation 🛠️

1. **Clone the repository**
   ```bash
   git clone https://github.com/abonkfarouk/elsacms.git
   cd elsacms
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Configure Environment**
   Copy the example environment file:
   ```bash
   cp env .env
   ```
   Open `.env` and configure your database and SMTP settings:
   ```ini
   CI_ENVIRONMENT = development
   
   # Database
   database.default.hostname = localhost
   database.default.database = elsacms
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi

   # SMTP (Email)
   SMTP_HOST = smtp.gmail.com
   SMTP_PORT = 465
   SMTP_USER = your_email@gmail.com
   SMTP_PASS = your_app_password
   SMTP_SECURE = ssl
   ```

4. **Run Migrations & Seeds**
   Set up the database and create the default admin account:
   ```bash
   php spark migrate
   php spark db:seed DatabaseSeeder
   ```

5. **Start the Server**
   ```bash
   php spark serve
   ```

## Login 🔑

Access the admin dashboard at `http://localhost:8080/login`.

- **Username**: `admin`
- **Password**: `admin123`

> **Note**: Please change the default password immediately after logging in!

## Directory Structure

- `app/`: Core application code (Controllers, Models, Views).
- `public/`: Publicly accessible files (CSS, JS, Uploads).
- `writable/`: Logs, cache, and session files.
- `tests/`: PHPUnit tests.

## Security

If you discover a security vulnerability, please check the [CodeIgniter Security Policy](https://codeigniter.com/security_policy).

## License

This project is open-sourced software licensed under the **MIT license**.
