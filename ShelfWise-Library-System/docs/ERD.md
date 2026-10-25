# ShelfWise — Draft ERD (Milestone 1)

The schema is a **draft**, not yet created in MySQL. Primary keys are `PK`, foreign keys `FK`. It has six entities.

```mermaid
erDiagram
    USERS ||--o{ LOANS : borrows
    USERS ||--o{ RESERVATIONS : makes
    CATEGORIES ||--o{ BOOKS : classifies
    BOOKS ||--o{ LOANS : appears_in
    BOOKS ||--o{ RESERVATIONS : reserved_in
    LOANS ||--o| FINES : may_have
    USERS {
        int id PK
        varchar full_name
        varchar email UK
        varchar password_hash
        enum role
    }
    CATEGORIES {
        int id PK
        varchar name UK
        text description
    }
    BOOKS {
        int id PK
        int category_id FK
        varchar title
        varchar author
        varchar isbn UK
        int total_copies
        int available_copies
    }
    LOANS {
        int id PK
        int user_id FK
        int book_id FK
        date borrowed_at
        date due_at
        date returned_at
        enum status
    }
    RESERVATIONS {
        int id PK
        int user_id FK
        int book_id FK
        date reserved_at
        enum status
    }
    FINES {
        int id PK
        int loan_id FK
        decimal amount
        enum status
    }
```

## Relationships and scope
- One category has many books; each book belongs to one category.
- One user can have many loans and reservations; a loan/reservation belongs to one book.
- A loan may have zero or one fine; a fine belongs to one loan.
- `users.role` is `admin` or `member`. An admin manages library records; a member browses books and their loans/reservations.
- Later CRUD planning: users, categories, books, loans, reservations, fines each get CRUD endpoints through the REST API, subject to permissions.
- **External API planned for M3:** Open Library book search/metadata, shown on the Books page. The app must work when the API is unavailable.
