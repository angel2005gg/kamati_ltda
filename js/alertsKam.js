function mostrarAlerta(tipo, mensaje) {
    const alertContainer = document.getElementById('alertContainer');
    const alertHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show fixed-alert" role="alert" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); width: 50%; text-align: center;">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
    alertContainer.innerHTML = alertHTML;
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
}

function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
          results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';

    try {
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    } catch (e) {
        console.error(`Error decoding parameter ${name}: ${e}`);
        return null;
    }
}

window.onload = function() {
    const mensaje = getParameterByName('mensaje');
    const tipo = getParameterByName('tipo');

    if (mensaje && tipo) {
        mostrarAlerta(tipo, mensaje);
    }
};