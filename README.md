### TaskBoard-Pro

A Symfony 7 application scaffolded from `symfony/skeleton`, containerized with FrankenPHP + Caddy and a PostgreSQL database. It includes user registration and email confirmation flows, with URL signing, forms, Doctrine ORM, and a basic test suite.

> TODO: Add product overview/screenshots and a brief feature list for TaskBoard-Pro once available.

---

### Stack
- Language: `PHP >= 8.4.13`
- Framework: `Symfony 7`
- Runtime (Docker): `FrankenPHP` with `Caddy`
- Database: `PostgreSQL` (via Docker service `database`)
- ORM: `Doctrine ORM 3`
- Templates: `Twig`
- Mailer: `symfony/mailer`
- Messaging: `symfony/messenger` (DSN configurable; using Doctrine transport by default)
- URL signing: `tilleuls/url-signer-bundle`
- Package manager: `Composer`
- Tests: `PHPUnit 11`, `DAMA Doctrine Test Bundle`
- Code quality: `PHPStan`, `PHP CS Fixer`

---

### Requirements
- Recommended: Docker and Docker Compose (v2: `docker compose`)
- Alternative (no Docker):
    - PHP `>= 8.4.13` with required extensions (`ctype`, `iconv`)
    - Composer
    - PostgreSQL 16

---

### Getting started (Docker)
1. Build and start services:
    - `make start` (equivalent to `docker compose build --pull --no-cache` then `docker compose up -d`)
2. Initialize database and load fixtures (non-prod only):
    - `make db` (drops, creates, migrates, and loads fixtures for `APP_ENV=dev`)
3. Open the app:
    - Default HTTP URL: `http://localhost`
    - HTTPS is exposed by Caddy; default ports are `HTTPS_PORT=443`, `HTTP3_PORT=443/udp` (see `compose.yaml`).
4. View logs:
    - `make logs`
5. Shell into the PHP container:
    - `make bash` (or `make sh`)
6. Stop services:
    - `make down`

> Note: The Docker image publishes ports using variables `HTTP_PORT`, `HTTPS_PORT`, and `HTTP3_PORT`. Adjust via environment or a `.env` file.

---

### Application entry points
- HTTP front controller: `public/index.php`
- Console: `bin/console` (use via `make sf c="<command>"` or `docker compose exec -e APP_ENV=<env> php bin/console <command>`)

---

### Makefile commands
- `make help` — List all targets
- `make build` — Build Docker images
- `make up` — Start containers (detached)
- `make start` — Build and start
- `make down` — Stop and remove containers and orphans
- `make logs` — Follow logs
- `make sh` / `make bash` — Shell into PHP container
- `make composer c='<args>'` — Run Composer inside the container (e.g., `make composer c='install'`)
- `make vendor` — Install vendors with production flags
- `make sf c='<args>'` — Run Symfony console command
- `make cc` — Clear cache (`c:c`)
- `make db env=<dev|test>` — Drop/create DB, run migrations, load fixtures (guarded against prod)
- `make migration env=<dev|test>` — Create migration
- `make migrate env=<dev|test>` — Run migrations
- `make test c='<phpunit options>'` — Recreate test DB and run PHPUnit
- `make phpstan` — Run PHPStan
- `make phpcsfixer` — Run PHP CS Fixer

---

### Composer scripts
- `post-install-cmd` / `post-update-cmd` → `@auto-scripts`
- Auto-scripts run:
    - `cache:clear`
    - `assets:install %PUBLIC_DIR%`
    - `importmap:install`

Run arbitrary Composer commands in Docker via `make composer c='<args>'`.

---

### Environment variables
Core app (`.env`):
- `APP_ENV` — Environment name (`dev`, `test`, `prod`)
- `APP_SECRET` — Symfony secret
- `DEFAULT_URI` — Base URI used by routing
- `DATABASE_URL` — Doctrine connection string (default postgres)
- `MAILER_DSN` — Transport DSN for `symfony/mailer`
- `MAILER_FROM` — Default sender email
- `CONFIRMATION_LINK_LIFETIME` — Lifetime (seconds) for signed confirmation links
- `MESSENGER_TRANSPORT_DSN` — Messenger transport DSN (defaults to Doctrine transport)
- `SIGNATURE_KEY` — Secret key for URL signer bundle

Docker-related (`compose.yaml`):
- `SERVER_NAME` — Hostname(s) for Caddy/FrankenPHP
- `HTTP_PORT`, `HTTPS_PORT`, `HTTP3_PORT` — Published ports for the `php` service
- `POSTGRES_VERSION`, `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD` — Database image/version and credentials
- `CADDY_MERCURE_URL`, `CADDY_MERCURE_PUBLIC_URL`, `CADDY_MERCURE_JWT_SECRET` — Mercure hub config
- `SYMFONY_VERSION`, `STABILITY` — Used only during initial installation; can be removed afterward

> The test suite boots with `APP_ENV=test` (see `phpunit.dist.xml` and `tests/bootstrap.php`). The `make test` target resets the test DB automatically.

---

### Database
- Dev/Test init: `make db` (or `make db env=test`)
- Migrations: `make migrate` or `make migration` to generate new ones
- Fixtures: Loaded automatically by `make db` (via `doctrine:fixtures:load -n`)

> Default DSN examples
- Docker (app container → db container): `postgresql://app:!ChangeMe!@database:5432/app?serverVersion=16&charset=utf8`
- Local (no Docker): `postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8`

---

### Running tests
- With Docker: `make test` (optional PHPUnit flags via `c="--filter ..."`)
- Without Docker: `vendor/bin/phpunit`

PHPUnit configuration: `phpunit.dist.xml`
- Bootstrap: `tests/bootstrap.php` (loads `.env` and boots test env)
- Extensions: `DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension`

---

### Project structure (partial)
- `public/` — Front controller (`index.php`) and public assets
- `src/` — Application code
    - `Controller/Security/RegistrationController.php`
    - `Controller/Security/ConfirmationController.php`
    - `...` other domain code, entities, forms, events
- `tests/` — Test suite (`LoginTest.php`, `RegistrationTest.php`, `ConfirmationTest.php`, `bootstrap.php`)
- `config/` — Symfony configuration (standard skeleton layout)
- `templates/` — Twig templates (e.g., `security/registration.html.twig`, `security/confirm.html.twig`)
- `Makefile` — Developer workflow commands
- `compose.yaml`, `compose.override.yaml`, `compose.prod.yaml` — Docker Compose files
- `frankenphp/` — Docker entrypoint and Caddyfile for FrankenPHP
- `phpunit.dist.xml` — PHPUnit configuration
- `composer.json` — Dependencies and Composer scripts

> TODO: Document additional bundles, custom commands, and routes once stabilized.

---

### Common Symfony commands
- List commands: `make sf` or `bin/console`
- Cache clear: `make cc`
- Generate migration: `make migration`
- Run migrations: `make migrate`

---

### Linting and static analysis
- PHPStan: `make phpstan`
- PHP CS Fixer: `make phpcsfixer`

---

### Deployment
- Production compose file: `compose.prod.yaml`
- Example commands (from docs):
    - Build: `docker compose -f compose.yaml -f compose.prod.yaml build --pull --no-cache`
    - Up (wait): `docker compose -f compose.yaml -f compose.prod.yaml up --wait`

> TODO: Provide environment-specific guidance (secrets, scaling, health checks, CDN, TLS certificates) as the deployment target is finalized.

---

### License
This project is licensed under the MIT License — see `LICENSE` for details.
