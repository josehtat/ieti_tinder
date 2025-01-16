<?php

// Función que comprueba si el archivo existe y lo crea o actualiza si es necesario
function createFileWithDate($text) {
    // Obtener la fecha actual en formato YYYY-MM-DD
    $date = date('Y-m-d');
    
    // Definir la ruta de la carpeta logs
    $logsFolder = 'logs/';

    // Definir el nombre del archivo con la fecha actual y la ruta completa
    $file = $logsFolder . $date . '.txt';

    // Siempre escribir el texto en el archivo (agregar al final)
    file_put_contents($file, $text . PHP_EOL, FILE_APPEND);
    echo "Texto guardado en el archivo de la carpeta logs.";
}

// Verificar si se ha enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el texto enviado desde AJAX
    $text = $_POST['text'] ?? '';

    if (!empty($text)) {
        // Llamar a la función con el texto
        createFileWithDate($text);
    } else {
        echo "No se ha recibido texto válido.";
    }
}
?>

