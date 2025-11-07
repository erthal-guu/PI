<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $nome           = $connection->real_escape_string($_POST['nome']);
    $sobrenome      = $connection->real_escape_string($_POST['sobrenome']);
    $email          = $connection->real_escape_string($_POST['email']);
    $dataNascimento = $connection->real_escape_string($_POST['date']);
    $senha          = $_POST['senha'];
    $hashSenha      = password_hash($senha, PASSWORD_DEFAULT);

    $stmt_check = $connection->prepare("SELECT email FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        header("Location: ../views/html/index.php?emailExists=1");
        exit();
    }
    $stmt_check->close();
    $stmt_insert = $connection->prepare("INSERT INTO usuarios (nome, sobrenome, email, data_nascimento, senha) VALUES (?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("sssss", $nome, $sobrenome, $email, $dataNascimento, $hashSenha);

    if ($stmt_insert->execute()) {
        header("Location: ../views/html/index.php?registered=1");
        exit();
    } else {
        echo "Erro ao cadastrar usuário.";
    }
}
?>