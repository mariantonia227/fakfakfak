document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modalLogin");
  const btnLogin = document.getElementById("btn-login");
  const btnCerrar = document.getElementById("btn-cerrar");

  if (btnLogin && modal) {
    btnLogin.addEventListener("click", (e) => {
      e.preventDefault();
      modal.style.display = "flex";
    });
  }

  if (btnCerrar && modal) {
    btnCerrar.addEventListener("click", () => {
      modal.style.display = "none";
    });
  }

  // Cierra el modal si se hace clic fuera de él
  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });



  
});
