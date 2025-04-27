// JS para los filtros de la tabla
document.addEventListener('DOMContentLoaded', function () {
    const especieSelect = document.getElementById('especie');
    const nombreInput = document.getElementById('nombre');
    const form = document.getElementById('filtroForm');

    let timeout = null;

    // Cambiar especie = auto-submit
    especieSelect.addEventListener('change', function () {
        form.submit();
    });

    // Buscar nombre = auto-submit con un poco de retraso
    nombreInput.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            form.submit();
        }, 200);
    });
});
