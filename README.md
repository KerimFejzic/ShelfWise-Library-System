# ShelfWise — Library Management System

**IT 2001 Web Programming · Academic year 2026/27 · Milestones 1 and 2**

ShelfWise is a responsive, single-page library management application. Administrators will manage books, categories, members, loans, reservations and fines. Members will browse books and manage their loans and reservations. **Milestone 2 adds a real MySQL database and PHP PDO DAO layer; the frontend remains a static prototype until Milestone 4.**

## Project structure

- `frontend/` — HTML/CSS/JavaScript single-page prototype with views loaded using `fetch()`
- `backend/dao/` — shared `BaseDao.php` and six entity DAOs using prepared statements
- `backend/config/config.example.php` — example database configuration; copy to `config.php` locally
- `backend/routes/`, `backend/services/`, `backend/middleware/`, `backend/logs/` — reserved for later milestones
- `database/shelfwise.sql` — MySQL schema, relationships and six-entity sample records
- `docs/ERD.md` — textual entity-relationship diagram
- `docs/MILESTONE2_GUIDE.md` — full milestone setup instructions
- `docs/AI_LOG.md`, `AGENTS.md` — AI usage log and architecture conventions

## Run locally (Windows XAMPP)

1. Start **Apache** and **MySQL** from XAMPP Control Panel.
2. In `http://localhost/phpmyadmin`, use **Import** to import `database/shelfwise.sql` (creates `shelfwise`).
3. Copy `backend/config/config.example.php` to `backend/config/config.php`; adjust your local MySQL credentials if needed. **Never commit config.php.**
4. From the project root, test the DAO using:
   ```powershell
   C:\xampp\php\php.exe backend\dao\test_dao.php
   C:\xampp\php\php.exe backend\dao\test_crud.php
   ```
   The CRUD script runs all six DAO classes in a database transaction, then rolls it back. Do not run it against production data.
5. Visit `http://localhost/Kerims_Project/ShelfWise-Milestone-1/frontend/` if this is your actual XAMPP project path, or adapt the URL to your own folder. The frontend is **not yet connected to MySQL**.

## Database model

Six entities: `users`, `categories`, `books`, `loans`, `reservations`, `fines`. Every table has an `id` primary key. `books.category_id` references categories; loans and reservations reference users and books; fines reference loans. See `docs/ERD.md` for details.

## DAO operations

`BaseDao` implements `get_all()` (Read), `get_by_id()` (Read), `add()` (Create), `update()` (Update), `delete()` (Delete). Each entity DAO extends it, and SQL values are passed using PDO prepared statements. These are **database operations**, not HTTP endpoints. FlightPHP POST/GET/PUT/DELETE routes are planned for Milestone 3.

## Roles and future integrations

Roles: `admin` and `member` (stored in `users.role`). Sample users have **nonfunctional placeholder password hashes**; JWT authentication and test accounts will be implemented in Milestone 4. The Open Library third-party API integration belongs to Milestone 3 and will appear in the Books UI.

## Coming milestones

- **M3**: services, FlightPHP JSON CRUD routes, OpenAPI/Swagger and external API integration.
- **M4**: JWT, middleware, logs and fully connected AJAX frontend.
- **M5**: frontend MVC, validations and deployment.

## Repository / review

- Repository: https://github.com/KerimFejzic/ShelfWise-Library-System
- Submission branch: `milestone2` → pull request to `main`
- Reviewer: `ibu-web-programming2001`
- Swagger page: not implemented yet (M3)
- Deployed app: not implemented yet (M5)

**Review all changes before committing, and update `docs/AI_LOG.md` with the AI prompts and actual mistakes/fixes observed.**
