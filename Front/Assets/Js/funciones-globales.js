function iniciarDataTable(idTabla, celdas) {
  const columnas_dataTable = celdas.map((celdas) => ({
    title: celdas,
    data: celdas,
    orderable: true,
    searchable: true,
    className: "text-center",
  }));
  return new DataTable("#" + idTabla, {
    columns: columnas_dataTable,
    responsive: true,
    scrollY: 520,
    language: {
      decimal: ",",
      emptyTable: "No hay datos disponibles en la tabla",
      info: "Mostrando _START_ a _END_ de _TOTAL_ resultados",
      infoEmpty: "Mostrando 0 a 0 de 0 resultados",
      infoFiltered: "(filtrado de _MAX_ resultados totales)",
      infoPostFix: "",
      thousands: ".",
      lengthMenu: "Mostrar _MENU_ resultados",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "No se encontraron registros coincidentes",
      paginate: {
        first: "«",
        last: "»",
        next: "›",
        previous: "‹",
      },
      aria: {
        orderable: "Ordenar por esta columna",
        orderableReverse: "Ordenar esta columna en orden inverso",
      },
    },
  });
}
function togglePassword(id_input, id_icon) {
  const input = document.getElementById(id_input);
  const icon = document.getElementById(id_icon);
  const isPasswordVisible = input.type === "text";
  input.type = isPasswordVisible ? "password" : "text";
  icon.classList.toggle("bi-eye", isPasswordVisible);
  icon.classList.toggle("bi-eye-slash", !isPasswordVisible);
}
function modificarInputsNumericos (className) {
  document.querySelectorAll(className).forEach((input) => {
    input.addEventListener("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
    });
  });
};
async function cambiarPassword(id_old_pass, id_new_pass, id_form) {
  let old_password = document.getElementById(id_old_pass);
  let new_password = document.getElementById(id_new_pass);
  let form = document.getElementById(id_form);
  if (old_password.value == "" || new_password.value == "") {
    Swal.fire({
      position: "top-end",
      icon: "error",
      title: "Debes llenar todos los campos.",
      showConfirmButton: false,
      timer: 2800,
    });
    return;
  }
  const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Global/cambiar-password.php";
  const form_data = new FormData(form);
  const request = await fetch(url, {
    method: "POST",
    body: form_data,
  });
  const response = await request.json();
  if (response.code == "401") {
    Swal.fire({
      position: "top-end",
      icon: response.icon,
      title: response.message,
      showConfirmButton: false,
      timer: 3000,
    });
    return;
  } else {
    Swal.fire({
      position: "top-end",
      icon: response.icon,
      title: response.message,
      showConfirmButton: false,
      timer: 2800,
    }).then(() => {
      $("#cambiar-password").modal("hide");
      $("#cambiar-password").find("input").each(function () {
        $(this).val("");
      });
    });
    return;
  }
}
export { iniciarDataTable, togglePassword, cambiarPassword, modificarInputsNumericos };
