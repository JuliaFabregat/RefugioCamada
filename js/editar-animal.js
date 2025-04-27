document.addEventListener('DOMContentLoaded', function () {
    // Sincronizar raza seleccionada con el campo de texto
    const razaSelect = document.getElementById('raza_id');
    const razaNombre = document.getElementById('raza_nombre');
    
    if (razaSelect && razaNombre) {
        razaSelect.addEventListener('change', function () {
            var razaSeleccionada = this.options[this.selectedIndex].text;
            razaNombre.value = razaSeleccionada;
        });
    }

    // Modal de confirmación
    const btnConfirmar = document.getElementById('btn-confirmar-cambios');
    const modal = document.getElementById('modalConfirmacion');
    const btnCancelar = document.getElementById('btn-cancelar');
    const btnAceptar = document.getElementById('btn-aceptar');

    if (btnConfirmar && modal && btnCancelar && btnAceptar) {
        btnConfirmar.addEventListener('click', function () {
            modal.classList.remove('hidden');
        });

        btnCancelar.addEventListener('click', function () {
            modal.classList.add('hidden');
        });

        btnAceptar.addEventListener('click', function () {
            document.querySelector('form').submit();
        });
    }
});
