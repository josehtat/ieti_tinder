<?php
session_start();

// Función para calcular la distancia entre dos puntos usando la fórmula de Haversine
function haversine($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // Radio de la Tierra en kilómetros

    // Convertir grados a radianes
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Fórmula de Haversine
    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;
    $a = sin($dlat / 2) * sin($dlat / 2) +
        cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return $distance; // Retorna la distancia en kilómetros
}


$filter = false;
if (isset($_POST['filter']) && $_POST['filter'] == true) {
    $filter = true;
}

//Recoge cookie de userProfiles
if (isset($_SESSION['userProfiles']) && ($filter == false)) {
    $status = 0;
    $foundUserList = $_SESSION['userProfiles'];

    if (count($foundUserList) > 0) {
        // Borrar el primero de la lista
        if (isset($_POST['reaction']) && ($_POST['reaction'] == true)) {
            array_shift($foundUserList);
            if (count($foundUserList) > 0) {
                $_SESSION['userProfiles'] = $foundUserList;
            } else {
                unset($_SESSION['userProfiles']);
            }
        }
        if (count($foundUserList) > 0) {
            echo json_encode([
                'status' => $status,
                'data' => $foundUserList
            ]);
        }
    } else {
        unset($_SESSION['userProfiles']);
    }
}


if (!isset($_SESSION['userProfiles']) || $filter == false) {
    $foundUserList = array();

    try {
        $hostname = "localhost";
        $dbname = "ieti_tinder";
        $dbUsername = "ietitinder";
        $pw = "tinder123";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
    } catch (PDOException $e) {
        echo "Error al accedir a la base de dades - " . $e->getMessage() . "\n";
        exit;
    }


    //preparem i executem la consulta
    $queryText = "SELECT * FROM users " .
        "WHERE email_user = :mail;";

    try {
        //preparem i executem la consulta
        $queryUser = $pdo->prepare($queryText);
        $queryUser->bindParam(':mail', $_COOKIE['loggedUser']);
        $queryUser->execute();
    } catch (PDOException $e) {
        echo "Error de SQL 1<br>\n";
        //comprovo errors:
        $e = $queryUser->errorInfo();
        if ($e[0] != '00000') {
            echo "\nPDO::errorInfo():\n";
            die("Error accedint a dades: " . $e[2]);
        }
    }

    if ($queryUser->rowCount() <= 0 || $queryUser->rowCount() >= 2) {
        $status = 1;
        $logMessage = "Usuario incorrecto";
    } else {
        $location = null;
        $sex = null;
        $sexualOrientation = null;
        $age = null;
        foreach ($queryUser as $row) {
            $fullLocation = $row['location'];
            $location = explode(" ", $fullLocation);
            $sex = $row['sex'];
            $sexualOrientation = $row['sex_orientation'];
            $currentDate = new DateTime();
            $birthday = new DateTime($row['birthday']);
            $age = ($birthday->diff($currentDate))->y;
        }

        if ($location != null && $sex != null && $sexualOrientation != null && $age != null) {
            if ($sexualOrientation == "heterosexual") {
                $queryText = "SELECT * FROM users " .
                    "WHERE NOT sex = :sex AND NOT sex = 'NB' " .
                    "AND (sex_orientation = 'heterosexual' OR sex_orientation = 'bisexual') " .
                    "AND NOT email_user = :mail;";
            } else if ($sexualOrientation == "homosexual") {
                $queryText = "SELECT * FROM users " .
                    "WHERE sex = :sex " .
                    "AND (sex_orientation = 'homosexual' OR sex_orientation = 'bisexual') " .
                    "AND NOT email_user = :mail;";
            } else {
                $queryText = "SELECT * FROM users " .
                    "WHERE NOT email_user = :mail;";
            }

            try {
                //preparem i executem la consulta
                $queryFinds = $pdo->prepare($queryText);
                if ($sexualOrientation != "bisexual") {
                    $queryFinds->bindParam(':sex', $sex);
                }
                $queryFinds->bindParam(':mail', $_COOKIE['loggedUser']);
                $queryFinds->execute();
            } catch (PDOException $e) {
                echo "Error de SQL 2<br>\n";
                //comprovo errors:
                $e = $queryUser->errorInfo();
                if ($e[0] != '00000') {
                    echo "\nPDO::errorInfo():\n";
                    die("Error accedint a dades: " . $e[2]);
                }
            }

            if ($queryFinds->rowCount() <= 0) {
                $status = 1;
                $logMessage = "No hay gente disponible";
            } else {
                $queryText = "SELECT * FROM interactions " .
                    "WHERE (id_user = :mail OR id_receptor = :mail) " .
                    "AND (id_user = :findUser OR id_receptor = :findUser);";

                $foundUserList = array();

                foreach ($queryFinds as $find) {
                    try {
                        $queryInteraction = $pdo->prepare($queryText);
                        $queryInteraction->bindParam(':mail', $_COOKIE['loggedUser']);
                        $queryInteraction->bindParam(':findUser', $find['email_user']);
                        $queryInteraction->execute();
                    } catch (PDOException $e) {
                        echo "Error de SQL 3<br>\n";
                        $e = $queryInteraction->errorInfo();
                        if ($e[0] != '00000') {
                            echo "\nPDO::errorInfo():\n";
                            die("Error accedint a dades: " . $e[2]);
                        }
                    }

                    $currentDate = new DateTime();
                    $findBirthday = new DateTime($find['birthday']);
                    $findAge = ($findBirthday->diff($currentDate))->y;
                    $findName = $find['name'];
                    $findEmail = $find['email_user'];
                    $findFullLocation = $find['location'];
                    $findLocation = explode(" ", $findFullLocation);
                    $findPoints = $find['points'];

                    if ($queryInteraction->rowCount() <= 0) {
                        $foundUserList[] = array('email' => $findEmail, 'name' => $findName, 'age' => $findAge, 'latitude' => $findLocation[0], 'longitude' => $findLocation[1], 'points' => $findPoints);
                    } else {
                        foreach ($queryInteraction as $inter) {
                            if (($inter['id_user'] == $_COOKIE['loggedUser'] && $inter['like_user'] == null) || ($inter['id_receptor'] == $_COOKIE['loggedUser'] && is_null($inter['like_receptor']))) {
                                $foundUserList[] = array('email' => $findEmail, 'name' => $findName, 'age' => $findAge, 'latitude' => $findLocation[0], 'longitude' => $findLocation[1], 'points' => $findPoints);
                            }
                        }
                    }
                }
                // Agregar la distancia a cada ubicación
                foreach ($foundUserList as $key => &$foundUser) {
                    $foundUser['distance'] = haversine($location[0], $location[1], $foundUser['latitude'], $foundUser['longitude']);
                    $foundUser['pictures'] = array();

                    $maxDistance = 50;
                    if (isset($_POST['maxDistance'])) {
                        $maxDistance = $_POST['maxDistance'];
                    }

                    $minAge = 18;
                    if (isset($_POST['minAge'])) {
                        $minAge = $_POST['minAge'];
                    }

                    $maxAge = 38;
                    if (isset($_POST['maxAge'])) {
                        $maxAge = $_POST['maxAge'];
                    }

                    if ($foundUser['distance'] > $maxDistance) {
                        unset($foundUserList[$key]);
                    }

                    //Age must be be between minAge and maxAge
                    if ($foundUser['age'] < $minAge || $foundUser['age'] > $maxAge) {
                        unset($foundUserList[$key]);
                    }


                    $queryText = "SELECT * FROM pictures " .
                        "WHERE email_user = :findUser;";

                    try {
                        $queryPictures = $pdo->prepare($queryText);
                        $queryPictures->bindParam(':findUser', $foundUser['email']);
                        $queryPictures->execute();
                    } catch (PDOException $e) {
                        echo "Error de SQL 4<br>\n";
                        $e = $queryPictures->errorInfo();
                        if ($e[0] != '00000') {
                            echo "\nPDO::errorInfo():\n";
                            die("Error accedint a dades: " . $e[2]);
                        }
                    }

                    if ($queryPictures->rowCount() > 0) {
                        foreach ($queryPictures as $picture) {
                            $foundUser['pictures'][] = $picture['path'];
                        }
                    }
                }
                unset($foundUser); // Limpiar referencia

                // Ordenar la lista de usuarios por distancia
                usort($foundUserList, function ($a, $b) {
                    return $a['distance'] - $b['distance'];
                });

                // Ordenar la lista de usuarios por puntuación
                usort($foundUserList, function ($a, $b) {
                    return $b['points'] - $a['points'];
                });

                $status = 0;

                // Devolver la lista de usuarios
                $_SESSION['userProfiles'] = $foundUserList;

                echo json_encode([
                    'status' => $status,
                    'data' => $foundUserList
                ]);
            }
        } else {
            $status = 1;
            $logMessage = "Datos incorrectos";
            echo json_encode([
                'status' => $status,
                'data' => $logMessage
            ]);
        }
    }
}
