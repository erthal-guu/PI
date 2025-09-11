<?php
session_start();
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['senha'])) {
    $email = $connection->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $connection->prepare("SELECT senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashSenha);
        $stmt->fetch();

        if (password_verify($senha, $hashSenha)) {
            $_SESSION['email'] = $email;
            header("location:../views/html/perfil.html");
            exit;
        } else {
            header("location:../views/html/index.html");
            echo "<script>alert('Email ou senha inválidos!');</script>";
            exit;
        }
    $stmt->close();
    }
    }
?>