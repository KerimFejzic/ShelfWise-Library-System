# ShelfWise — Library Management System

IT 2001 Web Programming (2026/27), complete starter through **Milestone 3**.

**Frontend:** static responsive HTML/CSS/JS prototype from M1. **Database:** six MySQL tables with sample records. **DAO:** PDO CRUD from M2. **Services:** validation, domain checks, and transaction-based loan inventory updates. **API:** FlightPHP JSON CRUD routes for all six entities. **Docs:** Swagger/OpenAPI PHP 8 attributes. **Third-party API:** Open Library ISBN metadata.

## Get started
Read [Milestone 3 setup and test guide](docs/MILESTONE3_GUIDE.md). Requires PHP 8.2+, XAMPP MySQL and Apache, Composer and `composer install` in `backend`.

For example, locally with the repository folder under `htdocs`:
- `http://localhost/<your-path>/ShelfWise-Library-System/backend/api/books`
- `http://localhost/<your-path>/ShelfWise-Library-System/backend/public/v1/docs/`

## Security boundaries
Milestone 3 is for **local development**. API operations are not authenticated until Milestone 4 (Labs 07–08). Never expose this demo publicly or use real passwords. `backend/config/config.php`, `backend/vendor/` and runtime logs are excluded from Git.

## Submission
Branch `milestone3`, PR to `main`, request reviewer `ibu-web-programming2001`. `docs/AI_LOG.md` must be completed using real reviews and observed mistakes.
