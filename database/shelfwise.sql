-- ShelfWise Milestone 2. Import in phpMyAdmin (Import tab).
CREATE DATABASE IF NOT EXISTS shelfwise CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shelfwise;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('admin','member') NOT NULL DEFAULT 'member'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 description TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS books (
 id INT AUTO_INCREMENT PRIMARY KEY,
 category_id INT NOT NULL,
 title VARCHAR(200) NOT NULL,
 author VARCHAR(160) NOT NULL,
 isbn VARCHAR(30) NOT NULL UNIQUE,
 total_copies INT NOT NULL DEFAULT 1,
 available_copies INT NOT NULL DEFAULT 1,
 CONSTRAINT fk_book_category FOREIGN KEY (category_id) REFERENCES categories(id),
 CONSTRAINT chk_book_total CHECK (total_copies >= 0),
 CONSTRAINT chk_book_available CHECK (available_copies >= 0 AND available_copies <= total_copies)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS loans (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 book_id INT NOT NULL,
 borrowed_at DATE NOT NULL,
 due_at DATE NOT NULL,
 returned_at DATE NULL,
 status ENUM('borrowed','returned','overdue') NOT NULL DEFAULT 'borrowed',
 CONSTRAINT fk_loan_user FOREIGN KEY (user_id) REFERENCES users(id),
 CONSTRAINT fk_loan_book FOREIGN KEY (book_id) REFERENCES books(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reservations (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 book_id INT NOT NULL,
 reserved_at DATE NOT NULL,
 status ENUM('pending','fulfilled','cancelled') NOT NULL DEFAULT 'pending',
 CONSTRAINT fk_reservation_user FOREIGN KEY (user_id) REFERENCES users(id),
 CONSTRAINT fk_reservation_book FOREIGN KEY (book_id) REFERENCES books(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fines (
 id INT AUTO_INCREMENT PRIMARY KEY,
 loan_id INT NOT NULL UNIQUE,
 amount DECIMAL(9,2) NOT NULL,
 status ENUM('unpaid','paid','waived') NOT NULL DEFAULT 'unpaid',
 CONSTRAINT fk_fine_loan FOREIGN KEY (loan_id) REFERENCES loans(id),
 CONSTRAINT chk_fine_amount CHECK (amount >= 0)
) ENGINE=InnoDB;

-- Demo users have non-login placeholder hashes. Real passwords will be created in M4.
INSERT IGNORE INTO users (id,full_name,email,password_hash,role) VALUES
 (1,'Library Admin','admin@shelfwise.test','NOT_A_REAL_HASH','admin'),
 (2,'Sample Member','member@shelfwise.test','NOT_A_REAL_HASH','member');
INSERT IGNORE INTO categories (id,name,description) VALUES
 (1,'Fiction','Novels and stories'), (2,'Technology','Computing and technology'),
 (3,'History','Historical studies');
INSERT IGNORE INTO books (id,category_id,title,author,isbn,total_copies,available_copies) VALUES
 (1,1,'Pride and Prejudice','Jane Austen','9780141439518',3,2),
 (2,2,'The Pragmatic Programmer','Andrew Hunt and David Thomas','9780135957059',2,2),
 (3,3,'A Short History of Nearly Everything','Bill Bryson','9780767908184',1,1);
INSERT IGNORE INTO loans (id,user_id,book_id,borrowed_at,due_at,returned_at,status) VALUES
 (1,2,1,'2026-10-20','2026-11-10',NULL,'borrowed');
INSERT IGNORE INTO reservations (id,user_id,book_id,reserved_at,status) VALUES
 (1,2,3,'2026-10-22','pending');
INSERT IGNORE INTO fines (id,loan_id,amount,status) VALUES
 (1,1,2.50,'unpaid');
