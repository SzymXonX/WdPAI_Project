// Zmienianie menu na małej rozdzielczości
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebarMenu = document.getElementById("sidebar-menu");

    menuToggle.addEventListener("click", function () {
        sidebarMenu.classList.toggle("active");
    });
});

// obsługa zmiany daty i zmiany zawartości kontenerów kategori i sumy wydatków/przychodów
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

    // update wyświetlania daty
    function updateDateDisplay() {
        dateDisplay.textContent = `${currentYear} ${monthNames[currentMonth - 1]}`;
        dateDisplay.dataset.year = currentYear;
        dateDisplay.dataset.month = currentMonth;

        fetchSummaryData();
    }

    // pobieranie danych kategori i sumy wydatków/przychodów
    function fetchSummaryData() {
        fetch(`/summary?year=${currentYear}&month=${currentMonth}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.json())
        .then(data => {
            updateCategoryList("expenses-list", data.expensesSummary, true);
            updateCategoryList("incomes-list", data.incomesSummary, false);
        })
        .catch(error => console.error("Błąd w pobieraniu danych:", error));
    }
    
    // update listy kategorii
    function updateCategoryList(containerId, categories, isExpense) {
        const container = document.getElementById(containerId);
    
        if (!container) {
            console.error(`Element o ID ${containerId} nie istnieje w DOM!`);
            return;
        }
    
        container.innerHTML = "";
    
        const filteredCategories = categories.filter(category => parseFloat(category.total) > 0);
    
        if (filteredCategories.length === 0) {
            container.innerHTML = `<p class="no-transactions">Brak ${isExpense ? "wydatków" : "przychodów"} w tym miesiącu.</p>`;
            return;
        }
    
        filteredCategories.forEach(category => {
            const categoryElement = document.createElement("li");
            categoryElement.classList.add("category-summary-item");
            categoryElement.innerHTML = `
                <span class="category-name">${category.category}</span>
                <span class="summary-amount ${isExpense ? "negative" : "positive"}">
                    ${isExpense ? "-" : "+"}${parseFloat(category.total).toFixed(2)} zł
                </span>
            `;
            container.appendChild(categoryElement);
        });
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
