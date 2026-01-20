// * Hide and Show the sidebar
const toggleBtn = document.getElementById("toggle-button");
const sidebar = document.getElementById("sidebar");
const section = document.getElementById("section");

toggleBtn.addEventListener("click", () => {
  let icon = toggleBtn.firstElementChild.firstElementChild.className;
  if (icon == "bi bi-arrow-left-circle-fill") {
    icon = "bi bi-arrow-right-circle-fill";
    toggleBtn.firstElementChild.firstElementChild.className = icon
    section.style.width = "95%"
  } else {
    icon = "bi bi-arrow-left-circle-fill";
    toggleBtn.firstElementChild.firstElementChild.className = icon;
    section.style.width = "85%"
  }
  sidebar.classList.toggle("collapsed");

  setTimeout(() => {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  }, 1);
});


const buttons = document.querySelectorAll(".navbar-navegation button");
const current = document.querySelector(".navbar-navegation button.current-page");

buttons.forEach((btn) => {
  if (!btn.classList.contains("current-page")) {
    btn.addEventListener("mouseenter", () => {
      current.classList.add("suppress-underline");
    });

    btn.addEventListener("mouseleave", () => {
      current.classList.remove("suppress-underline");
    });
  }
});