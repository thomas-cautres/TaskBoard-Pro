# TaskBoard Pro

A lightweight, Jira-like task board built with Symfony 7. This repo is a personal starter and skills demo that showcases modern Symfony practices: authentication (register, login, email confirmation with signed URLs), Twig UI, Doctrine ORM (PostgreSQL), translations, a Dockerized runtime (FrankenPHP + Caddy), and a handy Makefile for common workflows.


## Table of contents
- Overview
- Features
- Tech stack
- Quick start
- Demo users (fixtures)
- Make targets
- Environment & configuration
- Database & migrations
- Testing & quality
- Troubleshooting
- Roadmap
- Contributing
- License


## Overview
TaskBoard Pro aims to provide a minimal-yet-practical project/task board with a Kanban workflow. It’s optimized for fast local development via Docker and includes sensible defaults for tests and code quality.


## Features
Current
- Authentication: registration, login (CSRF), email confirmation (signed URLs)
- Project and task domain (ongoing)
- Translations (XLIFF)
- Dockerized dev stack with FrankenPHP + Caddy
- Makefile-powered workflows

Planned
- Boards: Kanban columns with drag & drop
- Sprints and backlogs
- Comments, activity log, notifications
- Labels, priorities, advanced search/filtering


## Tech stack
- Language: `PHP >= 8.4.13`
- Framework: `Symfony 7`
- Runtime: `FrankenPHP` + `Caddy` (Docker)
- Database: `PostgreSQL`
- ORM: `Doctrine ORM 3`
- Templates: `Twig`
- Mailer: `symfony/mailer`
- Messaging: `symfony/messenger`
- URL signing: `tilleuls/url-signer-bundle`
- Package manager: `Composer`
- Quality: `PHPStan`, `PHP CS Fixer`
- Tests: `PHPUnit 11`, `dama/doctrine-test-bundle`


## Quick start (Docker)
Prerequisites
- Docker Desktop (or Docker Engine) + Docker Compose
- GNU Make

Steps
1) Build and start containers:
```
make start
```
2) Initialize database (dev only; destructive):
```
make db
```
3) Open the app:
```
http://localhost
```
Helpful
- Logs: `make logs`
- Shell (bash) in PHP container: `make bash`
- Stop and remove containers: `make down`

Ports
- Override via environment or `.env` using `HTTP_PORT`, `HTTPS_PORT`, `HTTP3_PORT`.


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
- `make test c="<phpunit args>"` — Reset test DB and run PHPUnit
- `make phpstan` — Static analysis
- `make phpcsfixer` — Code style fix
- `make im-require c="<package>"` — Importmap: require a frontend package


## Environment & configuration
- App environment: use `APP_ENV=dev|test|prod` (Compose services set this for you).
- Ports: `HTTP_PORT`, `HTTPS_PORT`, `HTTP3_PORT` can be set in your environment or `.env`.
- Database: Docker Compose wires the DB; migrations/fixtures are run via Make targets.
- Mailer: configure `MAILER_DSN` in `.env.local` if you want to test email sending.


## Database & migrations
- Dev reset + fixtures: `make db` (destructive; refuses `env=prod`).
- Generate migrations: `make migration`.
- Apply migrations: `make migrate`.
- Tests automatically reset the test DB before running (`make test`).


## Testing & quality
- Run tests:
```
make test
```
- Static analysis:
```
make phpstan
```
- Code style fix:
```
make phpcsfixer
```


## Troubleshooting
- Port already in use (e.g., 80/443): set `HTTP_PORT`/`HTTPS_PORT` in your environment or a `.env` file, then `make down && make start`.
- Apple Silicon: Docker images should work on arm64; if you hit image issues, rebuild with `make start` (which does a fresh build).
- Slow first run: the initial Docker build and Composer install can take a while; subsequent runs are much faster.


## Roadmap
- Core: projects, issues/tasks, statuses, assignees
- Boards: Kanban-style columns, drag & drop
- Sprints and backlogs
- Comments, activity log, notifications
- Labels, priorities, search/filtering


## Contributing
PRs and issues are welcome. Keep commits scoped and include a brief description. For significant changes, please open an issue to discuss your proposal first.


## License
This project is licensed under the MIT License — see the `LICENSE` file for details.


---
Last updated: 2025-10-28 20:55
