document.addEventListener('click', function (event) {
    var toggle = event.target.closest('[data-toggle-password]');
    if (!toggle) return;

    var wrap = toggle.closest('.password-field');
    var input = wrap.querySelector('input');
    var willReveal = input.type === 'password';

    input.type = willReveal ? 'text' : 'password';
    wrap.classList.toggle('revealed', willReveal);
    toggle.setAttribute('aria-label', willReveal ? 'Ocultar contraseña' : 'Mostrar contraseña');
});
