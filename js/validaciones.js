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
  // LÓGICA PÁGINA SOLICITAR FOLLETO (REFACTORIZADA)
  // ==================================
  if (document.body.id === "folletoPage") {
    
    // --- Lógica de la tabla y botón de alternancia (SIMPLIFICADA) ---
    // Buscamos el botón y la tabla que PHP ha creado en folleto.php
    const botonToggle = document.getElementById("toggleTablaCostes");
    const tabla = document.getElementById("tablaCostesGenerada"); // Este es el ID de la tabla de PHP

    if (tabla && botonToggle) {
      
      // Ya no necesitamos el código que CREA la tabla (const COSTES, crearElemento, etc.)
      // Solo necesitamos el evento CLICK para mostrar/ocultar la tabla que ya existe
      
      botonToggle.addEventListener("click", () => {
        const isHidden = tabla.style.display === "none";
        tabla.style.display = isHidden ? "table" : "none";
        botonToggle.textContent = isHidden ? "Ocultar tabla de costes" : "Mostrar tabla de costes";
      });
    }

    // --- Lógica de envío del formulario (ESTA PARTE SE MANTIENE IGUAL) ---
    // Esta es la validación JS del formulario que ya tenías y que funciona.
    const formFolleto = document.querySelector("form");
    if (formFolleto) {
      formFolleto.addEventListener("submit", (event) => {
        limpiarErrores(formFolleto);
        let valido = true;

        // Lista de campos obligatorios para validación de vacío
        const camposObligatorios = [
          { id: "nombre", msg: "El nombre es obligatorio." },
          { id: "calle", msg: "Campo obligatorio." },
          { id: "numero", msg: "Campo obligatorio." },
          { id: "localidad", msg: "Campo obligatorio." },
          { id: "provincia", msg: "Campo obligatorio." },
          { id: "pais", msg: "Campo obligatorio." },
          { id: "anuncio", msg: "Debe seleccionar un anuncio." },
          { id: "codigo_postal", msg: "Campo obligatorio.", valFn: validarCodigoPostal },
          { id: "email", msg: "Campo obligatorio.", valFn: validarEmail }
        ];

        // VALIDACIONES DE VACÍO Y FORMATO (agrupadas)
        camposObligatorios.forEach(campo => {
          const valor = document.getElementById(campo.id).value.trim();
          if (valor === "") {
            valido = false;
            mostrarError(campo.id, campo.msg);
          } else if (campo.valFn) {
            const [v, m] = campo.valFn(valor);
            if (!v) {
              valido = false;
              mostrarError(campo.id, m);
            }
          }
        });
        
        // La validación de CP debe manejar también el caso de vacío
        if (document.getElementById("codigo_postal").value.trim() !== "" && valido) {
            const [cpValido, cpMsg] = validarCodigoPostal(document.getElementById("codigo_postal").value.trim());
            if (!cpValido) {
                valido = false;
                mostrarError("codigo_postal", cpMsg);
            }
        }

        // Validación de copias
        const numCopias = document.getElementById("num_copias").value;
        const copiasInt = parseInt(numCopias);
        if (isNaN(copiasInt) || copiasInt < 1 || copiasInt > 99) {
          valido = false;
          mostrarError("num_copias", "Debe ser un número entre 1 y 99.");
        }

        if (!valido) {
          event.preventDefault();
        } else {
          // CÁLCULO Y ALMACENAMIENTO (conciso)
          const getInput = (id) => document.getElementById(id).value.trim();
          const impresionColor = formFolleto.querySelector("input[name='impresion_color']:checked").value;
          const resInt = parseInt(getInput("resolucion"));
          const costeFijo = 2;
          
          // Operador ternario para calcular precioUnidad
          const precioUnidad = (resInt === 150) ? 
            (impresionColor === "blanco_negro" ? 5 : 7) : 
            (impresionColor === "blanco_negro" ? 8 : 12);

          const total = (precioUnidad * copiasInt) + costeFijo;
          
          // Resumen más corto
          const resumenHTML = `
          <ul>
            <li><strong>Nombre:</strong> ${getInput("nombre")}</li>
            <li><strong>Email:</strong> ${getInput("email")}</li>
            <li><strong>Dirección:</strong> ${getInput("calle")}, ${getInput("numero")}, ${getInput("codigo_postal")}, ${getInput("localidad")}, ${getInput("provincia")}, ${getInput("pais")}</li>
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