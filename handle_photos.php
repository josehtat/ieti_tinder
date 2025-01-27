<?php
$hostname = "localhost";
$dbname = "ieti_tinder";
$dbUsername = "ietitinder";
$pw = "tinder123";
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbUsername, $pw);
} catch (PDOException $e) {
    echo "Error al acceder a la base de datos - " . $e->getMessage() . "\n";
    exit;
}

$response = ["success" => false, "message" => "", "totalImages" => 0];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Eliminar imagen
    if (isset($_POST['deletePhoto'])) {
        $imageId = $_POST['deletePhoto'];

        $query = "SELECT path FROM pictures WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $imageId);
        $stmt->execute();
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($photo) {
            $filePath = $photo['path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $query = "DELETE FROM pictures WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $imageId);
            if ($stmt->execute()) {
                $response["success"] = true;
            } else {
                $response["message"] = "Error al eliminar la imagen de la base de datos.";
            }
        } else {
            $response["message"] = "La imagen no se encontró en la base de datos.";
        }
    }

    // Subir nueva foto
    if (isset($_FILES['newPhoto']) && $_FILES['newPhoto']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'profilePictures/';
        $originalName = $_FILES['newPhoto']['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $query = "SELECT id FROM pictures ORDER BY id DESC LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $imageId = $stmt->fetch(PDO::FETCH_ASSOC)['id'] + 1;

        $mailInArray = explode('@', $_COOKIE['loggedUser']);
        $imageName = $mailInArray[0] . "_" . $imageId . "." . $extension;

        $file_name = basename($imageName);
        $uploadFile = $uploadDir . $file_name;
        if (move_uploaded_file($_FILES['newPhoto']['tmp_name'], $uploadFile)) {
            $query = "INSERT INTO pictures (email_user, path) VALUES (:email, :path)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $_COOKIE['loggedUser']);
            $stmt->bindParam(':path', $uploadFile);
            if ($stmt->execute()) {
                $response["success"] = true;
                $response["imagePath"] = htmlspecialchars($uploadFile);
                $response["imageId"] = $pdo->lastInsertId();
            } else {
                $response["message"] = "Error en la ejecución de la consulta SQL.";
            }
        } else {
            $response["message"] = "Error al mover la foto a su destino final.";
        }
    }

    // Contar el total de imágenes después de cualquier cambio
    $countQuery = "SELECT count(*) as total FROM pictures WHERE email_user = :email";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->bindParam(':email', $_COOKIE['loggedUser']);
    $countStmt->execute();
    $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
    $response["totalImages"] = $countResult['total'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
