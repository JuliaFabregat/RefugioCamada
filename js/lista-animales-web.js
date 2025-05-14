document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filtroForm');
    const sel = document.getElementById('especie');
    const input = document.getElementById('nombre');
    let to = null;

    // Auto-submit al cambiar especie
    sel.addEventListener('change', () => form.submit());

    // Auto-submit al teclear nombre (200 ms de debounce)
    input.addEventListener('input', () => {
        clearTimeout(to);
        to = setTimeout(() => form.submit(), 200);
    });
});
