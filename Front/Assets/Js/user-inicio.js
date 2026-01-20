import { modificarInputsNumericos, togglePassword, cambiarPassword } from "./funciones-globales.js";
let contador_registros = 0;
const select_eventos = document.getElementById("select-eventos");
document.getElementById("guardar-registro").addEventListener("click", async (event) => {
  event.preventDefault();
  const form = document.getElementById('form-crear');
  const form_data = new FormData(form);
  for (let i = 0; i < contador_registros; i++) {
    if (
      !form_data.get(`fila[${i}][codigo_ficha]`) &&
      !form_data.get(`fila[${i}][nombre_programa]`) &&
      !form_data.get(`fila[${i}][dia_del_mes]`) &&
      !form_data.get(`fila[${i}][actividad]`) &&
      !form_data.get(`fila[${i}][resultados]`)
    ) {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: "Debes llenar todos los registros!!",
        showConfirmButton: false,
        timer: 2500,
      });
      return;
    }
  }
  form_data.append('tipo_matriz', select_eventos.value);
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/User/Crear_matriz.php";
  const request = await fetch(url, {
    method: "POST",
    body: form_data
  });
  const response = await request.json();
  Swal.fire({
    position: "top-end",
    icon: response.icon,
    title: response.message,
    showConfirmButton: false,
    timer: response.time,
  }).then(() => {
    if (response.icon != "error") {
      window.location.href = window.location.origin + "/Proyecto_Wilfred/Front/Pages/User/dashboard-registros.php" ;
    }
  })
})
document.getElementById("agregar-registro").addEventListener("click", () => {
  const contenedor_main = document.getElementById("contenedor-main");
  const contenedor_registros = document.getElementById("registros");
  const contenedor_boton = document.getElementById("boton-guardar");
  if (select_eventos.value == 0) {
    Swal.fire({
      position: "top-end",
      icon: "error",
      title: "Debes seleccionar un Tipo de Evento!!",
      showConfirmButton: false,
      timer: 2500,
    });
    return;
  }
  let horas = obtenerRangoHoras(select_eventos.value);
  mostrarContenedores(contenedor_main, contenedor_registros, contenedor_boton);
  const fila = `
    <div class="d-flex flex-wrap mb-3 gap-3 w-100" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 10px">
        <div class="d-flex justify-content-center align-items-center" style="width: 1rem;">
            <p class="form-label fs-5">${contador_registros + 1}</p>
        </div>
        <div class="form-floating flex-grow-1">
            <input type="text" class="form-control onlyNumbers codigo-input" id="codigo_${contador_registros}" name="fila[${contador_registros}][codigo_ficha]" placeholder="">
            <label for="codigo_${contador_registros}">Codigo de Ficha</label>
        </div>
        <div class="form-floating flex-grow-1">
            <textarea type="text" class="form-control" id="programa_${contador_registros}" name="fila[${contador_registros}][nombre_programa]" placeholder=""></textarea>
            <label for="programa_${contador_registros}">Nombre del Programa</label>
        </div>
        <div class="form-floating flex-grow-1">
            <input type="text" class="form-control dias_mes" id="dias_${contador_registros}" name="fila[${contador_registros}][dia_del_mes]" placeholder="">
            <label for="dias_${contador_registros}">Dia/as del Mes</label>
            <small> <em> <strong style="color: red;"> Ingresa los días separados por coma. </strong>  </em></small>
        </div>
        <div class="form-floating flex-grow-1">
            <select class="form-select select-rango" id="horas_${contador_registros}" name="fila[${contador_registros}][rango_horas]"></select>
            <label for="horas_${contador_registros}">Rango de Horas</label>
        </div>
        <div class="form-floating flex-grow-1">
            <textarea type="text" class="form-control" id="actividad_${contador_registros}" name="fila[${contador_registros}][actividad]" placeholder=""></textarea>
            <label for="actividad_${contador_registros}">Actividad de Aprendizaje</label>
        </div>
        <div class="form-floating flex-grow-1">
            <textarea type="text" class="form-control" id="resultado_${contador_registros}" name="fila[${contador_registros}][resultados]" placeholder=""></textarea>
            <label for="resultado_${contador_registros}">Resultado/os de Aprendizaje</label>
        </div>
    </div>
    `;
  contenedor_registros.innerHTML += fila;
  document.querySelectorAll('.codigo-input').forEach(input => {
    input.removeEventListener('input', validarCodigos); // prevenir duplicados
    input.addEventListener('input', validarCodigos);
  });
  llenarSelectsHoras(horas);
  modificarInputsNumericos(".onlyNumbers");
  modificarInputDias(".dias_mes");
  contador_registros++;
});

