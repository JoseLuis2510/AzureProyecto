<?php
include('ConexionBD.php');
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 2) {
    header("Location: InicioSesion.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No se especificó el ID de la alerta.");
}

$id_alerta = $_GET['id'];

// Marcar la alerta como leída
$stmt = $conn->prepare("UPDATE alertas SET leida = TRUE WHERE id_alerta = ?");
$stmt->bind_param("i", $id_alerta);
$stmt->execute();
$stmt->close();

header("Location: Alertas.php");
exit();
?>