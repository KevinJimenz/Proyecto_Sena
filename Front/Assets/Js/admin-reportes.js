import { iniciarDataTable, togglePassword, cambiarPassword } from "../Js/funciones-globales.js";
const columnas_dataTable = [
  'Id',
  'Nombre del Instructor',
  'Tipo de Matriz',
  'Acciones',
];
iniciarDataTable("tabla_reportes", columnas_dataTable);
document.getElementById('todos_reportes').addEventListener('click', () => {
  imprimir(0);
})
document.getElementById('reporte_instructor').addEventListener('click', () => {
  cargarDatos();
})
const cargarDatos = async () => {
  const idTabla = 'tabla_reportes';
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
      <button class="btn btn-success btn-imprimir" data-id="${item.Id}">Imprimir</button>
    `,
  }));
  tabla.rows.add(datos_con_acciones).draw();
}
document.querySelector(`#tabla_reportes tbody`).addEventListener("click", (e) => {
  if (e.target.classList.contains("btn-imprimir")) {
    const id_fila = e.target.dataset.id;
    imprimir(id_fila);
  }
});
const imprimir = async (id_matriz) => {
  if (id_matriz == 0) {
    const url = 'http://localhost/Proyecto_Wilfred/Back/Controllers/Admin/Reportes/Imprimir_todos.php';
    window.location.href = url;
  }
  if (id_matriz != 0) {
    let url = `http://localhost/Proyecto_Wilfred/Back/Controllers/Admin/Reportes/Imprimir_instructor.php?id_matriz=${id_matriz}`;
    window.location.href = url;
  }
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
