// Timeout para que desaparezcan los mensajes de éxito
document.addEventListener('DOMContentLoaded', function () {

    const mensaje = document.getElementById('mensaje-exito');
    
    if (mensaje) {
        setTimeout(() => {
            mensaje.style.opacity = '0';
            setTimeout(() => mensaje.remove(), 500);
        }, 3000);
    }
});