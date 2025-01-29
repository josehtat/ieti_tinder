<?php
// Verificar si el usuario tiene la cookie de autenticación y el rol adecuado
if (!isset($_COOKIE['loggedUser']) || !isset($_COOKIE['userRole']) || $_COOKIE['userRole'] !== 'admin') {
    // Redirigir al login si no está autenticado como administrador
    http_response_code(403);
    header("Location: ../error/403.php");
    die("Error 403: Prohibido");
}

// Puedes añadir un mensaje o lógica adicional aquí, si lo necesitas
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?t=<?php echo time(); ?>">
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/script.js"></script>
    <title>Panel Administrativo</title>
</head>

<body class="admin-body">
    <div class="admin-container">
        <header>
            <div class="header-content">
                <h1>Bienvenido al Panel Administrativo</h1>
                <p>Esta sección es solo para usuarios con rol de administrador.</p>
            </div>
            <div class="header-buttons">
                <button id="backButton">Volver</button>
                <button id="usersButton">Ver usuarios</button>
                <button id="logsButton">Ver logs</button>
            </div>
        </header>
        <main>


            <?php

            function get_users()
            {
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
                    "WHERE NOT role = 'admin'";

                try {
                    //preparem i executem la consulta
                    $queryUser = $pdo->prepare($queryText);
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

                return $queryUser->fetchAll();
            }

            function get_user_by_id($id)
            {
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
                $queryText = "SELECT * FROM users;";

                //    $unhashedId = hash('sha256', $id);
                try {
                    //preparem i executem la consulta
                    $queryUser = $pdo->prepare($queryText);
                    $queryUser->execute();
                } catch (PDOException $e) {
                    echo "Error de SQL 2<br>\n";
                    //comprovo errors:
                    $e = $queryUser->errorInfo();
                    if ($e[0] != '00000') {
                        echo "\nPDO::errorInfo():\n";
                        die("Error accedint a dades: " . $e[2]);
                    }
                }

                foreach ($queryUser as $row) {
                    if ($row['email_user'] == $id) {
                        return $row;
                    }
                }
            }

            if (isset($_GET['id'])) {
                $user_id = $_GET['id'];
                $user = get_user_by_id($user_id); // Funció per obtenir el usuario específico
            
                if ($user) {
                    echo "<h2>Usuario {$user['name']}</h2>";
                    echo "<div id='userContainer'>";
                    echo "<p>Nombre: {$user['name']}</p>";
                    echo "<p>Apellidos: {$user['surnames']}</p>";
                    echo "<p>Email: {$user['email_user']}</p>";
                    echo "<p>Fecha de nacimiento: {$user['birthday']}</p>";
                    echo "</div>";
                } else {
                    echo "<p>Error: No se ha encontrado el usuario especificado.</p>";
                }
            } else {
                // Connexió a la base de dades o accés al sistema de fitxers
                $users = get_users(); // Funció per obtenir els logs disponibles
                $per_page = 25; // Màxim de resultats per pàgina
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                // Càlcul de l'inici i final per paginar
                $start = ($page - 1) * $per_page;
                $total_users = count($users);
                $paged_users = array_slice($users, $start, $per_page);

                // Renderització
                echo "<table>";
                echo "<tr>";
                echo "<th>Email</th>";
                echo "<th>Nombre</th>";
                echo "<th>Apellidos</th>";
                echo "<th>Acciones</th>";
                echo "</tr>";
                if (empty($paged_users)) {
                    echo "<tr><td colspan='4'>No hay usuarios disponibles.</td></tr>";
                } else {
                    foreach ($paged_users as $user) {
                        echo "<tr>";
                        echo "<td>{$user['email_user']}</td>";
                        echo "<td>{$user['name']}</td>";
                        echo "<td>{$user['surnames']}</td>";
                        echo "<td><a class='viewUser'>Ver usuario</a></td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";

                // Paginador
                $total_pages = ceil($total_users / $per_page);
                if ($total_pages > 1) {
                    echo "<div id='userPagination'>";
                    echo "<a>&lt;&lt;</a>";
                    echo "<a>&lt;</a>";
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<a>$i</a>";
                    }
                    echo "<a>&gt;</a>";
                    echo "<a>&gt;&gt;</a>";
                    echo "</div>";
                }
            }
            ?>
        </main>
    </div>
    <script>
        // Resto de tu JavaScript
        $(document).ready(function () {
            // Resto de tu JavaScript
            $("#backButton").click(function () {
                console.log("Esto se ha ejecutado mientras la función se esta ejecutando");
                <?php if (isset($_GET['id'])) { ?>
                    window.location.href = "/admin/users.php";
                <?php } else { ?>
                    window.location.href = "/admin";
                <?php } ?>
            });

            $("#logsButton").click(function () {
                window.location.href = "/admin/logs.php";
            });

            $("#usersButton").click(function () {
                window.location.href = "/admin/users.php";
            });

            var currentPage = <?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?>;
            var totalPages = <?php echo isset($total_pages) ? $total_pages : 1; ?>; // Total de paginas

            $("#userPagination").on("click", "a", function () {
                var page = $(this).text();
                if (page == "<<") {
                    page = 1;
                } else if (page == "<") {
                    page = currentPage - 1;
                    if (page < 1) {
                        page = 1;
                    }
                } else if (page == ">") {
                    page = currentPage + 1;
                    if (page > totalPages) {
                        page = totalPages;
                    }
                } else if (page == ">>") {
                    page = totalPages;
                }

                if (page == 1) {
                    window.location.href = "/admin/users.php";
                } else {
                    window.location.href = "/admin/users.php?page=" + page;
                }
            });

            $("#userPagination a").each(function () {
                var element = $(this);
                if (element.text() == "<<" && currentPage == 1) {
                    element.addClass("disabled");
                }
                if (element.text() == "<" && currentPage == 1) {
                    element.addClass("disabled");
                }
                if (element.text() == currentPage) {
                    element.addClass("active");
                }
                if (element.text() == ">" && currentPage == totalPages) {
                    element.addClass("disabled");
                }
                if (element.text() == ">>" && currentPage == totalPages) {
                    element.addClass("disabled");
                }

            })

            $(".viewUser").click(function () {
                var userId = $(this).closest("tr").find("td").eq(0).text();
                window.location.href = "/admin/users.php?id=" + userId;
            });
        })
    </script>
</body>

</html>