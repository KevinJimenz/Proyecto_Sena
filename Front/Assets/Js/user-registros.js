import { iniciarDataTable, togglePassword, cambiarPassword } from "./funciones-globales.js";
const columnas_dataTable = [
  'Id',
  'Codigo',
  'Programa',
  'Dias',
  'Rango Horas',
  'Actividad',
  'Resultados',
  'Acciones'
];
iniciarDataTable("tabla_registros", columnas_dataTable);
document.querySelector(`#tabla_registros tbody`).addEventListener("click", (e) => {
  if (e.target.classList.contains("btn-editar")) {
    const id_fila = e.target.dataset.id;
    cargarModal(id_fila, 'contenedor-editar', 'editar');
  }
  if (e.target.classList.contains("btn-eliminar")) {
    const id_fila = e.target.dataset.id;
    cargarModal(id_fila, 'contenedor-eliminar', 'eliminar');
  }
});
const cargarDatos = async () => {
  const id_tabla = 'tabla_registros';
  const url_tabla = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/User/Traer_registros.php";
  const url_horas = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/User/Traer_horas.php";
  const [request_tabla, request_horas] = await Promise.all([
    fetch(url_tabla, {
      method: 'GET'
    }),
    fetch(url_horas, {
      method: 'GET'
    }),
  ]);
  const [response_tabla, response_horas] = await Promise.all([
    request_tabla.json(),
    request_horas.json(),
  ]);
  const tabla = $("#" + id_tabla).DataTable();
  tabla.clear();
  const datos_con_acciones = response_tabla.data.map((item) => ({
    ...item,
    Acciones: `
      <button class="btn btn-secondary btn-editar" data-id="${item.Id}">Editar</button>
      <button class="btn btn-danger btn-eliminar" data-id="${item.Id}">Eliminar</button>
    `,
  }));
  tabla.rows.add(datos_con_acciones).draw();
  let suma = 0;
  suma = response_horas.data[0]['Evento 1'] + response_horas.data[0]['Evento 2'] + response_horas.data[0]['Evento 3'];
  document.getElementById('evento_1').innerHTML = response_horas.data[0]['Evento 1'] ;
  document.getElementById('evento_2').innerHTML = response_horas.data[0]['Evento 2'] ;
  document.getElementById('evento_3').innerHTML = response_horas.data[0]['Evento 3'] ;
  document.getElementById('total_horas').innerHTML = suma;

}
const cargarModal = async (id_registro, id_contenedor_modal, tipo_modal) => {
  if (tipo_modal == 'editar') {
    const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/User/Traer_registro_por_id.php";
    const form_data = new FormData();
    form_data.append("id_registro", id_registro);
    const request = await fetch(url, {
      method: "POST",
      body: form_data
    });
    const response = await request.json();
    let horas = obtenerRangoHoras(response.data[0].Tipo)
    let html = `
      <input class="form-control" id="id_registro" name="id_registro" value="${id_registro}" hidden>
      <div class="mb-3">
        <label for="codigo_ficha" class="form-label">Codigo de Ficha</label>
        <input type="text" class="form-control onlyNumber" id="codigo_ficha" name="codigo_ficha" value="${response.data[0]['Codigo de Ficha']}">
      </div>
      <div class="mb-3">
        <label for="nombre_programa" class="form-label">Nombre del Programa</label>
        <input type="email" class="form-control" id="nombre_programa" name="nombre_programa" value="${response.data[0]['Nombre de Programa']}">
      </div>
      <div class="mb-3">
        <label for="dias" class="form-label">Dias <small> <em> <strong>(Ingresa los días separados por coma)</strong>  </em></small></label>
        <input type="email" class="form-control dias_mes" id="dias" name="dias" value="${response.data[0]['Dias']}">
        
      </div>
      <div class="row mb-3">
        <div class="col-6">
          <label for="rango_viejo" class="form-label">Rango de <strong>Horas Anterior</strong></label>
          <input type="email" class="form-control" id="rango_viejo" value="${response.data[0]['Horas Programadas']}" readonly>
        </div>
        <div class="col-6">
          <label for="horas" class="form-label">Elige el <strong>Nuevo Rango</strong></label>
          <select class="form-select select-rango" id="horas" name="horas"></select>
        </div>
      </div>
      <div class="mb-3">
        <label for="actividad" class="form-label">Actividad de Aprendizaje</label>
        <textarea type="email" class="form-control" id="actividad" name="actividad">${response.data[0]['Actividad de Aprendizaje']}</textarea>
      </div>
      <div class="mb-3">
        <label for="resultados" class="form-label">Resultados de Aprendizaje</label>
        <textarea type="email" class="form-control" id="resultados" name="resultados">${response.data[0]['Resultados de Aprendizaje']}</textarea>
      </div>
    `;
    document.getElementById(id_contenedor_modal).innerHTML = html;
    $("#modal-editar").modal("show");
    llenarSelectsHoras(horas);
    modificarInputDias(".dias_mes");
    return
  }
  if (tipo_modal == 'eliminar') {
    const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/User/Traer_registro_por_id.php";
    const form_data = new FormData();
    form_data.append("id_registro", id_registro);
    const request = await fetch(url, {
      method: "POST",
      body: form_data
    });
    const response = await request.json();
    let html = `
      <input class="form-control" id="id_registro" name="id_registro" value="${id_registro}" hidden>
        <div class="mb-3">
          <p class="form-label" style="font-size: 18px;">¿Seguro que quieres eliminar a <strong> ${response.data[0]['Nombre de Programa']} </strong> ?</p>
        </div>
    `;
    document.getElementById(id_contenedor_modal).innerHTML = html;
    $("#modal-eliminar").modal("show");
    return
  }
};
function llenarSelectsHoras(horas) {
  const select_rango = document.querySelector(".select-rango");
  for (let i = 0; i < horas.length; i++) {
    select_rango.innerHTML += horas[i];
  }
}
function obtenerRangoHoras(tipo) {
  let horas = [];
  switch (tipo) {
    case 1:
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
    case 2:
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
    case 3:
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
  }
  return horas;
}
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
document.getElementById("confirmar-cambio-password").addEventListener("click", (event) => {
  event.preventDefault();
  cambiarPassword('old-password', 'new-password', 'form')
});
function modificarInputDias(nameInput) {
  document.querySelectorAll(nameInput).forEach(function (input) {
    input.addEventListener('input', function () {
      // Quitar todo lo que no sea dígito o coma
      this.value = this.value.replace(/[^0-9,]/g, '');

      let partes = this.value.split(',');

      let numerosVistos = {};
      let diasValidos = [];

      partes.forEach(function (parte) {
        if (parte !== '') {
          // Quitar espacios o ceros iniciales
          let numero = parseInt(parte.trim(), 10);

          // Validar número entre 1 y 31 y que no esté repetido
          if (!isNaN(numero) && numero >= 1 && numero <= 31 && !numerosVistos[numero]) {
            diasValidos.push(numero);
            numerosVistos[numero] = true;
          }
        }
      });

      // Si termina con coma, conservar la coma para seguir escribiendo
      if (this.value.endsWith(',')) {
        this.value = diasValidos.join(',') + ',';
      } else {
        this.value = diasValidos.join(',');
      }

    });
  });
};
// Editar Registro
document.getElementById('confirmar-edicion').addEventListener('click', async (event) => {
  event.preventDefault();
  let form = document.getElementById('form-editar');
  let form_data = new FormData(form);
  if (
    !form_data.get('codigo_ficha') ||
    !form_data.get('nombre_programa') ||
    !form_data.get('dias') ||
    !form_data.get('actividad') ||
    !form_data.get('resultados')
  ) {
    Swal.fire({
      position: "top-end",
      icon: 'error',
      title: 'Por favor llene todos los campos.',
      showConfirmButton: false,
      timer: 2800,
    })
    return
  }
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/User/Editar_registro.php";
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
    window.location.reload(true);
  })
})
// Eliminar Registro
document.getElementById('confirmar-eliminacion').addEventListener('click', async (event) => {
  event.preventDefault();
  let form = document.getElementById('form-eliminar');
  let form_data = new FormData(form);
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/User/Eliminar_registro.php";
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
    window.location.reload(true);
  })
})
cargarDatos();