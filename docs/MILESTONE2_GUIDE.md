# ShelfWise Milestone 2 — Setup & submission

## What is included
- `database/shelfwise.sql`: six MySQL entities, keys, constraints and sample rows
- `backend/dao/BaseDao.php`: PDO connection, prepared queries, CRUD, transactions
- Six concrete DAOs matching the M1 ERD
- `backend/config/config.example.php`: updated configuration template
- `backend/dao/test_dao.php`: local read-only smoke test

## Merge into your project
Copy only `backend/`, `database/` and this guide into your **latest local ShelfWise repository**. Do not replace your frontend, `README.md`, `AGENTS.md` or current `docs/AI_LOG.md`.

## Run locally
1. Start Apache and MySQL in XAMPP.
2. In http://localhost/phpmyadmin choose Import and import `database/shelfwise.sql`.
3. Copy `backend/config/config.example.php` to `backend/config/config.php` and fill in local database connection settings. The real file is ignored by Git.
4. In PowerShell from repository root, run `php backend/dao/test_dao.php` (or `C:\xampp\php\php.exe backend/dao/test_dao.php`).
5. Check table contents using phpMyAdmin and inspect the DAO methods. DO NOT run the optional write test on valued data.
6. `git status` must not list `backend/config/config.php`.
7. Document your real AI tool use and any observed/fixed AI mistakes in `docs/AI_LOG.md` and update `AGENTS.md` as needed.

## Git workflow
Start from the correct base: if Milestone 1 PR was merged, use `git switch main; git pull; git switch -c milestone2`. If `milestone2` already exists use `git switch milestone2`. If M1 is not merged, check with the reviewer rather than overwriting the branch.
Commit and push: `git add -A; git commit -m "Milestone 2 - MySQL schema and DAO layer"; git push -u origin milestone2`.
Create PR `milestone2` -> `main`, assign `ibu-web-programming2001` as reviewer. Do not merge your own PR.
Deadline: Monday 2 November 2026 08:00 (local).

## Notes for defense
DAO methods run SQL, services (M3) enforce business logic, and routes (M3) map HTTP methods POST/GET/PUT/DELETE. M2 does not require creating those routes yet.
The sample passwords are placeholders, not working authentication accounts. The user DAO has `get_by_email` for later auth.
Delete behavior is constrained by foreign keys: you cannot delete a referenced user, book, category or loan without handling dependent records first; this is intentional.
The demo fine attached to an active loan is just sample data, not a rule for calculating fines.


### Additional CRUD verification
`php backend/dao/test_crud.php` creates, reads, updates, and deletes temporary records in **all six tables**, respecting foreign-key dependencies, then rolls back. This is an internal DAO test, not a FlightPHP REST endpoint. It still requires local MySQL and `backend/config/config.php`.

### Submission workflow
Work on branch `milestone2` created from the latest **approved/merged** `main`. Commit all M2 changes there, push and open a PR to `main`; request `ibu-web-programming2001` as reviewer. Do not merge your own PR. Before submission make sure `docs/AI_LOG.md` contains **real** issues and review notes.
