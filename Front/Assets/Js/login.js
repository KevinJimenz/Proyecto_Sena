function togglePassword() {
  const input = document.getElementById("password");
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
}
document.getElementById("iniciar-sesion").addEventListener("click", async (event) => {
    event.preventDefault();
    let email = document.getElementById("correo");
    let pass = document.getElementById("password");
    const form = document.getElementById("form");
    if (email.value == "" || pass.value == "") {
      Swal.fire({
        position: "top-end",
        icon: "error",
        title: "Debes llenar todos los campos.",
        showConfirmButton: false,
        timer: 2800,
      });
      return;
    }
    const url = window.location.origin + "/Proyecto_Wilfred/Back/Controllers/Login/validar-usuario.php";
    const form_data = new FormData(form);
    const request = await fetch(url, {
      method: "POST",
      body: form_data,
    });
    const response = await request.json();
    if (response.code == "404" || response.code == "401") {
      Swal.fire({
        position: "top-end",
        icon: response.icon,
        title: response.message,
        showConfirmButton: false,
        timer: 2800,
      });
      return;
    } 
    if (response.code == "200") {
      Swal.fire({
      position: "top-end",
      icon: response.icon,
      title: response.message,
      showConfirmButton: false,
      timer: 2800,
      }).then(() => {
      const ruta_base = window.location.origin + "/Proyecto_Wilfred/Front/Pages/";
      const rutas = {
        Administrador: "Admin/dashboard-admin-inicio.php",
        Instructor: "User/dashboard-inicio.php"
      };
      if (rutas.hasOwnProperty(response.rol)) {
        window.location.href = ruta_base + rutas[response.rol];
      }
      });
      return;
    }
  });
