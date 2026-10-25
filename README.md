# ShelfWise — Library Management System

**IT 2001 Web Programming | 2026/27 | Milestone 1**

ShelfWise is a responsive single-page library management application. Admins manage books, categories, members, loans, reservations and fines. Members browse books, reserve titles and view their borrowing history. M1 is a **static prototype**: it deliberately does **not** authenticate users, store data or call a backend yet.

## Run Milestone 1
1. Unzip the project or clone the repository.
2. Open `frontend/index.html` in a browser. For reliable local template loading, serve the `frontend` folder with a simple HTTP server, e.g. `python -m http.server 8000` from inside `frontend/`, then open `http://localhost:8000`.
3. Use the sidebar to navigate among views without a full-page reload. Forms are present for layout demonstrations only.

## Stack and architecture
- Frontend: HTML, CSS, vanilla JavaScript; `fetch()` loads local HTML view templates as a simple SPA (no build tools).
- Backend: reserved for PHP 8.2+, FlightPHP REST, services, PDO DAO, JWT auth and middleware in later milestones.
- MySQL draft: see [docs/ERD.md](docs/ERD.md). No database is implemented in M1.

## Planned entities and roles
`users` (admin/member), `categories`, `books`, `loans`, `reservations`, `fines`. Relationships and key fields in `docs/ERD.md`.

## Views
Dashboard, Books, Categories, Members, Loans, Reservations, Fines, Profile, Login and Register. The Open Library API area is planned on the Books page; no live API integration in M1.

## Later milestones
- M2: create schema/export SQL with sample data, implement BaseDao and individual DAOs using PDO prepared statements.
- M3: services, FlightPHP routes, OpenAPI/Swagger and external Open Library API.
- M4: JWT + admin/member RBAC, middleware/logging and AJAX-connected pages.
- M5: frontend MVC, validation and deployment.

## Security and AI
Never commit real `backend/config/config.php`, runtime logs or `backend/vendor/`. Use the provided `backend/config/config.example.php`. `AGENTS.md` documents architecture; `docs/AI_LOG.md` documents AI assistance and requires your real review notes.

## Submission status
- Repository: **add your GitHub URL here** after creating it.
- Swagger URL: **planned M3**.
- Deployment URL: **planned M5**.
- Test accounts: **planned M4**, not functional in M1.
