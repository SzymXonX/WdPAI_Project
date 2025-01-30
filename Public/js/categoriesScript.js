// Zmienianie menu na małej rozdzielczości
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebarMenu = document.getElementById("sidebar-menu");

    menuToggle.addEventListener("click", function () {
        sidebarMenu.classList.toggle("active");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebarMenu = document.getElementById("sidebar-menu");
    const transactionButtons = document.querySelectorAll(".transaction-type-btn");
    const categoryTypeInput = document.getElementById("category-type");

    menuToggle.addEventListener("click", function () {
        sidebarMenu.classList.toggle("active");
    });

    transactionButtons.forEach(button => {
        button.addEventListener("click", function () {
            transactionButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");
            categoryTypeInput.value = this.dataset.type;
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebarMenu = document.getElementById("sidebar-menu");

    menuToggle.addEventListener("click", function () {
        sidebarMenu.classList.toggle("active");
    });
});

function deleteCategory(categoryId, type) {
    if (!confirm("Czy na pewno chcesz usunąć tę kategorię?")) {
        return;
    }

    fetch('/deleteCategory', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: categoryId, type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Kategoria została usunięta.");
            location.reload();
        } else {
            alert("Błąd: " + data.message);
        }
    })
    .catch(error => console.error("Błąd:", error));
}
