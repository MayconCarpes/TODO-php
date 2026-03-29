<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_POST['id'];
$usuario_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("UPDATE tarefa SET status_id = 3 WHERE id = :id AND usuario_id = :usuario_id");
$stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);

header("Location: dashboard.php");
exit;
?>
