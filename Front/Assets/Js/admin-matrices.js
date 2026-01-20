import { iniciarDataTable, togglePassword, cambiarPassword } from "../Js/funciones-globales.js";
const columnas_dataTable_instructores = [
  'Id',
  'Nombre del Instructor',
  'Tipo de Matriz',
  'Acciones',
];
iniciarDataTable("tabla_matrices", columnas_dataTable_instructores);
const columnas_dataTable_detalle = [
  'Codigo de Ficha',
  'Nombre del Programa',
  'Dias',
  'Rango de Horas',
  'Actividad de Aprendizaje',
  'Resultados de Aprendizaje',
];
iniciarDataTable("tabla_detalle", columnas_dataTable_detalle);
const cargarDatos = async () => {
  const idTabla = 'tabla_matrices';
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Matrices/Traer_instructores.php";
  const request = await fetch(url, {
    method: "GET",
  });
  const response = await request.json();
  const tabla = $("#" + idTabla).DataTable();
  tabla.clear();
  const datos_con_acciones = response.data.map((item) => ({
    ...item,
    Acciones: `
      <button class="btn btn-secondary btn-detalle" data-id="${item.Id}">Detalle</button>
    `,
  }));
  tabla.rows.add(datos_con_acciones).draw();
}
document.querySelector(`#tabla_matrices tbody`).addEventListener("click", (e) => {
  if (e.target.classList.contains("btn-detalle")) {
    const id_fila = e.target.dataset.id;
    cargarModal(id_fila);
  }
  
});
const cargarModal = async (id_matriz) => {
  const idTabla = 'tabla_detalle';
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Matrices/Traer_detalle_matriz.php";
  const form_data = new FormData();
  form_data.append("id_matriz", id_matriz);
  const request = await fetch(url, {
    method: "POST",
    body: form_data
  });
  const response = await request.json();
  const tabla = $("#" + idTabla).DataTable();
  tabla.clear();
  tabla.rows.add(response.data).draw();
  $("#modal-detalle").modal("show");
  $('#modal-detalle').on('shown.bs.modal', function () {
    $('#' + idTabla).DataTable().columns.adjust().draw();
  });
  return;
};
const passwordFields = [
  { inputId: 'old-password', iconId: 'old-icon' },
  { inputId: 'new-password', iconId: 'new-icon' },
  { inputId: 'confirm-password', iconId: 'confirm-icon' }
];
passwordFields.forEach(({ inputId, iconId }) => {
  const icon = document.getElementById(iconId);
  if (icon) {
    icon.addEventListener('click', () => togglePassword(inputId, iconId));
  }
});
document.getElementById("confirmar-cambio").addEventListener("click", (event) => {
  event.preventDefault();
  cambiarPassword('old-password', 'new-password', 'form')
});
cargarDatos();