<?php
setcookie("loggedUser", "", time() - 3600, "/"); // Eliminar cookie loggedUser
setcookie("userRole", "", time() - 3600, "/");  // Eliminar cookie userRole
