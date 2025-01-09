<d?php
    session_start();

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const logoutButton = document.getElementById('logout');
                if (logoutButton) {
                    logoutButton.addEventListener('click', () => {
                        <?php unset($_SESSION['loggedUser']); ?>
                        window.location.href = window.location.pathname;
                    });
                }
            })
        </script>
    </head>

    <body>
        <?php


        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $hashedPassword = hash('sha256', $password);

            try {
                $hostname = "localhost";
                $dbname = "usersM7";
                $dbUsername = "admin";
                $pw = "SQL no me gusta!";
                $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
            } catch (PDOException $e) {
                echo "Error al accedir a la base de dades" . $e->getMessage() . "\n";
                exit;
            }


            //preparem i executem la consulta
            $queryText = "SELECT * FROM users " .
                "WHERE username = :username AND password = :password;";

            try {
                //preparem i executem la consulta
                $query = $pdo->prepare($queryText);
                $query->bindParam(':username', $username);
                $query->bindParam(':password', $hashedPassword);
                $query->execute();
            } catch (PDOException $e) {
                echo "Error de SQL<br>\n";
                //comprovo errors:
                $e = $query->errorInfo();
                if ($e[0] != '00000') {
                    echo "\nPDO::errorInfo():\n";
                    die("Error accedint a dades: " . $e[2]);
                }
            }

            if ($query->rowCount() <= 0 || $query->rowCount() >= 2) {
                echo "<p>Wrong username or password</p>";
            } else {
                foreach ($query as $row) {
                    $_SESSION['loggedUser'] = $row['username'];
                }
            }
        }

        if (isset($_SESSION['loggedUser'])) { ?>
            <div class="welcome-container">
                <p>Welcome, user <strong><?php echo $_SESSION['loggedUser'] ?></strong>. You are now logged in.</p>
                <button id="logout">Logout</button>
            </div>
        <?php } else { ?>
            <div class="login-container">
                <form action="login.php" method="post" class="login-form">
                    <h2>Login</h2>
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Username: " required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password:" required>
                    </div>
                    <div class="submit-btn">
                        <button type="submit">Login</button>
                    </div>
                </form>
            </div>
        <?php } ?>
    </body>

    </html>