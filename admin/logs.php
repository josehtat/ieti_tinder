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
                <button id="exitButton">Cerrar sesión de administrador</button>
            </div>
        </header>
        <main>


            <?php
            function get_logs()
            {
                // Connexió a la base de dades o accés al sistema de fitxers
                $logs = glob('../logs/*.txt');

                $formatted_logs = array();

                foreach ($logs as $log) {
                    $log_info = pathinfo($log);
                    $formatted_logs[] = array(
                        'id' => $log_info['filename'] . '.' . $log_info['extension'],
                        'name' => $log_info['filename'] . '.' . $log_info['extension'],
                        'size' => round(filesize($log) / 1024, 2), // Convertir a KB
                        'date' => date('d/m/Y', filemtime($log))
                    );
                }

                return $formatted_logs;
            }

            function get_log_by_id($id)
            {
                // Connexió a la base de dades o accés al sistema de fitxers
                $log = file_get_contents('../logs/' . $id);
                return $log;
            }

            if (isset($_GET['id'])) {
                $log_id = $_GET['id'];
                $log = get_log_by_id($log_id); // Funció per obtenir el log específic

                if ($log) {
                    $lines = explode("\n", $log);
                    echo "<h2>Logs del {$log_id}</h2>";
                    echo "<div id='logContainer'>";
                    foreach ($lines as $line) {
                        echo "<p>{$line}</p>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>Error: No se ha encontrado el log especificado.</p>";
                }
            } else {
                // Connexió a la base de dades o accés al sistema de fitxers
                $logs = get_logs(); // Funció per obtenir els logs disponibles
                $per_page = 25; // Màxim de resultats per pàgina
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                // Càlcul de l'inici i final per paginar
                $start = ($page - 1) * $per_page;
                $total_logs = count($logs);
                $paged_logs = array_slice($logs, $start, $per_page);

                // Renderització
                echo "<table>";
                echo "<tr>";
                echo "<th>Nombre</th>";
                echo "<th>Tamaño</th>";
                echo "<th>Fecha</th>";
                echo "<th>Acciones</th>";
                echo "</tr>";
                if (empty($paged_logs)) {
                    echo "<tr><td colspan='4'>No hay logs disponibles.</td></tr>";
                } else {
                    foreach ($paged_logs as $log) {
                        echo "<tr>";
                        echo "<td>{$log['name']}</td>";
                        echo "<td>{$log['size']} KB</td>";
                        echo "<td>{$log['date']}</td>";
                        echo "<td><a class='viewLog'>Ver log</a></td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";

                // Paginador
                $total_pages = ceil($total_logs / $per_page);
                if ($total_pages > 1) {
                    echo "<div id='logPagination'>";
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
        $(document).ready(function() {
            // Resto de tu JavaScript
            $("#backButton").click(function() {
                console.log("Esto se ha ejecutado mientras la función se esta ejecutando");
                <?php if (isset($_GET['id'])) { ?>
                    window.location.href = "/admin/logs.php";
                <?php } else { ?>
                    window.location.href = "/admin";
                <?php } ?>
            });

            $("#exitButton").click(function() {
                $.post("/clear-cookies.php", function() {
                    // Redirect after cookies are cleared
                    window.location.href = "/";
                });
            });

            $("#logsButton").click(function() {
                window.location.href = "/admin/logs.php";
            });

            $("#usersButton").click(function() {
                window.location.href = "/admin/users.php";
            });

            var currentPage = <?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?>;
            var totalPages = <?php echo isset($total_pages) ? $total_pages : 1; ?>; // Total de paginas

            $("#logPagination").on("click", "a", function() {
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
                    window.location.href = "/admin/logs.php";
                } else {
                    window.location.href = "/admin/logs.php?page=" + page;
                }
            });

            $("#logPagination a").each(function() {
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

            $(".viewLog").click(function() {
                var logId = $(this).closest("tr").find("td").eq(0).text();
                window.location.href = "/admin/logs.php?id=" + logId;
            });
        })
    </script>
</body>

</html>