let animalIdToDelete = null;
let animalNombreToDelete = null;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            animalIdToDelete = btn.getAttribute('data-id');
            animalNombreToDelete = btn.getAttribute('data-nombre');

            document.getElementById('animalNombreModal').textContent = animalNombreToDelete;
            document.getElementById('animalNombreModalFinal').textContent = animalNombreToDelete;

            $('#confirmDeleteModal').modal('show');
        });
    });

    document.getElementById('firstConfirmBtn').addEventListener('click', () => {
        $('#confirmDeleteModal').modal('hide');
        $('#finalConfirmDeleteModal').modal('show');
    });

    document.getElementById('finalConfirmBtn').addEventListener('click', () => {
        if (animalIdToDelete) {
            window.location.href = `eliminar-animal.php?id=${animalIdToDelete}`;
        }
    });
});
