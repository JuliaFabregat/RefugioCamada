// Modal de Eliminacion
document.addEventListener('DOMContentLoaded', () => {
    let animalIdToDelete = null;
    let animalNombreToDelete = null;

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            animalIdToDelete = btn.getAttribute('data-id');
            animalNombreToDelete = btn.getAttribute('data-nombre');

            document.getElementById('nombreAnimalModal').textContent = animalNombreToDelete;

            $('#modalEliminar').modal('show'); // jQuery + Bootstrap modal
        });
    });

    document.getElementById('confirmarEliminar').addEventListener('click', () => {
        if (animalIdToDelete) {
            window.location.href = `eliminar-animal.php?id=${animalIdToDelete}`;
        }
    });
});