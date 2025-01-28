CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_name VARCHAR(255),
    last_name VARCHAR(255)
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE expenses (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE incomes (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    source VARCHAR(255) NOT NULL,
    description TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO categories (name) VALUES
('Jedzenie'),
('Transport'),
('Zakupy'),
('Mieszkanie'),
('Zdrowie'),
('Rozrywka');

INSERT INTO expenses (user_id, amount, category_id, description) VALUES
(1, 50.00, 1, 'Obiad w restauracji'),
(1, 80.00, 2, 'Bilet miesięczny Kraków'),
(1, 200.00, 3, 'Spodnie w Reserved');

INSERT INTO incomes (user_id, amount, source, description) VALUES
(1, 3000.00, 'Pensja', 'Wynagrodzenie za pracę'),
(1, 500.00, 'Dodatkowa praca', 'Pomalowanie sąsiadowi płotu'),
(1, 200.00, 'Dodatkowa praca', 'Posprzątanie babci piwnicy');

