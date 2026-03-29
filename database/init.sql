CREATE TABLE IF NOT EXISTS persons (
	id SERIAL PRIMARY KEY,
	first_name VARCHAR(80) NOT NULL,
	last_name VARCHAR(80) NOT NULL,
	email VARCHAR(180) NOT NULL UNIQUE
);

INSERT INTO persons (first_name, last_name, email)
VALUES ('Alice', 'Martin', 'alice.martin@example.com'), ('Karim', 'Benali', 'karim.benali@example.com'), ('Sofia', 'Rossi', 'sofia.rossi@example.com');
