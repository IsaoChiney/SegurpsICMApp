<?php
$mysqli = new mysqli("localhost", "root", "", "login_system");

if ($mysqli === false) {
    die("ERROR: No se pudo conectar. " . $mysqli->connect_error);
}
?>