function validarCodigos() {
  const inputs = document.querySelectorAll('.codigo-input');
  const valores = {};
  const boton_Guardar = document.getElementById("guardar-registro");
  inputs.forEach(input => {
    const valor = input.value.trim();
    if (valor !== '') {
      if (valores[valor]) {
        input.setCustomValidity('Este código ya fue ingresado.');
        input.reportValidity(); // muestra el mensaje
        boton_Guardar.disabled = true;
      } else {
        input.setCustomValidity('');
        valores[valor] = true;
        boton_Guardar.disabled = false;
      }
    } else {
      input.setCustomValidity('');
      boton_Guardar.disabled = false;
    }
  });
}
function modificarInputDias(nameInput) {
  document.querySelectorAll(nameInput).forEach(function (input) {
    input.addEventListener('input', function () {
      // Quitar todo lo que no sea dígito o coma
      this.value = this.value.replace(/[^0-9,]/g, '');

      // Dividir por coma
      let partes = this.value.split(',');

      let numerosVistos = {};
      let diasValidos = [];

      partes.forEach(function (parte) {
        // Solo procesar si la parte no está vacía y es un número válido
        if (parte !== '') {
          let numero = parseInt(parte, 10);

          // Validar si es número entre 1 y 31 y no repetido
          if (!isNaN(numero) && parte.length <= 2 && numero >= 1 && numero <= 31 && !numerosVistos[numero]) {
            diasValidos.push(numero);
            numerosVistos[numero] = true;
          }
        }
      });

      // Si termina con coma, conservar la coma para que el usuario pueda seguir escribiendo
      if (this.value.endsWith(',')) {
        this.value = diasValidos.join(',') + ',';
      } else {
        this.value = diasValidos.join(',');
      }
    });
  });
}
function mostrarContenedores(...elementos) {
  elementos.forEach(el => {
    if (el.style.display === "none") {
      el.style.display = "block";
    }
  });
}
function llenarSelectsHoras(horas) {
  const select_rango = document.querySelectorAll(".select-rango");
  for (let i = 0; i < select_rango.length; i++) {
    select_rango[i].innerHTML = "";
    for (let j = 0; j < horas.length; j++) {
      select_rango[i].innerHTML += horas[j];
    }
  }
}
function obtenerRangoHoras(tipo) {
  let horas = [];
  switch (tipo) {
    case "1":
      let evento_1 = [
        [6, 7, "AM"],
        [7, 8, "AM"],
        [8, 9, "AM"],
        [9, 10, "AM"],
        [10, 11, "AM"],
        [11, 12, "AM"],
        [12, 1, "PM"],
        [1, 2, "PM"],
        [2, 3, "PM"],
        [3, 4, "PM"],
        [4, 5, "PM"],
        [5, 6, "PM"],
        [6, 7, "PM"],
        [7, 8, "PM"],
        [8, 9, "PM"],
        [9, 10, "PM"],
      ];
      evento_1.map((rango) =>
        horas.push(
          `<option value="${rango[0]}-${rango[1]}-${rango[2]}">${rango[0]}${rango[2]} a ${rango[1]}${rango[2]}</option>`
        )
      );
      break;
    case "2":
      let evento_2 = [
        [6, 8, "AM"],
        [8, 10, "AM"],
        [10, 12, "AM"],
        [12, 2, "PM"],
        [2, 4, "PM"],
        [4, 6, "PM"],
        [6, 8, "PM"],
        [8, 10, "PM"],
      ];
      evento_2.map((rango) =>
        horas.push(
          `<option value="${rango[0]}-${rango[1]}-${rango[2]}">${rango[0]}${rango[2]} a ${rango[1]}${rango[2]}</option>`
        )
      );
      break;
    case "3":
      let evento_3 = [
        [6, 12, "AM"],
        [12, 6, "PM"],
      ];
      evento_3.map((rango) =>
        horas.push(
          `<option value="${rango[0]}-${rango[1]}-${rango[2]}">${rango[0]}${rango[2]} a ${rango[1]}${rango[2]}</option>`
        )
      );
      break;
    case "0":
      horas.push(0);
      break;
  }
  return horas;
}
document.getElementById("eliminar-registro").addEventListener("click", () => {
  const contenedor_registros = document.getElementById("registros");
  if (contenedor_registros.children.length > 0) {
    contenedor_registros.removeChild(contenedor_registros.lastElementChild);
    contador_registros--;
    if (contenedor_registros.children.length === 0) {
      contenedor_registros.style.display = "none";
      document.getElementById("contenedor-main").style.display = "none";
      document.getElementById("boton-guardar").style.display = "none";
    }
  }
});
document.getElementById("select-eventos").addEventListener("change", () => {
  // limpio el contenedor de registros y lo oculto, tambien oculto el boton guardar y reinicio el contador
  const contenedor_registros = document.getElementById("registros");
  contenedor_registros.replaceChildren();
  contenedor_registros.style.display = "none";
  document.getElementById("contenedor-main").style.display = "none";
  document.getElementById("boton-guardar").style.display = "none";
  contador_registros = 0;
});
const passwordFields = [
  { inputId: "old-password", iconId: "old-icon" },
  { inputId: "new-password", iconId: "new-icon" },
];
passwordFields.forEach(({ inputId, iconId }) => {
  const icon = document.getElementById(iconId);
  if (icon) {
    icon.addEventListener("click", () => togglePassword(inputId, iconId));
  }
});
document.getElementById("confirmar-cambio").addEventListener("click", (event) => {
  event.preventDefault();
  cambiarPassword('old-password', 'new-password', 'form')
});