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
    var container = document.getElementById('messages-container');

    // Elimina cualquier mensaje existente antes de agregar uno nuevo
    container.innerHTML = '';

    // Crear el nuevo mensaje
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

    // Agregar el mensaje al contenedor
    container.appendChild(messageContainer);

    // Eliminar el mensaje después de 1,5 segundos
    setTimeout(function() {
        messageContainer.style.opacity = 0;
        setTimeout(function() {
            if (container.contains(messageContainer)) {
                container.removeChild(messageContainer);
            }
        }, 500);
    }, 1500);
}


// Cambiar imagen al hacer clic
function toggleImage(type) {
    if (type === 'dislike') {
        const dislikeImage = document.getElementById('dislikeImg');
        dislikeImage.src = dislikeImage.src.includes('cruzV2.png') 
            ? 'img/cruzV1.png' 
            : 'img/cruzV2.png'; 
    } else if (type === 'like') {
        const likeImage = document.getElementById('likeImg');
        likeImage.src = likeImage.src.includes('corazonV2.png') 
            ? 'img/corazonV1.png' 
            : 'img/corazonV2.png';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    let message = "Bienvenido a la página";

    if (window.location.pathname.includes("messages.php")) {
        message = "Bienvenido a tu bandeja de mensajes";
    } else if (window.location.pathname.includes("profile.php")) {
        message = "Aquí puedes ver tu perfil";
    } else if (window.location.pathname.includes("discober.php")) {
        message = "Descubre nuevos matches";
    } else if (window.location.pathname.includes("conversation.php")) {
        message = "Bienvenido a tu conversación";
    }
    showMessage('info', message);
});
