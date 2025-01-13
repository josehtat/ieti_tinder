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

