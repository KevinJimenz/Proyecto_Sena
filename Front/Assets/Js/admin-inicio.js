import { iniciarDataTable, togglePassword, cambiarPassword } from "../Js/funciones-globales.js";
const columnas_dataTable = [
  'Id',
  'Nombre del Instructor',
  'Matrices Hechas',
  'Horas evento 1',
  'Horas evento 2',
  'Horas evento 3',
  'Total de Horas'
];
iniciarDataTable("tabla_inicio", columnas_dataTable);
const cargarDatos = async () => {
  const idTabla = 'tabla_inicio';
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Admin/Inicio/Traer_info_instructores.php";
  const request = await fetch(url, {
    method: "GET",
  });
  const response = await request.json();
  const tabla = $("#" + idTabla).DataTable();
  tabla.clear();
  tabla.rows.add(response.data).draw();
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
document.getElementById("confirmar-cambio").addEventListener("click", (event) => {
  event.preventDefault();
  cambiarPassword('old-password', 'new-password', 'form')
});
cargarDatos();