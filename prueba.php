<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <script src="/js/jquery-3.7.1.min.js"></script>
</head>
<body id="bodyProfile">
    <main class="containerProfile">
        <div id="profile-section" class="section active">

        </div>
    </main>

    <script>
        $(document).ready(function () {
            $('#photosLinkProfile').on('click', function () {
                $('#profile-section').removeClass('activeProfile');
                $('#photos-section').addClass('activeProfile');
            });

            $('#backToProfile').on('click', function () {
                $('#photos-section').removeClass('activeProfile');
                $('#profile-section').addClass('activeProfile');
            });
        });
    </script>
</body>
</html>
