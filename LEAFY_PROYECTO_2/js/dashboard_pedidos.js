document.addEventListener("DOMContentLoaded", function () {

    const buscadorPedido = document.getElementById("buscarPedido");

    if (buscadorPedido) {

        buscadorPedido.addEventListener("keyup", function () {

            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll(".tabla-pedidos tbody tr");

            filas.forEach(function (fila) {

                // Si es la fila de "No hay pedidos", no la tocamos
                if (fila.classList.contains("sin-pedidos")) return;

                let texto = fila.textContent.toLowerCase();

                if (texto.includes(filtro)) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }

            });

        });

    }

});