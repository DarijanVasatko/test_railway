document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const targetElement = document.querySelector(this.getAttribute("href"));
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    });

    function setupTableSearch(inputId, tableId) {
        const searchInput = document.getElementById(inputId);
        const table = document.getElementById(tableId);

        if (!searchInput || !table) return;

        searchInput.addEventListener("input", function () {
            const term = this.value.toLowerCase();
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName("td");
                const match = Array.from(cells).some(cell =>
                    cell.textContent.toLowerCase().includes(term)
                );
                row.style.display = match ? "" : "none";
            }
        });
    }

    setupTableSearch("productSearch", "productTable");
    setupTableSearch("userSearch", "userTable");

    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
            const fields = ["ID", "Naziv", "Opis", "Cijena", "Stanje", "Kategorija", "Tip"];
            fields.forEach(field => {
                const input = document.getElementById("editProizvod_" + field);
                if (input) input.value = this.getAttribute("data-" + field.toLowerCase());
            });

            const currentImage = document.getElementById("currentImage");
            const imgPath = this.getAttribute("data-slika");
            currentImage.innerHTML = imgPath
                ? `<img src="${imgPath}" class="product-img" alt="Current product image">`
                : `<span class="text-muted">No image</span>`;
        });
    });

    const editProductForm = document.getElementById("editProductForm");
    if (editProductForm) {
        editProductForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            await handleFormSubmission(this, "/zavrsni/admin/update_product.php");
        });
    }

    let productToDelete = null;
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            productToDelete = this.getAttribute("data-id");
            document.getElementById("productToDeleteId").textContent = productToDelete;
        });
    });

    const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener("click", async function () {
            if (!productToDelete) return;
            await handleDelete(
                this,
                `/zavrsni/admin/delete_product.php?id=${productToDelete}`,
                "deleteConfirmModal"
            );
            productToDelete = null;
        });
    }

    document.querySelectorAll(".edit-user-btn").forEach(button => {
        button.addEventListener("click", function () {
            const fields = ["ID", "Ime", "Prezime", "Email", "Username"];
            fields.forEach(field => {
                const input = document.getElementById("editKupac_" + field);
                if (input) input.value = this.getAttribute("data-" + field.toLowerCase());
            });
        });
    });

    const editUserForm = document.getElementById("editUserForm");
    if (editUserForm) {
        editUserForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            await handleFormSubmission(this, "../admin/update_user.php");
        });
    }

    let userToDelete = null;
    document.querySelectorAll(".delete-user-btn").forEach(button => {
        button.addEventListener("click", function () {
            userToDelete = this.getAttribute("data-id");
            document.getElementById("userToDeleteId").textContent = userToDelete;
        });
    });

    const confirmUserDeleteBtn = document.getElementById("confirmUserDeleteBtn");
    if (confirmUserDeleteBtn) {
        confirmUserDeleteBtn.addEventListener("click", async function () {
            if (!userToDelete) return;
            await handleDelete(this, `../admin/delete_user.php?id=${userToDelete}`, "deleteUserConfirmModal");


            userToDelete = null;
        });
    }

    function showToast(type, title, message) {
        const container = document.getElementById("toastContainer");
        if (!container) return;

        const toastId = "toast-" + Date.now();
        container.insertAdjacentHTML(
            "beforeend",
            `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `
        );

        const toastEl = document.getElementById(toastId);
        new bootstrap.Toast(toastEl).show();
        setTimeout(() => toastEl.remove(), 5000);
    }

    async function handleFormSubmission(form, url) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Saving...';

        try {
            const res = await fetch(url, {
                method: "POST",
                body: formData,
                headers: { Accept: "application/json" },
            });

            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            const result = await res.json();

            if (result.status === "success") {
                showToast("success", "Success", result.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast("danger", "Error", result.message);
            }
        } catch (err) {
            console.error(err);
            showToast("danger", "Error", "An error occurred. Check console.");
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-save me-1"></i> Save Changes';
        }
    }

    async function handleDelete(button, url, modalId) {
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Deleting...';

        try {
            const res = await fetch(url);
            const result = await res.json();

            if (result.status === "success") {
                showToast("success", "Deleted", result.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast("danger", "Error", result.message);
            }
        } catch (err) {
            console.error(err);
            showToast("danger", "Error", "Deletion failed. Check console.");
        } finally {
            const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
            if (modal) modal.hide();

            button.disabled = false;
            button.innerHTML = `<i class="bi bi-trash me-1"></i> Delete`;
        }
    }
});
 