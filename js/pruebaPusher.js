Pusher.logToConsole = true;

var pusher = new Pusher('6dbee54e89268399db8d', {
    cluster: 'us2'
});

var channel = pusher.subscribe('chat-channel');

// Mostrar los datos recibidos en tiempo real
channel.bind('update-data', function(data) {
    // Aquí actualizas los campos con los datos recibidos
    if (data.elementId && data.value !== undefined) {
        var element = document.getElementById(data.elementId);
        if (element) {
            element.value = data.value;
        }
    }
});

// Función para enviar datos a Pusher
function sendUpdate(elementId, value) {
    fetch('../controlador/send-update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ elementId: elementId, value: value })
    })
    .then(response => response.text()) // Cambia a text() para obtener la respuesta completa
    .then(text => {
        console.log('Response Text:', text); // Muestra la respuesta completa
        try {
            const data = JSON.parse(text); // Intenta analizar como JSON
            console.log('Success:', data);
        } catch (e) {
            console.error('Failed to parse JSON:', e);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

// Manejar el input en tiempo real con un retraso
var debounce = function(func, delay) {
    let timer;
    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
};

var updateInput = debounce(function(elementId, value) {
    sendUpdate(elementId, value);
}, 500);

// Añadir listeners a los campos de texto e inputs
document.querySelectorAll('textarea, input, select, button').forEach(function(element) {
    element.addEventListener('input', function() {
        updateInput(this.id, this.value);
    });
});