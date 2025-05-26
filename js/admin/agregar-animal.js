// Mostrar/ocultar la ficha veterinaria al marcar el checkbox
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('rellenar_ficha');
    const fichaVeterinaria = document.getElementById('ficha_veterinaria');

    // Que no debería, ya que la creamos vacía por defecto
    if (!checkbox || !fichaVeterinaria) {
        console.error("No se encontró el checkbox o la ficha veterinaria.");
        return;
    }

    // Mostrar u ocultar la ficha veterinaria dependiendo de si está marcado el checkbox
    checkbox.addEventListener('change', function () {
        fichaVeterinaria.style.display = checkbox.checked ? 'block' : 'none';
    });

    // Si está marcado por defecto, se muestra la ficha directamente
    if (checkbox.checked) {
        fichaVeterinaria.style.display = 'block';
    }
});