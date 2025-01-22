<?php

$email_user = $_COOKIE["loggedUser"];
setcookie("loggedUser", "", time() - 3600);

$resp["status"] = 0;
$resp["data"] = 'Usuario ' . $email_user . ' ha cerrado sesion correctamente.';
echo json_encode($resp);
