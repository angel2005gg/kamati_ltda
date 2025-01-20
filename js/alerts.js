document.addEventListener('DOMContentLoaded', function() {
    const alertContainer = document.getElementById('alertContainer');
    const alertHTML = `
        <div class="alert alert-success alert-dismissible fade show fixed-alert" role="alert" >
            <strong>¡BIENVENIDO!</strong>.
        </div>`;
    alertContainer.innerHTML = alertHTML;
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});