# ShelfWise — AI Coding Rules

## Project
ShelfWise is a responsive library management single-page application for IT 2001 Web Programming (2026/27).

## Stack
Frontend: plain HTML, CSS, JavaScript; optional Bootstrap later. Backend: PHP 8.2+, FlightPHP REST, MySQL using PDO; JWT authentication planned for Milestone 4.

## Architecture
- Keep frontend in `frontend/` and backend in `backend/`; never mix PHP with frontend HTML.
- Backend layers: `routes` accepts HTTP and returns JSON; `services` applies rules; `dao` is the only place for PDO SQL.
- All queries use PDO prepared statements, not string-concatenated user data.
- Frontend uses `fetch()` to call REST endpoints when APIs are implemented; Milestone 1 uses explicitly labeled sample data only.
- Use clear, small, readable functions; prefer topics demonstrated in Labs 01–03, and explain any additions.
- No unnecessary frameworks, build systems, databases or advanced patterns.
- Do not commit secrets, real user data, logs, or vendor packages.

## Naming
- DB tables: plural snake_case (`books`, `loans`).
- JS functions/variables: camelCase. CSS classes: kebab-case.
- PHP classes: PascalCase (`BookDao`, `BookService`).

## Milestones
M1 is planning and static SPA only. M2 adds schema and DAO. M3 adds services/routes/OpenAPI/external API. M4 adds authentication/middleware/logging and connects UI. M5 adds validation and deployment.

## AI review
Explain any proposed changes in simple terms and log AI usage in `docs/AI_LOG.md`. Never pretend untested features work.
