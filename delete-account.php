<?php
$mail = $_COOKIE['loggedUser'];
$status = 0;
$logMessage = "";

try {
    $hostname = "localhost";
    $dbname = "ieti_tinder";
    $dbUsername = "ietitinder";
    $pw = "tinder123";
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
} catch (PDOException $e) {
    $status = 4;
    $logMessage = "Database error: " . $e->getMessage();
}

$queryText = "UPDATE users SET account_status = 'inactive' WHERE email_user = :mail;";

try {
    //preparem i executem la consulta
    $queryDisableUser = $pdo->prepare($queryText);
    $queryDisableUser->bindParam(':mail', $mail);
    $queryDisableUser->execute();
} catch (PDOException $e) {
    echo "Error de SQL<br>\n";
    //comprovo errors:
    $e = $queryDisableUser->errorInfo();
    if ($e[0] != '00000') {
        echo "\nPDO::errorInfo():\n";
    }
}

if ($queryDisableUser->rowCount() > 0) {
    $status = 0;
    $logMessage = "Cuenta del usuario " . $mail . " desactivada";
    setcookie("loggedUser", "", time() - 3600);
} else {
    $status = 1;
    $logMessage = "Error al desactivar la cuenta";
}

echo json_encode([
    'status' => $status,
    'data' => $logMessage
]);
