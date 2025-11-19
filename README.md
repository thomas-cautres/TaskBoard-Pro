# TaskBoard Pro

A **Symfony 7** project management application showcasing modern PHP development practices. Supports SCRUM, KANBAN, and BASIC project methodologies with a clean Kanban board interface.


## 🎯 Features

### Current
- ✅ User authentication (register, login, email confirmation)
- ✅ Create projects (SCRUM/KANBAN/BASIC)
- ✅ View project with Kanban board
- ✅ List projects with search, filters, and pagination
- ✅ Project statistics dashboard
- ✅ Edit and archive projects
- ✅ Create tasks

### Planned
- 🔜 Manage tasks
- 🔜 Drag & drop tasks between columns
- 🔜 Sprints, comments, notifications
- 🔜 API


## 🛠️ Stack

- **PHP** 8.4.13+ | **Symfony** 7.3
- **Database**: PostgreSQL | **ORM**: Doctrine 3.5
- **Runtime**: FrankenPHP + Caddy (Docker)
- **Frontend**: Twig, Bootstrap 5.3, Importmap
- **Tests**: PHPUnit 11, Behat, Panther
- **Quality**: PHPStan, PHP CS Fixer


## 🔧 Symfony Components Used

- [FrameworkBundle](https://symfony.com/doc/current/reference/configuration/framework.html) — Core kernel, DI, config, routing.
- [Security](https://symfony.com/doc/current/security.html) — Auth, authorization, CSRF.
- [Form](https://symfony.com/doc/current/forms.html) — Build and process forms.
- [Validator](https://symfony.com/doc/current/validation.html) — Object and input validation.
- [TwigBundle / Templates](https://symfony.com/doc/current/templates.html) — Server-side rendering with Twig.
- [Translation](https://symfony.com/doc/current/translation.html) — i18n with message catalogs.
- [Asset](https://symfony.com/doc/current/components/asset.html) + [Asset Mapper](https://symfony.com/doc/current/frontend/asset_mapper.html) — Serve and map static assets.
- [StimulusBundle](https://symfony.com/doc/current/frontend/stimulus.html) — Lightweight JS controllers.
- [Messenger](https://symfony.com/doc/current/messenger.html) — Async messages and queues.
- [Mailer](https://symfony.com/doc/current/mailer.html) — Send emails.
- [Uid](https://symfony.com/doc/current/components/uid.html) — UUID/ULID value objects.
- [Workflow](https://symfony.com/doc/current/workflow.html) — Define state machines.
- [EventDispatcher](https://symfony.com/doc/current/components/event_dispatcher.html) — Events and subscribers.
- [Console](https://symfony.com/doc/current/components/console.html) — CLI commands framework.
- [Dotenv](https://symfony.com/doc/current/components/dotenv.html) — Load env vars in dev.
- [Runtime](https://symfony.com/doc/current/components/runtime.html) — Application bootstrapping.
- [Monolog (MonologBundle)](https://symfony.com/doc/current/logging.html) — Structured logging.
- [Yaml](https://symfony.com/doc/current/components/yaml.html) — YAML parsing.
- [Serializer](https://symfony.com/doc/current/components/serializer.html) — Serialization

Developer and testing tooling
- [MakerBundle](https://symfony.com/bundles/SymfonyMakerBundle/current/index.html) — Code generators for scaffolding.
- [Web Profiler](https://symfony.com/doc/current/profiler.html) — Dev toolbar and profiler panels.
- [BrowserKit](https://symfony.com/doc/current/components/browser_kit.html) + [CssSelector](https://symfony.com/doc/current/components/css_selector.html) — HTTP client and DOM selectors for tests.
- [Panther](https://symfony.com/doc/current/testing/end_to_end.html) — Headless end‑to‑end browser tests.

## 🚀 Quick Start
```bash
# Clone and start
git clone https://github.com/thomas-cautres/TaskBoard-Pro.git
cd taskboard-pro
make start

# Initialize database (dev only)
make db

# Open app
http://localhost

# Default login (from fixtures)
Email: user-confirmed@domain.com
Password: test1234
```


## 📋 Common Commands
```bash
# Development
make start          # Start containers
make stop           # Stop containers
make logs           # View logs
make bash           # Open shell

# Database
make db             # Reset DB + fixtures
make migrate        # Run migrations

# Testing
make unit           # Run PHPUnit
make behat          # Run Behat
make phpstan        # Static analysis
make phpcsfixer     # Code style check

# Cache
make cc             # Clear cache
```

---

## 🧪 Testing
```bash
make unit           # Unit tests
make behat          # Behavior tests
make coverage       # Coverage report
```

## Demo users (fixtures)
Loading fixtures via `make db` creates the following demo accounts:
- Confirmed: `user-confirmed@domain.com` / `test1234`
- Confirmed: `user2-confirmed@domain.com` / `test1234`
- Unconfirmed: `user-unconfirmed@domain.com` / `test1234`

Note: Some features may require a confirmed account.


## Make targets
- `make start` — Build images and start containers
- `make up` — Start containers (no rebuild)
- `make down` — Stop containers and remove orphans
- `make logs` — Follow container logs
- `make sh` / `make bash` — Shell into the PHP container
- `make composer c="<args>"` — Run Composer inside the container
- `make vendor` — Install vendors (no-dev, from lock)
- `make sf c="<console cmd>"` — Run Symfony console, e.g. `make sf c=about`
- `make cc` — Clear cache (`c:c`)
- `make db env=dev|test` — Recreate DB, run migrations, load fixtures (refuses prod)
- `make migration` — Create Doctrine migration from entity changes
- `make migrate` — Apply migrations
- `make unit c="<phpunit args>"` — Reset test DB and run PHPUnit
- `make phpstan` — Static analysis
- `make phpcsfixer` — Code style fix
- `make im-require c="<package>"` — Importmap: require a frontend package


## Environment & configuration
- App environment: use `APP_ENV=dev|test|prod` (Compose services set this for you).
- Ports: `HTTP_PORT`, `HTTPS_PORT`, `HTTP3_PORT` can be set in your environment or `.env`.
- Database: Docker Compose wires the DB; migrations/fixtures are run via Make targets.
- Mailer: configure `MAILER_DSN` in `.env.local` if you want to test email sending.

## 🌐 Translations

Uses XLIFF format:
- `translations/messages.en.xlf` - English
```twig
{{ 'project.list.title'|trans }}
```

---

## 🔒 Security

- CSRF protection on forms
- Password hashing (Symfony native)
- Email confirmation (signed URLs)
- Ownership-based permissions

---

## 📝 License

MIT License

---

## 👤 Author

**Thomas Cautrès** - [GitHub](https://github.com/thomas-cautres)
