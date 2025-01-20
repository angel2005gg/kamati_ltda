document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('cambio_contrasena');
    const password1 = document.getElementById('txt_contrasena');
    const password2 = document.getElementById('txt_verificarContrasena');
    const submitBtn = document.getElementById('submitContrasena');
    const hiddenVerificacion = document.getElementById('hiddenContrasenaVerificacion');

    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            password1.disabled = false;
            password2.disabled = false;
            submitBtn.disabled = false;
            hiddenVerificacion.value = "1";
            submitBtn.style.backgroundColor = '#002d4b';
        } else {
            password1.disabled = true;
            password2.disabled = true;
            password1.value = "";
            password2.value = "";
            submitBtn.disabled = true;
            hiddenVerificacion.value = "0";
            submitBtn.style.backgroundColor = 'red';

        }
    });
});