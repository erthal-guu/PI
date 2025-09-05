<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $nome      = $connection->real_escape_string($_POST['nome']);
    $sobrenome = $connection->real_escape_string($_POST['sobrenome']);
    $email     = $connection->real_escape_string($_POST['email']);
    $senha     = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt_check = $connection->prepare("SELECT email FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>alert('Email já existente!');</script>";
        $stmt_check->close();
        $connection->close();
        exit();
    } else {
        $stmt_insert = $connection->prepare("INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("ssss", $nome, $sobrenome, $email, $senha);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
        } else {
        }

        $stmt_insert->close();
    }

    $stmt_check->close();
    $connection->close();
}
?>