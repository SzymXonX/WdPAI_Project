# SaveSpace
SaveSpace to aplikacja do zarządzania wydatkami i przychodami, umożliwiająca użytkownikom śledzenie ich finansów, kategoryzację transakcji oraz analizę budżetu.

# Pobranie aplikacji:
```
$ git clone https://github.com/SzymXonX/WdPAI_Project.git
```

# Uruchamianie:
1. Otwórz pobrane repozytorium w np. Visual Studio Code
2. Uruchom aplikacje Docker
3. Zbuduj docker-compose
```
$ docker-compose up --build
```
4. Po załadowaniu wszystkich kontenerów i serwisów udaj się w przeglądarce na adres http://localhost:8080/
5. Zaloguj się na utworzonych już użytkowników lub utwórz własne konto za pomocą "Nie masz konta?"
6. Korzystaj z aplikacji! 

# Dostępni użytkownicy
## Admin:
Login: admin@example.com
Hasło: admin

## Userzy:
Login: koczurszymon@gmail.com
Hasło: 1234

Login: maria@o2.pl
Hasło: maria123

Na tych kontach znajdują się już przykładowe dane

# Opis działania podstron
## Strona główna
Na stronie głównej wyświetla się podsumowanie sumaryczne wybranego miesiąca wydatków, przychodów, budżetu oraz jednostkowe wydatki i przychody

W formularzu w prawym górnym można dodawać przychody lub wydatki

Za pomocą strzałek przy dacie można zmieniać miesiące

Aby wyświetlić opis wydatku wystarczy w niego kliknąć, 
znajduje się tam również przycisk usuwania wydatku/przychodu

!! UWAGA !! Usunięcie starego wydatku/przychodu skutkuje brakiem możliwości utworzenia go ponownie w danym czasie !! UWAGA !!

## Kategorie
W zakładce kategorie wyświetlają się kategorie wydatków i przychodów

W formularzu w górnej części ekranu można dodawać kategorię do obydwu grup

Za pomocą przycisku usuń usuwamy kategorię 

!! UWAGA !! Usunięcie kategorii skutuję usunięciem wszystkich wydatków/przychodów z nią związanych !! UWAGA !!

## Podsumowanie
Tutaj wyświetla się podsumowanie kategorii i sumaryczny wydatek w danym miesiącu na każdą kategorię

Przy dacie za pomocą strzałek również można zmieniać datę podsumowania

# Technologie
- HTML
- PHP
- CSS
- JavaScript
- PostgreSQL
- PDO
- nginx
- pgAdmin

# Logowanie do pgAdmin'a
1. Aby dostać się do pgAdmina wpisz w przeglądarke localhost:5050/
2. Zaloguj sie: Login - support@savespace.com    Hasło - password
3. Utwórz nowy serwer: 
Name - [nasza nazwa serwera], 
Connections/Hostname - postgres, 
Connection/Username - user,
Connection/Password - password
4. Gotowe, rozwiń zakładkę Servers/Database/Savespace

# Struktura katalogów
```
/Docker
    /nginx
        - Dockerfile
        - nginx.conf
    /php
        - Dockerfile
    /postgres
        - init.sql

/Public
    /css
        - categoriesStyles.css
        - mainStyles.css
        - settingsStyles.css
        - styles.css
        - summaryStyles.css
    /Images
        - closed_eye_password.png
        - logo_bez_tla.png
        - logo.png
        - open_eye_password.png
    /js
        - categoriesScript.js
        - menuScript.js
        - script.js
        - summaryScript.js
    /views
        - categories.php
        - login.php
        - main.php
        - register.php
        - settings.php
        - summary.php
/ScreenShots
    - category.png
    - login.png
    - mainPage.png
    - register.png
    - settings.png
    - summary.png

/Src
    /controllers
        - AppController.php
        - CategoriesController.php
        - DefaultController.php
        - MainController.php
        - SecurityController.php
        - SettingsController.php
        - SummaryController.php
    /models
        - Category.php
        - Expense.php
        - Income.php
        - User.php

- .gitignore
- data.sql
- Database.php
- docker-compose.yml
- index.php
- initialize_db.php
- README.MD
- Routing.php
```
# Zrzuty ekranu strony
![Logowanie](ScreenShots/login.png)
![Rejestracja](ScreenShots/register.png)
![Strona główna](ScreenShots/mainPage.png)
![Kategorie](ScreenShots/category.png)
![Podsumowanie](ScreenShots/summary.png)
![Ustawienia](Settings/category.png)


# Autor
- Szymon Koczur
