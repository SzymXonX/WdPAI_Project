// Zmienianie menu na małej rozdzielczości
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebarMenu = document.getElementById("sidebar-menu");

    menuToggle.addEventListener("click", function () {
        sidebarMenu.classList.toggle("active");
    });
});

// zmienianie ikony oka w polu hasła
document.addEventListener('DOMContentLoaded', function () {
    const eyeIcon = document.getElementById('eye');
    if (eyeIcon) {
        eyeIcon.addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.src = 'Public/images/open_eye_password.png';
            } else {
                passwordInput.type = 'password';
                eyeIcon.src = 'Public/images/closed_eye_password.png';
            }
        });
    }

    const confirmEyeIcon = document.getElementById('confirm-eye');
    if (confirmEyeIcon) {
        confirmEyeIcon.addEventListener('click', function () {
            const confirmPasswordInput = document.getElementById('confirm-password');
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmEyeIcon.src = 'Public/images/open_eye_password.png';
            } else {
                confirmPasswordInput.type = 'password';
                confirmEyeIcon.src = 'Public/images/closed_eye_password.png';
            }
        });
    }
});

// update ukrytych pól formularza
function updateHiddenFields() {
    document.getElementById("selected-year").value = currentYear;
    document.getElementById("selected-month").value = currentMonth;
}

// wywołanie funkcji updateHiddenFields po kliknięciu w przycisk dodawania transakcji
document.querySelector(".add-button").addEventListener("click", function () {
    updateHiddenFields();
});

// toggle wyświetlaniem przycisków
document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".transaction-type-btn");
    const hiddenInput = document.getElementById("transaction-type");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            buttons.forEach(btn => btn.classList.remove("active"));

            this.classList.add("active");

            hiddenInput.value = this.dataset.type;
        });
    });
});

// Cała logika zmiany daty i wyświelania podsumowania miesięcznego
document.addEventListener("DOMContentLoaded", function () {
    const monthNames = [
        "styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec",
        "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień"
    ];

    const dateDisplay = document.getElementById("current-date");
    const prevButton = document.getElementById("prev-month");
    const nextButton = document.getElementById("next-month");

    let currentYear = parseInt(dateDisplay.dataset.year);
    let currentMonth = parseInt(dateDisplay.dataset.month);

    function updateDateDisplay() {
        dateDisplay.textContent = `${currentYear} ${monthNames[currentMonth - 1]}`;
        dateDisplay.dataset.year = currentYear;
        dateDisplay.dataset.month = currentMonth;

        fetchSummaryData();
    }

    function fetchSummaryData() {
        fetch(`/main?year=${currentYear}&month=${currentMonth}`)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");

                document.getElementById("summary-wydatki").value = doc.getElementById("summary-wydatki").value;
                document.getElementById("summary-przychody").value = doc.getElementById("summary-przychody").value;
                document.getElementById("summary-budzet").value = doc.getElementById("summary-budzet").value;
            })
            .catch(error => console.error("Błąd w pobieraniu danych:", error));
    }

    prevButton.addEventListener("click", function () {
        if (currentMonth === 1) {
            currentMonth = 12;
            currentYear--;
        } else {
            currentMonth--;
        }
        updateDateDisplay();
    });

    nextButton.addEventListener("click", function () {
        if (currentMonth === 12) {
            currentMonth = 1;
            currentYear++;
        } else {
            currentMonth++;
        }
        updateDateDisplay();
    });

    updateDateDisplay();
});

// zmiana rozsuwanego menu kategorii zależnie od nacisniętego przycisku wyboru
document.addEventListener("DOMContentLoaded", function () {
    const transactionTypeButtons = document.querySelectorAll(".transaction-type-btn");
    const categorySelect = document.getElementById("category");
    const expenseCategories = document.getElementById("expense-categories");
    const incomeCategories = document.getElementById("income-categories");
    const hiddenInput = document.getElementById("transaction-type");

    transactionTypeButtons.forEach(button => {
        button.addEventListener("click", function () {
            transactionTypeButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            if (this.dataset.type === "expense") {
                expenseCategories.style.display = "block";
                incomeCategories.style.display = "none";
                hiddenInput.value = "expense";
            } else {
                expenseCategories.style.display = "none";
                incomeCategories.style.display = "block";
                hiddenInput.value = "income";
            }
        });
    });
});

