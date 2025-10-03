<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../views/html/index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email_novo = $_POST['email'];
    $telefone = $_POST['telefone'];
    $dataNascimento = $_POST['dataNascimento'];


    $email_original = $_SESSION['email'];


    $stmt = $connection->prepare("UPDATE usuarios SET nome = ?, sobrenome = ?, email = ?, telefone = ?, data_nascimento = ? WHERE email = ?");


    $stmt->bind_param("ssssss", $nome, $sobrenome, $email_novo, $telefone, $dataNascimento, $email_original);


    if ($stmt->execute()) {

        $_SESSION['email'] = $email_novo;

        header("Location: ../views/html/perfil.html?update_success=1");
        exit();
    } else {

        header("Location: ../views/html/perfil.html?update_error=1");
        exit();
    }

    $stmt->close();
    $connection->close();
} else {

    header("Location: ../views/html/perfil.html");
    exit();
}
?>