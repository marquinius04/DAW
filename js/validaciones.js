function validarUsuario(usuario) {
  if (usuario.length < 3) return [false, "La longitud mínima es de 3 caracteres."];
  if (usuario.length > 15) return [false, "La longitud máxima es de 15 caracteres."];

  const primerChar = usuario.charCodeAt(0);
  if (primerChar >= 48 && primerChar <= 57) { // 0-9
    return [false, "No puede comenzar con un número."];
  }

  for (let i = 0; i < usuario.length; i++) {
    const charCode = usuario.charCodeAt(i);
    const esMayus = charCode >= 65 && charCode <= 90;  // A-Z
    const esMinus = charCode >= 97 && charCode <= 122; // a-z
    const esNum = charCode >= 48 && charCode <= 57;   // 0-9

    if (!(esMayus || esMinus || esNum)) {
      return [false, `El carácter '${usuario[i]}' no está permitido. Solo letras inglesas y números.`];
    }
  }
  return [true, ""];
}

function validarClave(clave) {
  if (clave.length < 6) return [false, "La longitud mínima es de 6 caracteres."];
  if (clave.length > 15) return [false, "La longitud máxima es de 15 caracteres."];

  let tieneMayus = false;
  let tieneMinus = false;
  let tieneNum = false;

  for (let i = 0; i < clave.length; i++) {
    const charCode = clave.charCodeAt(i);
    const esMayus = charCode >= 65 && charCode <= 90;  // A-Z
    const esMinus = charCode >= 97 && charCode <= 122; // a-z
    const esNum = charCode >= 48 && charCode <= 57;   // 0-9
    const esGuion = charCode === 45; // -
    const esGuionBajo = charCode === 95; // _

    if (esMayus) tieneMayus = true;
    if (esMinus) tieneMinus = true;
    if (esNum) tieneNum = true;

    if (!(esMayus || esMinus || esNum || esGuion || esGuionBajo)) {
      return [false, `El carácter '${clave[i]}' no está permitido.`];
    }
  }

  if (!tieneMayus) return [false, "Debe contener al menos una letra mayúscula."];
  if (!tieneMinus) return [false, "Debe contener al menos una letra minúscula."];
  if (!tieneNum) return [false, "Debe contener al menos un número."];

  return [true, ""];
}

function validarEmail(email) {
  if (email.length > 254) return [false, "Email demasiado largo (máx 254)."];

  const arrobaPos = email.indexOf('@');
  if (arrobaPos === -1) return [false, "Debe contener una '@'."];
  if (email.indexOf('@', arrobaPos + 1) !== -1) return [false, "Debe contener solo una '@'."];

  const parteLocal = email.substring(0, arrobaPos);
  const dominio = email.substring(arrobaPos + 1);

  // --- Validar parte local ---
  if (parteLocal.length < 1 || parteLocal.length > 64) {
    return [false, "Error en la longitud de la parte local (1-64 caracteres)."];
  }
  if (parteLocal.startsWith('.') || parteLocal.endsWith('.')) {
    return [false, "La parte local no puede empezar o terminar con un punto."];
  }
  if (parteLocal.includes('..')) {
    return [false, "La parte local no puede contener dos puntos seguidos."];
  }
  const permitidosLocal = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#$%&'*+-/=?^_{|}~.";
  for (const char of parteLocal) {
    if (permitidosLocal.indexOf(char) === -1) {
      return [false, `Carácter '${char}' no permitido en la parte local.`];
    }
  }

  // --- Validar dominio ---
  if (dominio.length < 1 || dominio.length > 255) {
    return [false, "Error en la longitud del dominio (1-255 caracteres)."];
  }

  const subdominios = dominio.split('.');
  if (subdominios.length < 1) return [false, "El dominio debe tener al menos una parte."];
  const permitidosDominio = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";

  for (const sub of subdominios) {
    if (sub.length < 1 || sub.length > 63) {
      return [false, "Un subdominio tiene una longitud incorrecta (1-63 caracteres)."];
    }
    if (sub.startsWith('-') || sub.endsWith('-')) {
      return [false, "Un subdominio no puede empezar o terminar con guion."];
    }
    for (const char of sub) {
      if (permitidosDominio.indexOf(char) === -1) {
        return [false, `Carácter '${char}' no permitido en el dominio.`];
      }
    }
  }

  return [true, ""];
}

