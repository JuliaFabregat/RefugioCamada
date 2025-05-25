// Script para el botón de volver arriba
const btnScrollTop = document.getElementById('btnScrollTop');

window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
        btnScrollTop.classList.add('show'); // Mostramos el boton si se he scrolleado más de 100px
    } else {
        btnScrollTop.classList.remove('show'); // Ocultamos el boton si no se he scrolleado más de 100px
    }
});

btnScrollTop.addEventListener('click', () => {
    window.scrollTo({ // Volvemos al inicio de la página
        top: 0,
        behavior: 'smooth'
    });
});
