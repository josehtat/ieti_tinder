<?php

$email_user = $_COOKIE["loggedUser"];
setcookie("loggedUser", "", time() - 3600);

$resp["status"]    = 0;
$resp["msg"]   = 'Usuario deslogueado: ' . $email_user;
echo json_encode($resp);
