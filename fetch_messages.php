<?php
// Configurar la conexión a la base de datos
try {
    $hostname = "localhost";
    $dbname = "ieti_tinder";
    $dbUsername = "ietitinder";
    $pw = "tinder123";
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbUsername, $pw);
} catch (PDOException $e) {
    echo "Error al acceder a la base de datos - " . $e->getMessage() . "\n";
    exit;
}

// Obtener el mail del receptor desde la solicitud GET
$mail_receptor = $_GET['mail'];

// Obtener el mail del usuario logueado desde las cookies
$user_email = $_COOKIE['loggedUser'];

// Obtener la imagen del receptor
$query = "SELECT p.path FROM pictures p JOIN users u ON p.email_user = u.email_user WHERE u.email_user = :mail_receptor";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':mail_receptor', $mail_receptor);
$stmt->execute();
$row = $stmt->fetch();
$image_path = $row['path'];

// Obtener los mensajes entre el usuario logueado y el receptor
$query = "
    SELECT id_user, message_user, date 
    FROM messages
    WHERE (id_user = :user_email AND id_receptor = :receiver_email)
    OR (id_user = :receiver_email AND id_receptor = :user_email)
    ORDER BY date ASC
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_email', $user_email);
$stmt->bindParam(':receiver_email', $mail_receptor);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los mensajes
$last_time = null;
foreach ($messages as $message):
    $sender = ($message['id_user'] == $user_email) ? 'sent' : 'received';
    $message_time = strtotime($message['date']);
    $current_time = time();
    $time_diff = $current_time - $message_time;

    // Mostrar la fecha solo si ha pasado más de 5 minutos desde el último mensaje
    $show_date = false;

    // Si es el primer mensaje o han pasado más de 5 minutos desde el último mensaje, mostramos la fecha
    if (!$last_time || $time_diff > 300) {
        $show_date = true;
    }

    // Mostrar la fecha solo si se debe mostrar
    if ($show_date) {
        $formatted_date = date("d M Y, H:i", $message_time);
        echo "<div class='date'>$formatted_date</div>";
    }

    if ($sender == 'received') {
        echo "<div class='messageWithImage'>
                <img src='$image_path' alt='Foto de perfil' class='profileImageConversation'>
                <div class='messageConversation $sender'>" . htmlspecialchars($message['message_user']) . "</div>
              </div>";
    } else {
        echo "<div class='messageConversation $sender'>" . htmlspecialchars($message['message_user']) . "</div>";
    }

    $last_time = $message_time;
endforeach;
?>