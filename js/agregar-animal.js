// agregar-animales.php - FICHA VETERINARIA
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('rellenar_ficha');
    const fichaVeterinaria = document.getElementById('ficha_veterinaria');

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