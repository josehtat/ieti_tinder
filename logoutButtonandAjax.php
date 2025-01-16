<!-- 
 Lo que hay a continuación es la manera de hacer que el logout funcioné
 El botón hará que se llame a la función logout() cuando se haga clic
 Y la función logout() hará la petición AJAX al logout.php para cerrar la sesión
-->


<div class="submit-btn">
    <button id="logout">Cerrar sesión, <?php echo $_COOKIE['loggedUser']; ?></p></button>
</div>

<script>
    function logout() {
        var parameters = {};

        $.ajax({
            data: parameters,
            url: 'logout.php',
            type: 'POST',
            success: logoutResult,
            dataType: 'json'
        });
    }

    function logoutResult(logRes) {
        console.log(logRes);
        if (logRes.status == 0) {
            window.location.href = "/";
        }
    }

    $(document).ready(function() {
        $("#logout").click(function(event) {
            logout();
        });
    });
</script>