document.addEventListener("DOMContentLoaded", function() {
  const btnSeleccionar = document.getElementById("btnSeleccionar");
  const checkAllContainer = document.getElementById("checkAllContainer");
  const checkAll = document.getElementById("checkAll");
  const headerCheckAll = document.getElementById("headerCheckAll");
  const checkboxes = document.querySelectorAll(".check-student");
  const checkCols = document.querySelectorAll(".check-col");
  const cancel = document.getElementById('btnCancelar');

  let seleccionActivo = false;

  // Activar selección y mostrar checkboxes


  // Seleccionar/Deseleccionar todos los checkboxes
  checkAll.addEventListener("change", function() {
      checkboxes.forEach(checkbox => checkbox.checked = checkAll.checked);
      actualizarBoton();
  });

  // Detectar cambios en los checkboxes individuales
  checkboxes.forEach(checkbox => {
      checkbox.addEventListener("change", actualizarBoton);
  });

  function toggleSeleccion(activar) {
      checkAllContainer.classList.toggle("d-none", !activar);
      headerCheckAll.classList.toggle("d-none", !activar);
      checkCols.forEach(col => col.classList.toggle("d-none", !activar));

      if (!activar) {
          checkboxes.forEach(checkbox => checkbox.checked = false);
          checkAll.checked = false;
          btnSeleccionar.innerHTML = '<i class="la la-check-square"></i> Seleccionar';
          btnSeleccionar.classList.replace("btn-danger", "btn-warning");
      }
  }

  function actualizarBoton() {
      let seleccionados = [...checkboxes].some(checkbox => checkbox.checked);

      if (seleccionados) {
          btnSeleccionar.innerHTML = '<i class="la la-trash"></i> Borrar';
          btnSeleccionar.classList.replace("btn-warning", "btn-danger");
      } else {
          btnSeleccionar.innerHTML = '<i class="la la-check-square"></i> Seleccionar';
          btnSeleccionar.classList.replace("btn-danger", "btn-warning");
      }
  }
});

let isSelected = false;

document.getElementById("btnSeleccionar").addEventListener("click", function () {
  
  
  isSelected = true
console.log(isSelected);
document.getElementById("headerCheckAll").classList.remove("d-none");


document.querySelectorAll(".check-col").forEach(function (el) {
el.classList.remove("d-none");
});

  document.getElementById("btnCancelar").classList.remove("d-none");
 
  // Mostrar los checkboxes de cada fila
});

document.getElementById("btnCancelar").addEventListener("click", function () {

  isSelected = true;
  this.classList.add("d-none");
  document.getElementById("headerCheckAll").classList.add("d-none");

  // Ocultar los checkboxes de cada fila y deseleccionarlos
  document.querySelectorAll(".check-col").forEach(function (el) {
      el.classList.add("d-none");
  });
  btnSeleccionar.innerHTML = '<i class="la la-select"></i> Seleccionar';
  btnSeleccionar.classList.replace("btn-danger", "btn-warning");

  document.querySelectorAll(".check-student, #checkAll").forEach(function (checkbox) {
      checkbox.checked = false;
  });
});