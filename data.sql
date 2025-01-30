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

