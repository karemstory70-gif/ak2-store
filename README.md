# AK2 Store

AK2 Store is a Laravel storefront for browsing products and categories, managing a cart and checkout, and handling orders. The storefront supports English and Arabic content. Staff manage catalog and storefront content through a Filament admin panel.

## Technology

- PHP 8.4 or newer (below PHP 9)
- Laravel 13, Livewire 4, Filament 5, and Filament Shield
- MariaDB or MySQL for a hosted demo
- Vite Plus, Tailwind CSS 4, Node.js 22, and npm

For MariaDB/MySQL, PHP must have PDO and `pdo_mysql` enabled. Install the PHP extensions required by Composer, including `ctype`, `dom`, `fileinfo`, `filter`, `hash`, `iconv`, `intl`, `json`, `libxml`, `mbstring`, `openssl`, `session`, `tokenizer`, `xmlreader`, and `zip`.

## Installation

1. Install the PHP dependencies:

   ```sh
   composer install
   ```

2. Create the local environment file. On Windows PowerShell:

   ```powershell
   if (!(Test-Path .env)) { Copy-Item .env.example .env }
   ```

   On macOS/Linux:

   ```sh
   test -f .env || cp .env.example .env
   ```

3. Configure the application key and database **before** running migrations:

   ```sh
   php artisan key:generate
   ```

   The example file selects SQLite for an isolated local development setup. Its database file is intentionally ignored by Git. Create/use only a new, disposable database for this project. For a hosted demo, create a new empty MariaDB/MySQL database and dedicated database user, then set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, and `DB_CONNECTION=mariadb` with the host, database name, username, and password in the hosting provider's environment settings. Do not put real credentials in `.env.example`, source code, or Git.

   For a fresh local SQLite setup, create the database file only if it does not already exist. PowerShell:

   ```powershell
   if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File -Path database/database.sqlite | Out-Null }
   ```

   macOS/Linux:

   ```sh
   test -f database/database.sqlite || touch database/database.sqlite
   ```

   If a database file already exists and you cannot confirm it is disposable, do not run migrations against it; configure a separate new database instead.

4. Build the database schema only after confirming that the configured connection points to the new, empty project database:

   ```sh
   php artisan migrate
   ```

   On a deployment runner, use `php artisan migrate --force` only for that dedicated new demo database. Do not use `migrate:fresh` against a database containing data.

5. Install and build the frontend assets:

   ```sh
   npm ci
   npm run build
   ```

6. Publish the public storage link:

   ```sh
   php artisan storage:link
   ```

7. Create or assign the administrator interactively after migrations:

   ```sh
   php artisan shield:super-admin
   ```

   Enter administrator details only at the interactive prompt; do not place passwords in shell commands or source files. Do not run the default database seeder on a public demo: it creates a generic test account. Verify that only intended accounts can access `/admin` before making the site public.

For local development, start Laravel with:

```sh
php artisan serve
```

The web server's document root must be the project's `public` directory. Do not expose the project root.

## Storage and hosted deployments

Product and storefront uploads use Laravel's public storage disk. Keep `storage/app/public` on persistent storage and ensure `public/storage` is linked after deployment. Files uploaded on the current workstation are not part of the repository; upload only approved demo images to the demo environment. Free hosting plans may use ephemeral storage or sleep when idle, so confirm their persistence, symlink, PHP CLI, and database-networking support.

Generated Vite assets in `public/build` and published Filament assets are build/deployment outputs and are intentionally excluded from Git. Build/publish them during deployment. Set `APP_DEBUG=false`, use a unique `APP_KEY`, and configure HTTPS and the correct `APP_URL` on the host. The example mailer writes to the log; configure a mail provider through environment settings if password reset or email-verification messages must be delivered.

## Deployment safety notes

- Keep `.env`, local database files, SQL dumps, backups, logs, caches, uploads, and customer/order exports out of Git.
- `composer setup` runs `php artisan migrate --force` as part of its setup script. Do not run it unless the configured connection is the intended new, empty database.
- Fortify registration and the existing Filament access rules are unchanged by this repository preparation. Review the intended administrator and registration behavior before exposing a public demo.
