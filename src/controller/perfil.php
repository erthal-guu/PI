<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../views/html/index.html");
    exit();
}

$id = $_SESSION['id'];

$stmt = $connection->prepare("SELECT nome, sobrenome, email, data_nascimento, data_cadastro FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    session_destroy();
    header("Location: ../views/html/index.html");
    exit();
}

$stmt->close();
$connection->close();
?>