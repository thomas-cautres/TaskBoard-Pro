# TaskBoard Pro

A **Symfony 7** project management application showcasing modern PHP development practices. Supports SCRUM, KANBAN, and BASIC project methodologies with a clean Kanban board interface.

---

## 🎯 Features

### Current (Sprint 1)
- ✅ User authentication (register, login, email confirmation)
- ✅ Create projects (SCRUM/KANBAN/BASIC)
- ✅ View project with Kanban board
- ✅ List projects with search, filters, and pagination
- ✅ Project statistics dashboard

### Planned
- 🔜 Edit and archive projects
- 🔜 Create and manage tasks
- 🔜 Drag & drop tasks between columns
- 🔜 Sprints, comments, notifications
- 🔜 Project statistics dashboard

---

## 🛠️ Stack

- **PHP** 8.4.13+ | **Symfony** 7.0
- **Database**: PostgreSQL | **ORM**: Doctrine 3.5
- **Runtime**: FrankenPHP + Caddy (Docker)
- **Frontend**: Twig, Bootstrap 5.3, Importmap
- **Tests**: PHPUnit 11, Behat, Panther
- **Quality**: PHPStan, PHP CS Fixer

---

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

---

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
make test           # Run PHPUnit
make phpstan        # Static analysis
make phpcsfixer     # Code style check

# Cache
make cc             # Clear cache
```

---

## 🗂️ Project Structure
```
src/
├── Controller/     # HTTP endpoints
├── Entity/         # Database models
├── Form/           # Form types
├── Repository/     # Queries
├── Security/       # Voters, auth
└── Service/        # Business logic

templates/          # Twig views
translations/       # i18n (EN/FR)
tests/             # PHPUnit tests
```

---

## 🧪 Testing
```bash
make test           # All tests
make coverage       # Coverage report
```

---

## 🌐 Translations

Uses XLIFF format:
- `translations/messages.en.xlf` - English
- `translations/messages.fr.xlf` - French
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
