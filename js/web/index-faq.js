// Abrir y cerrar los elementos del FAQ
document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {

        const item = btn.closest('.faq-item');
        item.classList.toggle('active');
        
        document.querySelectorAll('.faq-item').forEach(other => {
            if (other !== item) other.classList.remove('active');
        });
    });
});