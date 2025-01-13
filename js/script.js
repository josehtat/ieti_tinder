// Función logs
function sendRequest(logText) {
    // Crear una solicitud HTTP (AJAX)
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "logs.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Obtener la hora actual
    var currentTime = new Date().toLocaleTimeString();
    // Crear el mensaje con la hora
    var formattedText = "[" + currentTime + "] " + logText + ".";
    // Enviar la solicitud al archivo PHP con el texto
    xhr.send("text=" + encodeURIComponent(formattedText));
}

// Funcion control de errores
function showMessage(type, message) {
    // Crear el contenedor del mensaje
    var messageContainer = document.createElement('div');
    messageContainer.classList.add('message');

    // Añadir clase según el tipo de mensaje
    switch(type) {
        case 'info':
            messageContainer.classList.add('message-info');
            break;
        case 'error':
            messageContainer.classList.add('message-error');
            break;
        case 'warning':
            messageContainer.classList.add('message-warning');
            break;
        default:
            messageContainer.classList.add('message-info');
    }

    // Establecer el texto del mensaje
    messageContainer.textContent = message;

    // Añadir el mensaje al contenedor de mensajes
    var container = document.getElementById('messages-container');
    container.appendChild(messageContainer);

    // Eliminar el mensaje después de 5 segundos
    setTimeout(function() {
        messageContainer.style.opacity = 0;
        setTimeout(function() {
            container.removeChild(messageContainer);
        }, 500); // Tiempo para la animación de desaparición
    }, 5000); // El mensaje desaparecerá después de 5 segundos
}
