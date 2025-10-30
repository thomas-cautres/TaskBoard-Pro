# TaskBoard Pro

A **Symfony 7** project management application showcasing modern PHP development practices. Supports SCRUM, KANBAN, and BASIC project methodologies with a clean Kanban board interface.

---

## 🎯 Features

### ✅ Current (Sprint 1 - Complete)
- **Authentication**: Register, email confirmation (OTP), login/logout, remember me
- **Projects**: Create with 3 types (SCRUM/KANBAN/BASIC), auto-generated columns
- **Project List**: Search, filters (type, status), sorting, pagination (12/page)
- **Project View**: Kanban board, project details, tabs (Board/List/Activity/Sprints)
- **Notifications**: In-app dropdown, badge counter, mark as read
- **Security**: User checker, project voter (view permission), ownership-based access

### 🔜 Sprint 2 (In Progress)
- Edit project (name, description, dates)
- Archive/restore projects (soft delete)
- Delete projects (hard delete with confirmation)
- Dynamic navbar (real username, avatar)
- Dashboard with real statistics

### 🔜 Sprint 3-6 (Planned)
- **Tasks**: Create, edit, move, delete with auto-numbering (EX-1, EX-2...)
- **Comments**: Add, edit, delete comments on tasks
- **Activity Log**: Timeline of all project actions
- **Drag & Drop**: Intuitive task movement between columns
- **Filters**: Priority, search on board
- **Statistics**: Charts, progress tracking, time metrics
- **Global Search**: Find projects and tasks instantly
- **Email Notifications**: Async with Symfony Messenger

---

## 🏗️ Architecture Highlights

- **Event-Driven**: ProjectCreatedEvent, NotificationReadEvent with subscribers
- **DTOs**: Separation between entities and presentation (ProjectListDto, ProjectViewDto)
- **Invokable Controllers**: Single-responsibility pattern
- **Voters**: Fine-grained permissions (ProjectVoter with VIEW/EDIT/DELETE)
- **Enums PHP 8.1**: Type-safe (ProjectType, ProjectColumnName)
- **UUID v7**: Time-ordered for better indexing
- **Doctrine**: Optimized queries, eager loading, cascade operations

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
