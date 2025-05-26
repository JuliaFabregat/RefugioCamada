// Modal del envío del formulario
const btnCerrar = document.getElementById('cerrar-modal');
if (btnCerrar) {
    btnCerrar.addEventListener('click', () => {
        document.getElementById('modal-enviado').style.display = 'none';
        history.replaceState(null, '', 'contacto.php'); // Limpia el ?enviado=1 de la URL
    });
}
