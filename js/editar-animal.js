document.addEventListener('DOMContentLoaded', function () {
    // Al cambiar la raza seleccionada, actualiza el campo de texto con su nombre
    const razaSelect = document.getElementById('raza_id');
    const razaNombre = document.getElementById('raza_nombre');

    if (razaSelect && razaNombre) {
        razaSelect.addEventListener('change', function () {
            var razaSeleccionada = this.options[this.selectedIndex].text;
            razaNombre.value = razaSeleccionada;
        });
    }
});

// Vista previa de imagen cuando se selecciona una nueva
const imagenInput = document.getElementById('imagen-input');
const imagenPreview = document.querySelector('.animal-imagen-clickable');

if (imagenInput && imagenPreview) {
    imagenInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = () => imagenPreview.src = reader.result;
        reader.readAsDataURL(file);
    });
}