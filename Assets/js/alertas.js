var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

function alertas(mensaje, icono) {
  Toast.fire({
    icon: icono,
    title: mensaje,
    showConfirmButton: false,
    timer: 3000
  });
}

window.alertas = alertas;