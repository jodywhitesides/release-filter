document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.querySelector(".release-filter-dropdown");
    if (dropdown) {
        dropdown.addEventListener("change", function () {
            this.form.submit(); // Reloads page automatically when selection changes
        });
    }
});