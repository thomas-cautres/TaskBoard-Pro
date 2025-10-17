### TaskBoard Pro

A Symfony 7 starter and personal skills demo project. The end goal is a lightweight task management app (Jira-like) showcasing modern Symfony practices: auth (register, login, email confirmation), Twig UI, Doctrine ORM (PostgreSQL), translations, and a Dockerized runtime (FrankenPHP + Caddy). Includes Makefile workflows and a basic test suite.

---

### Vision / Roadmap (Jira-like)
- Core: projects, issues/tasks, statuses, assignees
- Boards: Kanban-style columns, drag & drop
- Sprints and backlogs (planned)
- Comments, activity log, and notifications (planned)
- Labels, priorities, and search/filtering (planned)

---

### Features
- Auth: registration, login (CSRF), email confirmation (signed URLs)
- Dev: Docker Compose, Makefile, Importmap, translations (XLIFF)
- Tests: PHPUnit 11, DAMA Doctrine Test Bundle

---

### Stack
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
- Code quality: `PHPStan`, `PHP CS Fixer`

---

### Quick start (Docker)
1) Build & run: `make start`
2) Init DB (dev only): `make db`
3) Open: `http://localhost`
4) Logs: `make logs` — Shell: `make bash` — Down: `make down`

> Ports via `HTTP_PORT`, `HTTPS_PORT`, `HTTP3_PORT` in environment or `.env`.

---

### Common Make targets
- `make start` — Build + up
- `make db` — Recreate DB, migrate, load fixtures (non-prod)
- `make test` — PHPUnit (resets test DB)
- `make phpstan` / `make phpcsfixer`

---

### Tests
- Docker: `make test`
