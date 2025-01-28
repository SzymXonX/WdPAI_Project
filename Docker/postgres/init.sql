CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_name VARCHAR(255),
    last_name VARCHAR(255)
);

-- Insert sample data into the 'users' table
INSERT INTO users (email, password, first_name, last_name) VALUES
('koczurszymon@gmail.com', '1234', 'Szymon', 'Koczur');
