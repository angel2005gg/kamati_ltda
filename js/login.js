document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let identificacion = document.getElementById('identificacion').value;
    let contrasena = document.getElementById('contrasena').value;

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'controlador/procesarFormulario.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            let alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = '';

            if (response.status === 'success') {
                window.location.href = response.redirect;
            } else {
                let alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger custom-alert';
                alertDiv.role = 'alert';
                alertDiv.textContent = response.message;
                alertContainer.appendChild(alertDiv);

                setTimeout(function() {
                    alertDiv.style.opacity = '0';
                    setTimeout(function() {
                        alertDiv.remove();
                    }, 500);
                }, 3000);
            }
        }
    };

    xhr.send('txt_identificacion=' + encodeURIComponent(identificacion) + '&txt_contrasena=' + encodeURIComponent(contrasena));
});