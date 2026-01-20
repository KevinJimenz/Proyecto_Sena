import { iniciarDataTable, togglePassword, cambiarPassword } from "../Js/funciones-globales.js";
const columnas_dataTable = [
  'Id',
  'Nombre',
  'Rol',
  'Tipo',
  'Acciones'
];
iniciarDataTable("tabla_usuarios", columnas_dataTable);
const cargarDatos = async () => {
  const idTabla = 'tabla_usuarios';
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Users/Traer_usuarios.php";
  const request = await fetch(url, {
    method: "GET",
  });
  const response = await request.json();
  const tabla = $("#" + idTabla).DataTable();
  tabla.clear();
  const datos_con_acciones = response.data.map((item) => ({
    ...item,
    Acciones: `
      <button class="btn btn-secondary btn-editar" data-id="${item.Id}">Editar</button>
      <button class="btn btn-danger btn-eliminar" data-id="${item.Id}">Eliminar</button>
    `,
  }));
  tabla.rows.add(datos_con_acciones).draw();
}
document.querySelector(`#tabla_usuarios tbody`).addEventListener("click", (e) => {
  if (e.target.classList.contains("btn-editar")) {
    const id_fila = e.target.dataset.id;
    cargarModal(id_fila, 'contenedor-editar', 'editar');
  }
  if (e.target.classList.contains("btn-eliminar")) {
    const id_fila = e.target.dataset.id;
    cargarModal(id_fila, 'contenedor-eliminar', 'eliminar');
  }
});
const modificarInputs = (className) => {
  document.querySelectorAll(className).forEach((input) => {
    input.addEventListener("input", function () {
      this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
    });
  });
}
const cargarModal = async (id_usuario, id_contenedor_modal, tipo_modal) => {
  if (tipo_modal == 'editar') {
    const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Users/Traer_usuario_por_id.php";
    const form_data = new FormData();
    form_data.append("id_usuario", id_usuario);
    const request = await fetch(url, {
      method: "POST",
      body: form_data
    });
    const response = await request.json();
    const tipoActual = response.data[0].Tipo; // elimina espacios extra
    const tipos = ["Instructor Tecnico", "Instructor Transversal"];
    const otrasOpciones = tipos.filter(tipo => tipo != tipoActual);
    let html = `
      <input class="form-control" id="id_usuario" name="id_usuario" value="${id_usuario}" hidden>
      <div class="mb-3">
        <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
        <input type="text" class="form-control onlyText" id="nombre_usuario" name="nombre_usuario" value="${response.data[0].Nombre}">
      </div>
      <div class="mb-3">
        <label for="correo_usuario" class="form-label">Correo del Usuario</label>
        <input type="email" class="form-control" id="correo_usuario" name="correo_usuario" value="${response.data[0].Correo}">
      </div>
      <div class="mb-3">
        <label for="tipo" class="form-label">Tipo</label>
        <select class="form-control" id="tipo" name="tipo">
          <option selected value="${response.data[0].Tipo}">${response.data[0].Tipo}</option>
          ${otrasOpciones.map(tipo => `<option value="${tipo}">${tipo}</option>`).join('')}
        </select>
      </div>
    `;
    document.getElementById(id_contenedor_modal).innerHTML = html;
    modificarInputs('.onlyText');
    $("#modal-editar").modal("show");
    return
  }
  if (tipo_modal == 'eliminar') {
    const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Users/Traer_usuario_por_id.php";
    const form_data = new FormData();
    form_data.append("id_usuario", id_usuario);
    const request = await fetch(url, {
      method: "POST",
      body: form_data
    });
    const response = await request.json();
    let html = `
      <input class="form-control" id="id_usuario" name="id_usuario" value="${id_usuario}" hidden>
        <div class="mb-3">
          <p class="form-label" style="font-size: 18px;">¿Seguro que quieres eliminar a <strong> ${response.data[0].Nombre} </strong> ?</p>
        </div>
    `;
    document.getElementById(id_contenedor_modal).innerHTML = html;
    $("#modal-eliminar").modal("show");
    return
  }
};
// Cambiar Constraseña de Usuario
const inputs_password = [
  { inputId: 'old-password', iconId: 'old-icon' },
  { inputId: 'new-password', iconId: 'new-icon' },
  { inputId: 'confirm-password', iconId: 'confirm-icon' }
];
inputs_password.forEach(({ inputId, iconId }) => {
  const icon = document.getElementById(iconId);
  if (icon) {
    icon.addEventListener('click', () => togglePassword(inputId, iconId));
  }
});
document.getElementById("confirmar-cambio-password").addEventListener("click", (event) => {
  event.preventDefault();
  cambiarPassword('old-password', 'new-password', 'form')
});
document.getElementById("ocultar-password").addEventListener("click", () => {
  const input = document.getElementById("password_usuario");
  const icon = document.getElementById("toggleIcon");
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("bi-eye");
    icon.classList.add("bi-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("bi-eye-slash");
    icon.classList.add("bi-eye");
  }
})
// Crear Usuario
document.getElementById('confirmar-agregacion').addEventListener('click', async (event) => {
  event.preventDefault();
  let form = document.getElementById('form-crear');
  let form_data = new FormData(form);
  if (
    !form_data.get('nombre_usuario') ||
    !form_data.get('correo_usuario') ||
    !form_data.get('password_usuario') ||
    form_data.get('tipo') == 0
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
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Users/Crear.php";
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
});
// Editar Usuario
document.getElementById('confirmar-edicion').addEventListener('click', async (event) => {
  event.preventDefault();
  let form = document.getElementById('form-editar');
  let form_data = new FormData(form);
  if (
    !form_data.get('nombre_usuario') ||
    !form_data.get('correo_usuario') ||
    form_data.get('tipo') == 0
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
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Users/Editar.php";
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
// Eliminar Usuario
document.getElementById('confirmar-eliminacion').addEventListener('click', async (event) => {
  event.preventDefault();
  let form = document.getElementById('form-eliminar');
  let form_data = new FormData(form);
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Users/Eliminar.php";
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
modificarInputs('.onlyText');