function validarNacimiento(fechaStr) {
  // Detectar cadena vacía o la formada por los tres campos vacíos: "//"
  if (!fechaStr || fechaStr.trim() === "" || fechaStr === "//") {
    return [false, "Debe introducir una fecha."];
  }

  // formato esperado: DD/MM/AAAA
  const partes = fechaStr.split('/');
  if (partes.length !== 3) return [false, "Formato incorrecto. Use DD/MM/AAAA."];

  const diaStr = partes[0].trim();
  const mesStr = partes[1].trim();
  const anioStr = partes[2].trim();

  if (!diaStr || !mesStr || !anioStr) return [false, "Debe introducir una fecha completa."];

  const dia = parseInt(diaStr, 10);
  const mes = parseInt(mesStr, 10); // 1-12
  const anio = parseInt(anioStr, 10); // AAAA

  if (isNaN(anio) || isNaN(mes) || isNaN(dia)) {
    return [false, "La fecha debe contener solo números."];
  }

  // Comprobar longitud razonable del año (4 dígitos)
  if (anioStr.length !== 4) return [false, "El año debe tener 4 dígitos."];

  const fecha = new Date(anio, mes - 1, dia);

  // Comprobar si la fecha es válida
  if (fecha.getFullYear() !== anio || fecha.getMonth() !== (mes - 1) || fecha.getDate() !== dia) {
    return [false, "La fecha introducida no es válida (ej: 31 de febrero)."];
  }

  // Comprobar edad (al menos 18 años)
  const hoy = new Date();
  const fechaHace18Anios = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());
  if (fecha > fechaHace18Anios) {
    return [false, "Debe ser mayor de 18 años."];
  }

  return [true, ""];
}

function validarCodigoPostal(cp) {
    if (cp.length !== 5) {
        return [false, "El código postal debe tener 5 cifras."];
    }
    for (let i = 0; i < cp.length; i++) {
        const charCode = cp.charCodeAt(i);
        if (charCode < 48 || charCode > 57) { // 0-9
            return [false, "El código postal debe contener solo números."];
        }
    }
    return [true, ""];
}


// ===================================
// INICIO DEL SCRIPT PRINCIPAL
// ===================================

