document.addEventListener("DOMContentLoaded", () => {
  
    // ==============================
    // Validación formulario de LOGIN
    // ==============================
  
    if (document.body.id === "loginPage") {
        const formLogin = document.querySelector("form[action='index_logueado.html']");

        if (formLogin) {
            formLogin.addEventListener("submit", function (event) {
            let usuario = formLogin.querySelector("#usuario").value.trim();
            let clave = formLogin.querySelector("#clave").value.trim();
            let errores = [];

            if (usuario === "") {
                errores.push("El nombre de usuario no puede estar vacío.");
            }

            if (clave === "") {
                errores.push("La contraseña no puede estar vacía.");
            }

            if (errores.length > 0) {
                event.preventDefault(); // evita que se envíe el formulario
                alert(errores.join("\n"));
            }
            });
        }
    }

    // =================================
    // Validación formulario de REGISTRO
    // =================================

    if (document.body.id === "registroPage") {
        const formRegistro = document.querySelector("form[action='index_logueado.html']");
        
        if (!formRegistro) return; // Si no estamos en registro.html, no hace nada

        formRegistro.addEventListener("submit", (e) => {
            e.preventDefault();
            let valido = true;

            // Limpia los mensajes de error previos
            formRegistro.querySelectorAll(".error").forEach(span => span.textContent = "");

            // Función auxiliar para mostrar errores
            const mostrarError = (idCampo, mensaje) => {
            const campo = document.getElementById(idCampo);
            const spanError = campo.nextElementSibling;
            if (spanError && spanError.classList.contains("error")) {
                spanError.textContent = mensaje;
            }
            valido = false;
            };

            // Obtener valores
            const usuario = document.getElementById("usuario").value.trim();
            const clave = document.getElementById("clave").value.trim();
            const clave2 = document.getElementById("clave2").value.trim();
            const email = document.getElementById("email").value.trim();
            const nacimiento = document.getElementById("nacimiento").value.trim();
            const ciudad = document.getElementById("ciudad").value.trim();
            const pais = document.getElementById("pais").value.trim();
            const sexo = formRegistro.querySelector("#sexo").value.trim();
            const foto = formRegistro.querySelector("#foto").value.trim();

            // Validaciones básicas
            if (usuario === "" || usuario.length > 200) {
            mostrarError("usuario", "El nombre de usuario es obligatorio (máx. 200 caracteres).");
            }

            if (clave === "" || clave.length < 6) {
            mostrarError("clave", "La contraseña debe tener al menos 6 caracteres.");
            }

            if (clave2 !== clave) {
            mostrarError("clave2", "Las contraseñas no coinciden.");
            }

            const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
            if (email === "" || !emailRegex.test(email)) {
            mostrarError("email", "Debe introducir un correo electrónico válido.");
            }

            if (!sexo) {
            // Se busca el último radio del grupo para colocar el mensaje después
            const span = formRegistro.querySelector("input[name='sexo']").parentNode.querySelector(".error");
            if (span) span.textContent = "Debe seleccionar un sexo.";
            valido = false;
            }

            const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (nacimiento === "" || !fechaRegex.test(nacimiento)) {
            mostrarError("nacimiento", "Debe introducir una fecha válida (formato MM-DD-YYYY).");
            }

            if (ciudad === "") {
            mostrarError("ciudad", "Debe introducir una ciudad.");
            }

            if (pais === "") {
            mostrarError("pais", "Debe seleccionar un país.");
            }

            if (foto === "") {
            mostrarError("foto", "Debe seleccionar una imagen como foto de perfil.");
            }

            // Si todo es válido, se envía
            if (valido) {
            formRegistro.submit();
            }
        });
    }

    // =========================================
    // Validación y cálculo de SOLICITAR FOLLETO
    // =========================================

    if (document.body.id === "folletoPage") {
        const formFolleto = document.querySelector("form[action='respuesta_folleto.html']");

        if (formFolleto) {
            formFolleto.addEventListener("submit", function (event) {
            let errores = [];

            // --- Campos base ---
            const nombre = formFolleto.querySelector("#nombre").value.trim();
            const email = formFolleto.querySelector("#email").value.trim();
            const calle = formFolleto.querySelector("#calle").value.trim();
            const numero = formFolleto.querySelector("#numero").value.trim();
            const codigoPostal = formFolleto.querySelector("#codigo_postal").value.trim();
            const localidad = formFolleto.querySelector("#localidad").value.trim();
            const provincia = formFolleto.querySelector("#provincia").value.trim();
            const pais = formFolleto.querySelector("#pais").value.trim();
            const numCopias = parseInt(formFolleto.querySelector("#num_copias").value);
            const resolucion = parseInt(formFolleto.querySelector("#resolucion").value);
            const anuncio = formFolleto.querySelector("#anuncio").value;
            const impresionColor = formFolleto.querySelector("input[name='impresion_color']:checked").value;

            // --- VALIDACIONES ---

            // Nombre
            if (nombre === "" || nombre.length > 200) {
                errores.push("El nombre es obligatorio y no debe superar 200 caracteres.");
            }

            // Email
            const regexEmail = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
            if (!regexEmail.test(email) || email.length > 200) {
                errores.push("Debe introducir un correo electrónico válido (máx. 200 caracteres).");
            }

            // Dirección
            if (calle === "" || numero === "" || codigoPostal === "" || localidad === "" || provincia === "" || pais === "") {
                errores.push("Debe completar todos los campos de dirección.");
            }

            // Código postal (solo números)
            if (codigoPostal && !/^\d{5}$/.test(codigoPostal)) {
                errores.push("El código postal debe contener 5 cifras numéricas.");
            }

            // Número de copias
            if (isNaN(numCopias) || numCopias < 1 || numCopias > 99) {
                errores.push("El número de copias debe estar entre 1 y 99.");
            }

            // Anuncio seleccionado
            if (anuncio === "") {
                errores.push("Debe seleccionar un anuncio.");
            }

            // --- CÁLCULO DEL PRECIO ---
            let precioUnidad = 0;

            if (resolucion === 150 && impresionColor === "blanco_negro") precioUnidad = 5;
            if (resolucion === 150 && impresionColor === "color") precioUnidad = 7;
            if (resolucion > 150 && impresionColor === "blanco_negro") precioUnidad = 8;
            if (resolucion > 150 && impresionColor === "color") precioUnidad = 12;

            const costeFijo = 2;
            const total = (precioUnidad * numCopias) + costeFijo;

            // Mostrar resumen
            if (errores.length > 0) {
                event.preventDefault();
                alert("Errores detectados:\n\n" + errores.join("\n"));
            } else {
                // Guardar los datos en sessionStorage para mostrarlos en la siguiente página
                const resumenHTML = `
                <ul>
                    <li><strong>Nombre:</strong> ${nombre}</li>
                    <li><strong>Email:</strong> ${email}</li>
                    <li><strong>Dirección:</strong> ${calle}, ${numero}, ${codigoPostal}, ${localidad}, ${provincia}, ${pais}</li>
                    <li><strong>Número de copias:</strong> ${numCopias}</li>
                    <li><strong>Resolución:</strong> ${resolucion} DPI</li>
                    <li><strong>Tipo de impresión:</strong> ${impresionColor === "color" ? "Color" : "Blanco y negro"}</li>
                    <li><strong>Precio por unidad:</strong> ${precioUnidad} €</li>
                    <li><strong>Coste fijo:</strong> ${costeFijo} €</li>
                    <li><strong>Total:</strong> ${total} €</li>
                </ul>
                `;

                sessionStorage.setItem("resumenFolleto", resumenHTML);

            }
            });
        }
    }
});


document.addEventListener("DOMContentLoaded", function () {
  
});



document.addEventListener("DOMContentLoaded", () => {
  
});



document.addEventListener("DOMContentLoaded", function () {
  
});
