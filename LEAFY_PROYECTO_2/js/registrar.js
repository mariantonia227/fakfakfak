document.addEventListener("DOMContentLoaded", function() {

    const tipoUsuario = document.getElementById("tipo");
    const camposNegocio = document.getElementById("campos-negocio");

    tipoUsuario.addEventListener("change", function() {

        if (this.value === "negocio") {
            camposNegocio.style.display = "flex";
            camposNegocio.style.flexDirection = "column";
            camposNegocio.style.gap = "15px";

            document.getElementById("nombre_negocio").required = true;
            document.getElementById("telefono").required = true;
            document.getElementById("ciudad").required = true;

        } else {
            camposNegocio.style.display = "none";

            document.getElementById("nombre_negocio").required = false;
            document.getElementById("telefono").required = false;
            document.getElementById("ciudad").required = false;
        }

    });

});