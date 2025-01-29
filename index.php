<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Affinity</title>
</head>

<body>
    <script>
        <?php if (isset($_COOKIE['loggedUser'])) {
            if (isset($_COOKIE['userRole']) && $_COOKIE['userRole'] == 'admin') { ?>
                window.location.href = "/admin/index.php";
            <?php } else { ?>
                window.location.href = "/discober.php";
            <?php }
        } else { ?>
            window.location.href = "/login.php";
        <?php } ?>
    </script>
</body>

</html>