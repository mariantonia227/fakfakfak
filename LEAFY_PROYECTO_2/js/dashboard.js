function toggleMenu() {
    const dropdown = document.getElementById("dropdownMenu");
    dropdown.classList.toggle("active");
}

document.addEventListener("click", function(event) {
    const menu = document.querySelector(".user-menu");
    const dropdown = document.getElementById("dropdownMenu");

    if (!menu.contains(event.target)) {
        dropdown.classList.remove("active");
    }
});

function showToast(message, type = "info") {

    const container = document.getElementById("toast-container");

    const toast = document.createElement("div");
    toast.classList.add("toast", type);

    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">✖</button>
    `;

    container.appendChild(toast);

    // Auto eliminar
    setTimeout(() => {
        toast.classList.add("hide");
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}
