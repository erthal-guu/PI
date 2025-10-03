<?php
session_start();
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['senha'])) {
    $email = $connection->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $connection->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashSenha);
        $stmt->fetch();
        
        if (password_verify($senha, $hashSenha)) {
            $_SESSION['id'] = $id;
            header("Location: ../views/index.php"); 
            exit;
        } else {
            header("Location: ../views/index.php?error=invalid_credentials");
            exit;
        }
    } else {
        header("Location: ../views/index.php?error=invalid_credentials");
        exit;
    }
    
    $stmt->close();
    $connection->close();
}
?>