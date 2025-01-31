// Zmienianie menu na małej rozdzielczości
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebarMenu = document.getElementById("sidebar-menu");

    menuToggle.addEventListener("click", function () {
        sidebarMenu.classList.toggle("active");
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const editModal = document.getElementById("edit-user-modal");
    const editForm = document.getElementById("edit-user-form");

    window.editUser = function (userId) {
        const editModal = document.getElementById("edit-user-modal");
    
        if (editModal.style.display === "flex") {
            closeEditModal();
            return;
        }
    
        fetch(`/getUser?id=${userId}`)
            .then(response => {
                if (!response.ok) throw new Error("Błąd serwera");
                return response.json();
            })
            .then(user => {
                document.getElementById("edit-user-id").value = user.id;
                document.getElementById("edit-first-name").value = user.first_name;
                document.getElementById("edit-last-name").value = user.last_name;
                document.getElementById("edit-email").value = user.email;
                document.getElementById("edit-role").value = user.role;
                editModal.style.display = "flex";
            })
            .catch(error => alert("Błąd pobierania danych: " + error.message));
    };
    
    window.closeEditModal = function () {
        document.getElementById("edit-user-modal").style.display = "none";
    };
    

    editForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const userId = document.getElementById("edit-user-id").value;
        const firstName = document.getElementById("edit-first-name").value;
        const lastName = document.getElementById("edit-last-name").value;
        const email = document.getElementById("edit-email").value;
        const role = document.getElementById("edit-role").value;

        fetch("/updateUser", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: userId, first_name: firstName, last_name: lastName, email, role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Dane użytkownika zaktualizowane!");
                location.reload();
            } else {
                alert("Błąd aktualizacji: " + data.message);
            }
        })
        .catch(error => alert("Błąd komunikacji z serwerem: " + error.message));
    });

    window.deleteUser = function (userId) {
        if (!confirm("Czy na pewno chcesz usunąć tego użytkownika?")) return;

        fetch(`/deleteUser?id=${userId}`, { method: "DELETE" })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Użytkownik został usunięty.");
                    location.reload();
                } else {
                    alert("Nie można usunąć użytkownika: " + data.message);
                }
            })
            .catch(error => alert("Błąd usuwania użytkownika: " + error.message));
    };

    document.querySelectorAll(".delete-button").forEach(button => {
        button.addEventListener("click", function () {
            const userId = this.getAttribute("data-user-id");
            deleteUser(userId);
        });
    });
});

