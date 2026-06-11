# SANIA — Africa's Startup Ecosystem Platform

SANIA connects Africa's most innovative startups with world-class incubators and accelerators through a modern, role-based platform.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 (PHP 8.2) |
| Frontend | Vue 3 + Inertia.js |
| Styling | Tailwind CSS |
| Database | MySQL 8.0 |
| Cache/Queue | Redis 7 |
| Auth | Laravel Breeze (web) + Sanctum (API) |
| Containerization | Docker + Docker Compose |

## Getting Started

### Prerequisites
- Docker & Docker Compose
- Git

### Local Setup

```bash
# Clone the repository
git clone <repo-url>
cd sania

# Copy environment file
cp .env.example .env

# Build Docker images (first time, ~2-3 min)
make build

# Start all containers
make up

# Generate app key
make artisan cmd="key:generate"

# Run migrations + seed demo data
make fresh

# Build frontend assets (or use Vite HMR — see below)
make npm cmd="run build"
```

Access the app at **http://localhost:8080**

### Vite HMR (Hot Module Replacement)

The `node` service runs Vite dev server automatically. After `make up`:
- Vite HMR: http://localhost:5173

### Useful Commands

```bash
make shell          # SSH into the PHP-FPM container
make logs           # Tail app + nginx logs
make test           # Run PHP feature tests
make fresh          # Fresh migrate + seed (wipes database)
make artisan cmd="..." # Run any artisan command
make composer cmd="..." # Run any composer command
make npm cmd="..."  # Run any npm command
```

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@sania.africa | password |
| **Incubator** | incubator@hanga.rw | password |
| **Startup** | startup@demo.com | password |

Additional incubator accounts: `hello@cchub.africa`, `info@ihub.co.ke`, etc. (all password: `password`)

## Architecture Overview

```
sania/
├── app/
│   ├── Enums/          # Role, ApplicationStatus, StartupStage
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/ # REST API controllers
│   │   │   ├── Admin/  # Admin dashboard
│   │   │   ├── Incubator/
│   │   │   ├── Startup/
│   │   │   ├── Public/ # Public directory/home
│   │   │   └── Setup/  # Post-registration profile setup
│   │   ├── Middleware/ # EnsureProfileComplete, EnsureRole
│   │   ├── Requests/   # Form request validation
│   │   └── Resources/  # API response transformers
│   ├── Models/         # Eloquent models
│   ├── Notifications/  # ApplicationStatusChanged
│   ├── Observers/      # Cache invalidation
│   └── Policies/       # Authorization policies
├── database/
│   ├── migrations/     # Schema migrations
│   ├── seeders/        # Demo data (African ecosystem)
│   └── factories/      # Test factories
├── resources/js/
│   ├── Components/     # Reusable Vue components
│   │   ├── UI/         # Badge, StatCard, Avatar, Modal, etc.
│   │   ├── Layout/     # Sidebar, TopBar, NotificationBell
│   │   └── Forms/      # TextInput, SelectInput, MultiSelect, FileUpload
│   ├── Pages/          # Inertia page components
│   │   ├── Admin/      # Admin dashboard pages
│   │   ├── Auth/       # Login, Register, Setup
│   │   ├── Incubator/  # Incubator dashboard + kanban
│   │   ├── Public/     # Home, Directory, Detail pages
│   │   └── Startup/    # Startup dashboard + apply flow
│   └── Composables/    # useFlash, useDebounce
├── routes/
│   ├── web.php         # Web routes (role-based groups)
│   └── api.php         # REST API v1 routes
├── docker/             # Docker config files
└── docs/               # Postman collection
```

## REST API

Import `/docs/sania.postman_collection.json` into Postman.

Base URL: `http://localhost:8080/api/v1`

Authentication: Bearer token (obtain via `POST /api/v1/auth/login`)

### Endpoints

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/auth/register` | — | Register new user |
| POST | `/auth/login` | — | Login, returns token |
| POST | `/auth/logout` | Bearer | Revoke token |
| GET | `/incubators` | — | List incubators (filter by country, sector) |
| GET | `/incubators/{slug}` | — | Get single incubator |
| GET | `/startups` | — | List startups (filter by sector, stage) |
| GET | `/startups/{slug}` | — | Get single startup |
| GET | `/sectors` | — | List all sectors |
| GET | `/me` | Bearer | Get authenticated user |
| PUT | `/me/profile` | Bearer | Update own profile |
| GET | `/applications` | Bearer | List own applications |
| POST | `/applications` | Bearer | Submit application (startup) |
| PATCH | `/applications/{id}/status` | Bearer | Update status (incubator) |

## Key Features

- **Role-based access control**: Admin, Incubator, Startup roles with distinct dashboards
- **Public directory**: Search and filter incubators and startups by country, sector, and stage
- **Application flow**: Startups apply to incubators → Incubators manage via kanban board → Real-time status notifications
- **Admin panel**: Verify incubators, manage users, view activity logs
- **REST API**: Versioned `/api/v1/` endpoints with Sanctum token auth
- **Queue-based notifications**: Application status changes delivered asynchronously via Redis

## Running Tests

```bash
make test
# or
docker compose exec app php artisan test
```

All 36 feature tests cover: registration, profile setup, API auth, directory filtering, application submission, status updates, and authorization.
