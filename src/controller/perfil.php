<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../views/index.php");
    exit();
}

$id = $_SESSION['id'];

$stmt = $connection->prepare("
    SELECT nome, sobrenome, email, data_nascimento, data_cadastro 
    FROM usuarios 
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    session_destroy();
    header("Location: ../views/index.php");
    exit();
}
$stmt->close();

$stmt_consultas = $connection->prepare("
    SELECT COUNT(*) AS total_consultas 
    FROM consultas 
    WHERE id_usuario = ?
");
$stmt_consultas->bind_param("i", $id);
$stmt_consultas->execute();
$result_consultas = $stmt_consultas->get_result();
$consultas = $result_consultas->fetch_assoc();
$total_consultas = $consultas['total_consultas'] ?? 0;

$stmt_consultas->close();
$connection->close();


?>
