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
