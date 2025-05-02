// Función para controlar los clics en las filas
function handleRowClick(event, animalId) {
    // Si el clic dentro de la celda de acciones (editar/eliminar), no hacer nada
    if (event.target.closest('.animal-acciones')) return;

    // Si no, redirigir a la ficha del animal
    window.location.href = `animal.php?id=${animalId}`;
}

// Filtros de la tabla - Tiempo de actualización sin un botón que sea "filtrar"
document.addEventListener('DOMContentLoaded', function () {
    const especieSelect = document.getElementById('especie');
    const estadoSelect = document.getElementById('estado');
    const nombreInput = document.getElementById('nombre');
    const form = document.getElementById('filtroForm');

    let timeout = null;

    // Cambiar especie = auto-submit
    especieSelect.addEventListener('change', function () {
        form.submit();
    });

    // Cambiar estado = auto-submit
    estadoSelect.addEventListener('change', function () {
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
