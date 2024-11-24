<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../views/login_form.php");
    exit;
}

require_once "../config/config.php";

$id = $_GET['id'];
$asesor = $_SESSION['id'];

$sql = "DELETE FROM cotizaciones WHERE ID = ? AND asesor = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("is", $id, $asesor);
    if ($stmt->execute()) {
        header("location: ../views/welcome.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$mysqli->close();
?>
