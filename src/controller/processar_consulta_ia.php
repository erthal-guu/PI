<?php
// Habilita exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json');

// Log inicial
error_log("=== PROCESSAR CONSULTA IA - INÍCIO ===");
error_log("Sessão ID: " . ($_SESSION['id'] ?? 'NÃO DEFINIDO'));

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    error_log("ERRO: Usuário não autenticado");
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

// Inclui conexão
include("conexao.php");
error_log("Conexão incluída");

// Recebe os dados do POST
$rawData = file_get_contents('php://input');
error_log("Raw data recebido: " . $rawData);

$data = json_decode($rawData, true);
error_log("Dados decodificados: " . print_r($data, true));

$sintomas = $data['sintomas'] ?? '';
$duracao = $data['duracao'] ?? '';
$intensidade = $data['intensidade'] ?? '';
$userId = $_SESSION['id'];

error_log("Valores extraídos:");
error_log("- Sintomas: " . substr($sintomas, 0, 50) . "...");
error_log("- Duração: " . $duracao);
error_log("- Intensidade: " . $intensidade);
error_log("- User ID: " . $userId);

// Valida os dados
if (empty($sintomas) || empty($duracao) || empty($intensidade)) {
    error_log("ERRO: Dados incompletos");
    echo json_encode(['erro' => 'Dados incompletos']);
    exit;
}

$apiKey = 'Chave-api';
$apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

error_log("API Key definida (primeiros 20 chars): " . substr($apiKey, 0, 20) . "...");

$duracaoTexto = [
    'menos-24h' => 'menos de 24 horas',
    '1-3-dias' => '1 a 3 dias',
    '4-7-dias' => '4 a 7 dias',
    '1-2-semanas' => '1 a 2 semanas',
    'mais-2-semanas' => 'mais de 2 semanas'
];

$prompt = "Você é um assistente médico virtual chamado Self Med AI. Analise os seguintes sintomas e forneça orientações iniciais em português do Brasil.

**IMPORTANTE**: 
- Suas respostas são apenas orientações preliminares
- Sempre recomende consultar um médico profissional
- Em casos graves, oriente procurar emergência
- Seja empático e claro
- Use linguagem acessível

**Dados do Paciente:**
- Sintomas: {$sintomas}
- Duração: {$duracaoTexto[$duracao]}
- Intensidade: {$intensidade}/10

**Forneça uma resposta estruturada com:**
1. Resumo dos sintomas relatados
2. Possíveis causas (liste 2-3 mais prováveis)
3. Recomendações iniciais de cuidados
4. Sinais de alerta que exigem atendimento médico urgente
5. Sugestão de especialista médico apropriado

Mantenha um tom acolhedor e profissional.";

error_log("Prompt montado (tamanho: " . strlen($prompt) . " chars)");


$requestData = [
    'model' => 'llama-3.3-70b-versatile',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Você é um assistente médico virtual responsável e ético. Sempre enfatize a importância de consultar profissionais de saúde.'
        ],
        [
            'role' => 'user',
            'content' => $prompt
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 1500,
    'top_p' => 1,
    'stream' => false
];

error_log("Dados da requisição preparados");
error_log("Iniciando chamada à API Groq...");

// Faz a requisição para a API da IA
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

error_log("Resposta da API - HTTP Code: " . $httpCode);
error_log("cURL Error: " . ($curlError ?: 'nenhum'));
error_log("Resposta (primeiros 200 chars): " . substr($response, 0, 200));

// Verifica se houve erro na requisição
if ($httpCode !== 200) {
    $errorData = json_decode($response, true);
    error_log("ERRO na API: " . print_r($errorData, true));
    echo json_encode([
        'erro' => 'Erro ao processar com IA',
        'detalhes' => $errorData['error']['message'] ?? 'Erro desconhecido',
        'http_code' => $httpCode
    ]);
    exit;
}

$iaResponse = json_decode($response, true);
$analiseIA = $iaResponse['choices'][0]['message']['content'] ?? '';

error_log("Análise IA recebida (tamanho: " . strlen($analiseIA) . " chars)");

if (empty($analiseIA)) {
    error_log("ERRO: Resposta vazia da IA");
    echo json_encode(['erro' => 'Resposta vazia da IA']);
    exit;
}

// Salva a consulta no banco de dados
error_log("Tentando salvar no banco de dados...");

$stmt = $connection->prepare("INSERT INTO consultas (id_usuario, sintomas, duracao_sintomas, intensidade, resposta_ia, data_consulta) VALUES (?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    error_log("ERRO ao preparar statement: " . $connection->error);
    echo json_encode([
        'erro' => 'Erro ao preparar consulta SQL',
        'detalhes' => $connection->error
    ]);
    exit;
}

$stmt->bind_param("issis", $userId, $sintomas, $duracao, $intensidade, $analiseIA);

if ($stmt->execute()) {
    $consultaId = $connection->insert_id;
    error_log("✅ Consulta salva com sucesso! ID: " . $consultaId);
    
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Consulta registrada com sucesso',
        'analise' => $analiseIA,
        'consulta_id' => $consultaId
    ]);
} else {
    error_log("ERRO ao executar insert: " . $stmt->error);
    echo json_encode([
        'erro' => 'Erro ao salvar no banco de dados',
        'detalhes' => $stmt->error
    ]);
}

$stmt->close();
$connection->close();

error_log("=== PROCESSAR CONSULTA IA - FIM ===");
?>