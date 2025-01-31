/* Tabela użytkowników */
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    role VARCHAR(40) DEFAULT 'user'
);

/* Tabela kategorii */
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);


CREATE TABLE income_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);


/* Tabela wydatków */
CREATE TABLE expenses (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    date TIMESTAMP(0) DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

/* Tabela przychodów */
CREATE TABLE incomes (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    date TIMESTAMP(0) DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES income_categories(id) ON DELETE CASCADE
);

/* Tabela sumaryczna wydatki przychody i budżet */
CREATE TABLE summary (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    year INT NOT NULL,
    month INT NOT NULL,
    total_income DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_expense DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    budget DECIMAL(10,2) GENERATED ALWAYS AS (total_income - total_expense) STORED,
    UNIQUE (user_id, year, month),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

/* Trigger UPDATE wydatków */
CREATE OR REPLACE FUNCTION update_summary_expense()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO summary (user_id, year, month, total_expense)
    VALUES (
        NEW.user_id,
        EXTRACT(YEAR FROM NEW.date),
        EXTRACT(MONTH FROM NEW.date),
        NEW.amount
    )
    ON CONFLICT (user_id, year, month) 
    DO UPDATE SET 
        total_expense = summary.total_expense + NEW.amount;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_expense_insert
AFTER INSERT ON expenses
FOR EACH ROW EXECUTE FUNCTION update_summary_expense();

/* Trigger DELETE wydatków */
CREATE OR REPLACE FUNCTION update_summary_expense_delete()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE summary
    SET total_expense = total_expense - OLD.amount
    WHERE user_id = OLD.user_id 
    AND year = EXTRACT(YEAR FROM OLD.date) 
    AND month = EXTRACT(MONTH FROM OLD.date);

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_expense_delete
AFTER DELETE ON expenses
FOR EACH ROW EXECUTE FUNCTION update_summary_expense_delete();

/* Trigger UPDATE przychodów */
CREATE OR REPLACE FUNCTION update_summary_income()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO summary (user_id, year, month, total_income)
    VALUES (
        NEW.user_id,
        EXTRACT(YEAR FROM NEW.date),
        EXTRACT(MONTH FROM NEW.date),
        NEW.amount
    )
    ON CONFLICT (user_id, year, month) 
    DO UPDATE SET 
        total_income = summary.total_income + NEW.amount;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/* Trigger DELETE przychodów */
CREATE TRIGGER after_income_insert
AFTER INSERT ON incomes
FOR EACH ROW EXECUTE FUNCTION update_summary_income();

CREATE OR REPLACE FUNCTION update_summary_income_delete()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE summary
    SET total_income = total_income - OLD.amount
    WHERE user_id = OLD.user_id 
    AND year = EXTRACT(YEAR FROM OLD.date) 
    AND month = EXTRACT(MONTH FROM OLD.date);

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_income_delete
AFTER DELETE ON incomes
FOR EACH ROW EXECUTE FUNCTION update_summary_income_delete();



/* Funckja do pobierania roli */
CREATE OR REPLACE FUNCTION getRole(user_id INT) 
RETURNS VARCHAR(40) AS 
$$
DECLARE 
    user_role VARCHAR(40);
BEGIN
    SELECT role INTO user_role 
    FROM users 
    WHERE id = user_id;

    RETURN COALESCE(user_role, 'brak użytkownika'); 
END;
$$ LANGUAGE plpgsql;


/* Widoki */
CREATE VIEW user_financial_summary AS
SELECT 
    u.id AS user_id,
    u.first_name || ' ' || u.last_name AS full_name,
    u.email,
    COALESCE(SUM(s.total_expense), 0) AS total_expenses,
    COALESCE(SUM(s.total_income), 0) AS total_income
FROM users u
LEFT JOIN summary s ON u.id = s.user_id
GROUP BY u.id, u.first_name, u.last_name, u.email;


CREATE VIEW category_financial_summary AS
SELECT 
    c.id AS category_id,
    c.name AS category_name,
    'expense' AS transaction_type,
    COALESCE(SUM(e.amount), 0) AS total_amount
FROM categories c
LEFT JOIN expenses e ON c.id = e.category_id
GROUP BY c.id, c.name

UNION ALL

SELECT 
    ic.id AS category_id,
    ic.name AS category_name,
    'income' AS transaction_type,
    COALESCE(SUM(i.amount), 0) AS total_amount
FROM income_categories ic
LEFT JOIN incomes i ON ic.id = i.category_id
GROUP BY ic.id, ic.name;



insert into users (email, password, first_name, last_name, role) VALUES
('koczurszymon@gmail.com', '$2y$10$kM7dEyWUU.f3/q3C36qccOf62bnuv.eGYQylEPTEZkeLhI62x0xZS',	'Szymon',	'Koczur',	'user'),
('admin@example.com',	'$2y$10$ngppWi5X19.5jL9NmMwaNuygZ/rREhoM/tRwdgZ.IxwTtHY/63tgu',	'Admin',	'Admin',	'admin'),
('maria@o2.pl',	'$2y$10$VB2RutgRbJKCBlIszKrGQ.KlvMQOi7wf9P11fNyhuVH/ziGKIFDw6',	'Maria',	'Koczur',	'user');

/*  Dodanie przykładowych danych  */

/*  KATEGORIE  */
INSERT INTO categories (name) VALUES
('Jedzenie'),
('Transport'),
('Zakupy'),
('Mieszkanie'),
('Zdrowie'),
('Rozrywka'),
('Inne');

INSERT INTO income_categories (name) VALUES
('Pensja'),
('Premia'),
('Dodatkowa praca'),
('Prezenty'),
('Inne');


/*  WYDATKI I PRZYCHODY ADMIN */
INSERT INTO expenses (user_id, amount, category_id, description, date) VALUES 
(1, 120.00, 1, 'Kolacja w restauracji', '2024-12-12 14:30:00'),
(1, 200.00, 2, 'Paliwo do samochodu', '2024-12-25 15:30:00'),
(1, 350.00, 3, 'Nowe buty zimowe', '2025-01-05 16:30:00'),
(1, 2800.00, 4, 'Czynsz za mieszkanie', '2025-01-15 17:30:00' ),
(1, 90.00, 5, 'Leki na przeziębienie', '2025-02-08 10:30:00'),
(1, 160.00, 6, 'Bilet do teatru', '2025-02-18 08:30:00'),
(1, 45.00, 7, 'Gazeta i kawa', '2024-12-10 11:30:00');

INSERT INTO incomes (user_id, amount, category_id, description, date) VALUES 
(1, 7500.00, 1, 'Wynagrodzenie za pracę', '2024-12-10 22:00:00'),
(1, 600.00, 2, 'Premia roczna', '2025-01-10 21:00:00'),
(1, 7500.00, 1, 'Wynagrodzenie za pracę', '2025-01-10 22:00:00'),
(1, 7500.00, 1, 'Wynagrodzenie za pracę', '2025-02-10 10:00:00');


/* WYDATKI USER 2 */
INSERT INTO expenses (user_id, amount, category_id, description, date) VALUES 
(2, 120.00, 1, 'Kolacja w restauracji', '2024-12-10 03:58:49'),
(2, 200.00, 2, 'Paliwo do samochodu', '2024-12-20 10:47:23'),
(2, 350.00, 3, 'Nowe buty zimowe', '2025-01-05 16:28:36'),
(2, 2800.00, 4, 'Czynsz za mieszkanie', '2025-01-15 13:56:15'),
(2, 90.00, 5, 'Leki na przeziębienie', '2025-02-08 15:18:49'),
(2, 160.00, 6, 'Bilet do teatru', '2025-02-18 06:44:27'),
(2, 45.00, 7, 'Gazeta i kawa', '2024-12-10 12:43:00');

/* PRZYCHODY USER 2 */
INSERT INTO incomes (user_id, amount, category_id, description, date) VALUES 
(2, 7500.00, 1, 'Wynagrodzenie za pracę', '2024-12-10 13:25:46'),
(2, 600.00, 2, 'Premia roczna', '2024-12-20 17:56:48'),
(2, 150.00, 3, 'Sprzedaż niepotrzebnych rzeczy', '2025-01-05 16:43:15');

/* WYDATKI USER 3 */
INSERT INTO expenses (user_id, amount, category_id, description, date) VALUES 
(3, 80.00, 1, 'Śniadanie na mieście', '2024-12-10 13:51:21'),
(3, 220.00, 2, 'Naprawa samochodu', '2024-12-20 06:48:26'),
(3, 400.00, 3, 'Nowa kurtka', '2025-01-05 16:27:04'),
(3, 2600.00, 4, 'Czynsz za mieszkanie', '2025-01-15 04:40:40'),
(3, 120.00, 5, 'Wizyta u dentysty', '2025-02-08 04:23:52'),
(3, 130.00, 6, 'Bilet na mecz', '2025-02-18 02:47:53'),
(3, 30.00, 7, 'Książka do nauki', '2024-12-10 14:32:08');

/* PRZYCHODY USER 3 */
INSERT INTO incomes (user_id, amount, category_id, description, date) VALUES 
(3, 7800.00, 1, 'Wynagrodzenie miesięczne', '2024-12-10 07:55:06'),
(3, 550.00, 2, 'Bonus za wyniki w pracy', '2024-12-20 06:23:38'),
(3, 200.00, 3, 'Praca dodatkowa', '2025-01-05 04:19:37');

