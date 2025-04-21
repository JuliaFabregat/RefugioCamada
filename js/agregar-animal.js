// agregar-animales.php - FICHA VETERINARIA
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('rellenar_ficha');
    const fichaVeterinaria = document.getElementById('ficha_veterinaria');

    if (!checkbox || !fichaVeterinaria) {
        console.error("No se encontró el checkbox o la ficha veterinaria.");
        return;
    }

    // Mostrar u ocultar la ficha veterinaria dependiendo del estado del checkbox
    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
            fichaVeterinaria.style.display = 'block';
        } else {
            fichaVeterinaria.style.display = 'none';
        }
    });

    // Asegurarse de que el estado inicial sea correcto
    if (checkbox.checked) {
        fichaVeterinaria.style.display = 'block';
    }
});