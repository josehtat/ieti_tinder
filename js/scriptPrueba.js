// Función que se ejecuta cuando se hace clic en el botón
function enviarSolicitud(textolog) {
    // Crear una solicitud HTTP (AJAX)
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "logs.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Obtener la hora actual
    var hora = new Date().toLocaleTimeString();

    // Crear el mensaje con la hora
    var texto = "[" + hora + "] " + textolog + ".";

    // Enviar la solicitud al archivo PHP con el texto
    xhr.send("texto=" + encodeURIComponent(texto));

}