document.addEventListener("DOMContentLoaded", () => {

  // --- Funciones auxiliares de DOM ---

  /**
  * Muestra un mensaje de error en el span correspondiente y añade clase al campo.
  * @param {string} campoId - El ID del campo (input, select, etc.).
  */
  const mostrarError = (campoId, mensaje) => {
    // busca el input/select por id y el span de error por id + "Error"
    const campo = document.getElementById(campoId);
    const errorSpan = document.getElementById(campoId + "Error");

    // Añadir clase de error al campo real (si existe)
    if (campo) {
      campo.classList.add("campo-error");
    } else {
      // si no hay elemento con ese id, puede ser un grupo (select/name)
      const radios = document.getElementsByName(campoId);
      if (radios.length > 0) {
        radios.forEach(radio => radio.classList.add("campo-error"));
      }
    }

    // Mostrar y establecer el texto del span de error
    if (errorSpan) {
      if (mensaje) errorSpan.textContent = mensaje;
      errorSpan.style.display = "block";
    }
  };

  /**
  * Limpia todos los errores visuales de un formulario.
  * @param {HTMLFormElement} form - El formulario a limpiar.
  */
  const limpiarErrores = (form) => {
    // Quitar las clases de error
    form.querySelectorAll(".campo-error").forEach(campo => {
      campo.classList.remove("campo-error");
    });

    // Ocultar los spans de error
    form.querySelectorAll(".error").forEach(span => {
      span.style.display = "none"; // volver a ocultarlos
    });
  };



  // ==================================
  // LÓGICA PÁGINA DE LOGIN
  // ==================================
  if (document.body.id === "loginPage") {
    const formLogin = document.querySelector("form");

    if (formLogin) {
      formLogin.addEventListener("submit", (event) => {
        let valido = true;
        limpiarErrores(formLogin);

        const usuario = document.getElementById("usuario").value.trim();
        const clave = document.getElementById("clave").value.trim();

        if (usuario === "") {
          valido = false;
          mostrarError("usuario", "El nombre de usuario no puede estar vacío.");
        }

        if (clave === "") {
          valido = false;
          mostrarError("clave", "La contraseña no puede estar vacía.");
        }

        if (!valido) {
          event.preventDefault(); 
        }
      });
    }
  }


  // ==================================
  // LÓGICA PÁGINA DE REGISTRO
  // ==================================
  if (document.body.id === "registroPage") {
    const formRegistro = document.querySelector("form"); 

    if (formRegistro) {
      formRegistro.addEventListener("submit", (event) => {
        let valido = true;
        limpiarErrores(formRegistro);

        // --- Obtener valores ---
        const usuario = document.getElementById("usuario").value;
        const clave = document.getElementById("clave").value;
        const clave2 = document.getElementById("clave2").value;
        const email = document.getElementById("email").value.trim();
        const diaNacimiento = document.getElementById("diaNacimiento").value.trim();
        const mesNacimiento = document.getElementById("mesNacimiento").value.trim();
        const anyoNacimiento = document.getElementById("anyoNacimiento").value.trim();
        const nacimiento = `${diaNacimiento}/${mesNacimiento}/${anyoNacimiento}`;
        const sexo = document.getElementById("sexo").value;

        // --- Validaciones ---

        // Usuario
        const [usuarioValido, usuarioMsg] = validarUsuario(usuario);
        if (!usuarioValido) {
          valido = false;
          mostrarError("usuarioError", usuarioMsg);
        }

        // Contraseña
        const [claveValida, claveMsg] = validarClave(clave);
        if (!claveValida) {
          valido = false;
          mostrarError("clave", claveMsg);
        }

        // Repetir contraseña
        if (clave !== clave2) {
          valido = false;
          mostrarError("clave2", "Las contraseñas no coinciden.");
        }

        // Email
        const [emailValido, emailMsg] = validarEmail(email);
        if (!emailValido) {
          valido = false;
          mostrarError("email", emailMsg);
        }

        // Sexo
        if (!sexo) {
          valido = false;
          mostrarError("sexoError", "Debe seleccionar un sexo."); // Apunta al span 'sexoError'
        }
        
        // Fecha de nacimiento
        const [nacimientoValido, nacimientoMsg] = validarNacimiento(nacimiento);
        if (!nacimientoValido) {
            valido = false;
            mostrarError("nacimiento", nacimientoMsg);
        }

        // --- Fin validaciones ---

        if (!valido) {
          event.preventDefault(); // Evita el envío
        } else {

        }
      });
    }
  }


  // ==================================
  // LÓGICA PÁGINA SOLICITAR FOLLETO
  // ==================================
  if (document.body.id === "folletoPage") {
    /**
     * Calcula el coste de un folleto según las tarifas y el sistema de bloques.
     * @param {number} numPaginas 
     * @param {number} numFotos 
     * @param {boolean} esColor 
     * @param {boolean} esAltaRes 
     */
    function calcularCosteFolleto(numPaginas, numFotos, esColor, esAltaRes) {
        // Tarifas de la imagen
        const COSTE_FIJO = 10.0;
        const PRECIO_PAG_B1 = 2.0; 
        const PRECIO_PAG_B2 = 1.8; 
        const PRECIO_PAG_B3 = 1.6; 
        const COSTE_COLOR_FOTO = 0.5;
        const COSTE_RES_FOTO = 0.2;

        
        // 1. Cálculo de páginas 
        let costePaginas = 0.0;
        if (numPaginas <= 4) { // Bloque 1: < 5 págs (1-4)
            costePaginas = numPaginas * PRECIO_PAG_B1;
        } else if (numPaginas <= 10) { // Bloque 2: 5-10 págs
            // Coste de las primeras 4 págs + coste de las págs restantes
            costePaginas = (4 * PRECIO_PAG_B1) + ((numPaginas - 4) * PRECIO_PAG_B2);
        } else { // Bloque 3: > 10 págs
            // Coste bloque 1 (4 págs) + coste bloque 2 (6 págs) + coste de las págs restantes
            costePaginas = (4 * PRECIO_PAG_B1) + (6 * PRECIO_PAG_B2) + ((numPaginas - 10) * PRECIO_PAG_B3);
        }

        // 2. Cálculo de fotos (Color y resolución)
        let costeColor = 0.0;
        let costeResolucion = 0.0;

        if (esColor) {
            costeColor = numFotos * COSTE_COLOR_FOTO;
        }
        if (esAltaRes) {
            costeResolucion = numFotos * COSTE_RES_FOTO;
        }

        // 3. Suma total
        const total = COSTE_FIJO + costePaginas + costeColor + costeResolucion;
        
        // Devolver el valor formateado como "XX,XX €"
        return total.toFixed(2).replace('.', ',') + " €";
    }

    const datosEntradaTabla = [
        { p: 1, f: 3 }, { p: 2, f: 6 }, { p: 3, f: 9 }, { p: 4, f: 12 }, { p: 5, f: 15 },
        { p: 6, f: 18 }, { p: 7, f: 21 }, { p: 8, f: 24 }, { p: 9, f: 27 }, { p: 10, f: 30 },
        { p: 11, f: 33 }, { p: 12, f: 36 }, { p: 13, f: 39 }, { p: 14, f: 42 }, { p: 15, f: 45 }
    ];

    // ==============================================================
    // FIN DE LA SECCIÓN DE CÁLCULO
    // ==============================================================


    const contenedorTabla = document.getElementById("contenedorTablaCostes");
    const botonToggle = document.getElementById("toggleTablaCostes");

    if (contenedorTabla && botonToggle) {
        // 1. Crear la tabla
        const tabla = document.createElement("table");
        tabla.id = "tablaCostesGenerada";
        tabla.style.display = "none"; // Oculta por defecto

        // 2. Crear cabecera 
        const thead = document.createElement("thead");
        const trHead1 = document.createElement("tr");
        
        const thPag = document.createElement("th");
        thPag.textContent = "Número de páginas";
        thPag.rowSpan = 2;
        
        const thFotos = document.createElement("th");
        thFotos.textContent = "Número de fotos";
        thFotos.rowSpan = 2;

        const thBN = document.createElement("th");
        thBN.textContent = "Blanco y negro";
        thBN.colSpan = 2;
        
        const thColor = document.createElement("th");
        thColor.textContent = "Color";
        thColor.colSpan = 2;

        trHead1.appendChild(thPag);
        trHead1.appendChild(thFotos);
        trHead1.appendChild(thBN);
        trHead1.appendChild(thColor);

        const trHead2 = document.createElement("tr");
        const thBN1 = document.createElement("th");
        thBN1.textContent = "150-300 dpi";
        const thBN2 = document.createElement("th");
        thBN2.textContent = "450-900 dpi";
        const thC1 = document.createElement("th");
        thC1.textContent = "150-300 dpi";
        const thC2 = document.createElement("th");
        thC2.textContent = "450-900 dpi";
        
        trHead2.appendChild(thBN1);
        trHead2.appendChild(thBN2);
        trHead2.appendChild(thC1);
        trHead2.appendChild(thC2);
        
        thead.appendChild(trHead1);
        thead.appendChild(trHead2);
        tabla.appendChild(thead);

        // 3. Crear cuerpo 
        const tbody = document.createElement("tbody");
        
        // Función auxiliar interna para crear <td> con texto
        const crearTd = (texto) => {
            const td = document.createElement("td");
            td.textContent = texto;
            return td;
        };

        // Iterar sobre los datos de ENTRADA
        for (const filaData of datosEntradaTabla) {
            const tr = document.createElement("tr");
            
            const paginas = filaData.p;
            const fotos = filaData.f;

            // Añadir las dos primeras columnas (datos de entrada)
            tr.appendChild(crearTd(paginas));
            tr.appendChild(crearTd(fotos));

            // Calcular y añadir las 4 columnas de costes 
            tr.appendChild(crearTd(calcularCosteFolleto(paginas, fotos, false, false)));
            tr.appendChild(crearTd(calcularCosteFolleto(paginas, fotos, false, true)));
            tr.appendChild(crearTd(calcularCosteFolleto(paginas, fotos, true, false)));
            tr.appendChild(crearTd(calcularCosteFolleto(paginas, fotos, true, true)));
            
            tbody.appendChild(tr);
        }
        tabla.appendChild(tbody);

        // 4. Añadir la tabla al DOM
        contenedorTabla.appendChild(tabla);

        // 5. Configurar el botón para mostrar/ocultar
        botonToggle.addEventListener("click", () => {
            if (tabla.style.display === "none") {
            tabla.style.display = "table";
            botonToggle.textContent = "Ocultar tabla de costes";
            } else {
            tabla.style.display = "none";
            botonToggle.textContent = "Mostrar tabla de costes";
            }
        });
    }

    const formFolleto = document.querySelector("form"); 
    if (formFolleto) {
        formFolleto.addEventListener("submit", (event) => {
            let valido = true;
            limpiarErrores(formFolleto);

            // --- Campos base ---
            const nombre = document.getElementById("nombre").value.trim();
            const email = document.getElementById("email").value.trim();
            const calle = document.getElementById("calle").value.trim();
            const numero = document.getElementById("numero").value.trim();
            const codigoPostal = document.getElementById("codigo_postal").value.trim();
            const localidad = document.getElementById("localidad").value.trim();
            const provincia = document.getElementById("provincia").value.trim();
            const pais = document.getElementById("pais").value.trim();
            const numCopias = document.getElementById("num_copias").value;
            const resolucion = document.getElementById("resolucion").value;
            const anuncio = document.getElementById("anuncio").value;

            // --- VALIDACIONES ---
            if (nombre === "") {
                valido = false;
                mostrarError("nombre", "El nombre es obligatorio.");
            }
            
            const [emailValido, emailMsg] = validarEmail(email);
            if (!emailValido) {
                valido = false;
                mostrarError("email", emailMsg);
            }

            if (calle === "") {
                valido = false;
                mostrarError("calle", "Campo obligatorio.");
            }
            if (numero === "") {
                valido = false;
                mostrarError("numero", "Campo obligatorio.");
            }
            if (localidad === "") {
                valido = false;
                mostrarError("localidad", "Campo obligatorio.");
            }
            if (provincia === "") {
                valido = false;
                mostrarError("provincia", "Campo obligatorio.");
            }
            if (pais === "") {
                valido = false;
                mostrarError("pais", "Campo obligatorio.");
            }
            if (anuncio === "") {
                valido = false;
                mostrarError("anuncio", "Debe seleccionar un anuncio.");
            }
            
            const [cpValido, cpMsg] = validarCodigoPostal(codigoPostal);
            if (codigoPostal === "") {
                valido = false;
                mostrarError("codigo_postal", "Campo obligatorio.");
            } else if (!cpValido) {
                valido = false;
                mostrarError("codigo_postal", cpMsg);
            }

            const copiasInt = parseInt(numCopias);
            if (isNaN(copiasInt) || copiasInt < 1 || copiasInt > 99) {
                valido = false;
                mostrarError("num_copias", "Debe ser un número entre 1 y 99.");
            }

            if (!valido) {
                event.preventDefault();
            } else {
                const impresionColor = formFolleto.querySelector("input[name='impresion_color']:checked").value;
                const resInt = parseInt(resolucion);
                let precioUnidad = 0;

                // Tarifas del formulario en solicitar_folleto.html
                if (resInt === 150 && impresionColor === "blanco_negro") precioUnidad = 5;
                if (resInt === 150 && impresionColor === "color") precioUnidad = 7;
                if (resInt > 150 && impresionColor === "blanco_negro") precioUnidad = 8;
                if (resInt > 150 && impresionColor === "color") precioUnidad = 12;

                const costeFijo = 2;
                const total = (precioUnidad * copiasInt) + costeFijo;
                
                // Guardar en sessionStorage para la página de respuesta
                const resumenHTML = `
                <ul>
                    <li><strong>Nombre:</strong> ${nombre}</li>
                    <li><strong>Email:</strong> ${email}</li>
                    <li><strong>Dirección:</strong> ${calle}, ${numero}, ${codigoPostal}, ${localidad}, ${provincia}, ${pais}</li>
                    <li><strong>Número de copias:</strong> ${copiasInt}</li>
                    <li><strong>Resolución:</strong> ${resInt} DPI</li>
                    <li><strong>Tipo de impresión:</strong> ${impresionColor === "color" ? "Color" : "Blanco y negro"}</li>
                    <li><strong>Precio por unidad:</strong> ${precioUnidad} €</li>
                    <li><strong>Coste fijo:</strong> ${costeFijo} €</li>
                    <li><strong>Total:</strong> ${total} €</li>
                </ul>`;

                sessionStorage.setItem("resumenFolleto", resumenHTML);
            }
        });
    }
  }

}); 