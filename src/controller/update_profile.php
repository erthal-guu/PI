<?php
session_start();
include("conexao.php");

// Define o tipo de resposta como JSON
header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado'
    ]);
    exit();
}

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
    exit();
}

$id = $_SESSION['id'];
$nome = trim($_POST['firstName'] ?? '');
$sobrenome = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$dataNascimento = trim($_POST['dataNascimento'] ?? '');

// Validações
if (empty($nome) || empty($sobrenome) || empty($email)) {
    echo json_encode([
        'success' => false,
        'message' => 'Todos os campos obrigatórios devem ser preenchidos'
    ]);
    exit();
}

// Valida formato de e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'E-mail inválido'
    ]);
    exit();
}

// Verifica se o e-mail já está sendo usado por outro usuário
$stmt_check = $connection->prepare("
    SELECT id FROM usuarios 
    WHERE email = ? AND id != ?
");
$stmt_check->bind_param("si", $email, $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Este e-mail já está sendo usado por outro usuário'
    ]);
    $stmt_check->close();
    $connection->close();
    exit();
}
$stmt_check->close();

// Atualiza os dados do usuário
$stmt = $connection->prepare("
    UPDATE usuarios 
    SET nome = ?, sobrenome = ?, email = ?, data_nascimento = ?
    WHERE id = ?
");

$stmt->bind_param("ssssi", $nome, $sobrenome, $email, $dataNascimento, $id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Perfil atualizado com sucesso'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar perfil: ' . $connection->error
    ]);
}

$stmt->close();
$connection->close();
?>