# Milestone 3 — ShelfWise API

Based on Labs 04 (business services), 05 (FlightPHP presentation layer), 06 (OpenAPI and Swagger).
Labs 07–08 concern JWT/auth/roles and are **not implemented in Milestone 3**.

## Prerequisites and commands (Windows XAMPP)
1. Start Apache and MySQL in XAMPP. The database from Milestone 2 must already be imported (`database/shelfwise.sql`).
2. In `backend`, copy `config/config.example.php` to `config/config.php` and edit credentials. This file is ignored by Git.
3. Install Composer on Windows if it isn't installed (https://getcomposer.org/download/). In `backend/` run `composer install` (if there is no lock yet, Composer resolves versions and creates `composer.lock`; commit that lock file after verification). Requires PHP >=8.2, `pdo_mysql`.
4. Check the API in your local Apache URL: `http://localhost/<your-folder>/backend/api/health`.
5. Swagger UI: `http://localhost/<your-folder>/backend/public/v1/docs/` and raw OpenAPI: `.../swagger.php`.
6. Test the API with Postman, using the `/api/` prefix. `/api/books` reads records, `/api/books/1` reads one. POST/PUT/DELETE should only be tested on records created specifically for testing, and foreign-key constraints may reject deletes.
7. Open Library test: `GET /api/openlibrary/9780141439518` (requires Internet and HTTPS certificates).

## Architecture
Routes (`backend/routes/ApiRoutes.php`) receive JSON and return JSON with HTTP status codes. Services (`backend/services/`) validate data and enforce business rules; DAOs (`backend/dao/`) execute prepared SQL. Swagger docs under `backend/public/v1/docs/` use PHP 8 attributes from Lab 06.

## Extra offline check
Run `C:\xampp\php\php.exe backend\tests\test_validation.php` from project root to test the Validator without MySQL or Composer.

## API list
Six resources (`users`, `categories`, `books`, `loans`, `reservations`, `fines`) expose GET list, GET one, POST, PUT and DELETE; there is also `GET /api/health` and `GET /api/openlibrary/{isbn}`. Invalid JSON => 400, missing entity => 404, database conflict => 409, external API unavailable => 502, unexpected server error => 500.

## Example POST book
`POST /api/books`, Content-Type `application/json`:
```json
{"category_id":1,"title":"Sample title","author":"Sample Author","isbn":"9781234567897","total_copies":2}
```

## Safety and limitations
- **Milestone 3 API is not authenticated**. Do not deploy it publicly. Lab 07 will add JWT middleware and Lab 08 will add role and ownership checks.
- The Milestone 1 frontend is still a static prototype and is not connected to this API; that is a later milestone.
- Sample users in SQL contain placeholder password hashes, not login credentials. User creation hashes the supplied `password`, but login is intentionally not offered in M3.
- The external Open Library lookup fetches metadata only; it does not import a book or modify your database.
- If PHP errors report missing vendor/autoload.php, run Composer install in `backend/`.
- Database tests from Milestone 2 still exist under `backend/dao/`.

## GitHub submission
Make sure you are using the latest repository files; extract this full project and **review differences** before replacing your modified project. Work on `milestone3`, commit after tests, push and open `milestone3` → `main` PR; request `ibu-web-programming2001` as reviewer. Do not merge your own PR. Update `docs/AI_LOG.md` with real results and two actual AI mistakes you identified and corrected (do not invent mistakes).
