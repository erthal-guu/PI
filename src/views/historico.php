<?php
session_start();
$isLoggedIn = isset($_SESSION['id']);
$userName = '';
$consultas = [];
$stats = [
    'total' => 0,
    'este_mes' => 0,
    'ultima_consulta' => 'Nunca'
];

if (!$isLoggedIn) {
    header("Location: index.php");
    exit;
}

include("../controller/conexao.php");
$userId = $_SESSION['id'];

// Pega o nome do usuário
$stmt = $connection->prepare("SELECT nome FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userName = $user['nome'];
}
$stmt->close();

// Pega filtros da URL
$filtro = $_GET['filtro'] ?? 'all';
$busca = $_GET['busca'] ?? '';

// Monta a query de consultas
$query = "SELECT 
    c.id,
    c.sintomas,
    c.duracao_sintomas,
    c.intensidade,
    c.resposta_ia,
    c.data_consulta
FROM consultas c
WHERE c.id_usuario = ?";

$params = [$userId];
$types = "i";

// Adiciona filtro de período
switch ($filtro) {
    case 'week':
        $query .= " AND c.data_consulta >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        break;
    case 'month':
        $query .= " AND c.data_consulta >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        break;
    case '3months':
        $query .= " AND c.data_consulta >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
        break;
}

// Adiciona filtro de busca
if (!empty($busca)) {
    $query .= " AND (c.sintomas LIKE ? OR c.resposta_ia LIKE ?)";
    $buscaParam = "%{$busca}%";
    $params[] = $buscaParam;
    $params[] = $buscaParam;
    $types .= "ss";
}

$query .= " ORDER BY c.data_consulta DESC";

$stmt = $connection->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Mapeia duração para texto
$duracaoMap = [
    'menos-24h' => 'Menos de 24h',
    '1-3-dias' => '1-3 dias',
    '4-7-dias' => '4-7 dias',
    '1-2-semanas' => '1-2 semanas',
    'mais-2-semanas' => 'Mais de 2 semanas'
];

while ($row = $result->fetch_assoc()) {
    $data = new DateTime($row['data_consulta']);
    
    $sintomasResumo = substr($row['sintomas'], 0, 100);
    if (strlen($row['sintomas']) > 100) {
        $sintomasResumo .= '...';
    }
    
    $consultas[] = [
        'id' => $row['id'],
        'sintomas' => $row['sintomas'],
        'sintomas_resumo' => $sintomasResumo,
        'duracao' => $row['duracao_sintomas'],
        'duracao_texto' => $duracaoMap[$row['duracao_sintomas']] ?? $row['duracao_sintomas'],
        'intensidade' => $row['intensidade'],
        'resposta_ia' => $row['resposta_ia'],
        'data_formatada' => $data->format('d/m/Y'),
        'hora_formatada' => $data->format('H:i'),
        'data_relativa' => calcularDataRelativa($row['data_consulta'])
    ];
}
$stmt->close();

// Calcula estatísticas
$statsQuery = "SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN data_consulta >= DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 1 END) as este_mes,
    MAX(data_consulta) as ultima_consulta
FROM consultas 
WHERE id_usuario = ?";

$stmtStats = $connection->prepare($statsQuery);
$stmtStats->bind_param("i", $userId);
$stmtStats->execute();
$stmtStats->bind_result($stats['total'], $stats['este_mes'], $ultimaConsultaData);
$stmtStats->fetch();
$stmtStats->close();

if ($ultimaConsultaData) {
    $stats['ultima_consulta'] = calcularDataRelativa($ultimaConsultaData);
}

$connection->close();

// Função auxiliar para calcular data relativa
function calcularDataRelativa($dataConsulta) {
    $agora = new DateTime();
    $data = new DateTime($dataConsulta);
    $diferenca = $agora->diff($data);
    
    if ($diferenca->y > 0) {
        return $diferenca->y . ' ano' . ($diferenca->y > 1 ? 's' : '');
    } elseif ($diferenca->m > 0) {
        return $diferenca->m . ' ' . ($diferenca->m > 1 ? 'meses' : 'mês');
    } elseif ($diferenca->d > 0) {
        if ($diferenca->d == 1) return 'Ontem';
        return $diferenca->d . ' dias';
    } elseif ($diferenca->h > 0) {
        return $diferenca->h . ' hora' . ($diferenca->h > 1 ? 's' : '');
    } else {
        return 'Hoje';
    }
}

// Função para formatar a resposta da IA
function formatarResposta($texto) {
    $texto = nl2br(htmlspecialchars($texto));
    $texto = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $texto);
    $texto = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $texto);
    return $texto;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Consultas - Self Med</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">    
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="nav-brand">
                <div class="logo">
                    <i class="fas fa-heartbeat"></i>
                    <div class="brand-text">
                        <h1>Self Med</h1>
                        <span class="tagline">Powered by AI</span>
                    </div>
                </div>
            </div>
            <nav class="nav-menu">
                <a href="index.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Início</span>
                </a>
                <a href="consulta.php" class="nav-link">
                    <i class="fas fa-stethoscope"></i>
                    <span>Consulta</span>
                </a>
                <a href="historico.php" class="nav-link active">
                    <i class="fas fa-history"></i>
                    <span>Histórico</span>
                </a>
            </nav>

                <div class="user-profile" id="user-profile">
                    <div class="profile-dropdown">
                        <a href="perfilView.php" id="Perfil-icone""><button class="profile-btn" onclick="toggleProfileMenu()">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name" id="profile-name"><?php echo htmlspecialchars($userName); ?></span>
                            <i class="fas fa-chevron-down" id="Perfil-icone"></i>
                        </button></a>

                        <div class="profile-menu" id="profile-menu">
                            <a href="perfil.php"class="profile-menu-item">
                                <i class="fas fa-user-circle"></i>
                                Meu Perfil
                            </a>
                            <a href="historico.html" class="profile-menu-item">
                                <i class="fas fa-history"></i>
                                Histórico
                            </a>
                            <div class="profile-menu-divider"></div>
                            <a href="../../controller/logout.php" class="profile-menu-item logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                Sair
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </header>

    <main class="history-page">
        <div class="container">
            <div class="page-header">
                <h1><i class="fas fa-history"></i> Histórico de Consultas</h1>
                <p>Acompanhe suas consultas anteriores e orientações recebidas</p>
            </div>

            <!-- Filtros -->
            <form method="GET" action="historico.php" class="history-filters">
                <div class="filter-group">
                    <label for="filtro">Período:</label>
                    <select name="filtro" id="filtro" onchange="this.form.submit()">
                        <option value="all" <?php echo $filtro === 'all' ? 'selected' : ''; ?>>Todas as consultas</option>
                        <option value="week" <?php echo $filtro === 'week' ? 'selected' : ''; ?>>Última semana</option>
                        <option value="month" <?php echo $filtro === 'month' ? 'selected' : ''; ?>>Último mês</option>
                        <option value="3months" <?php echo $filtro === '3months' ? 'selected' : ''; ?>>Últimos 3 meses</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="busca">Buscar:</label>
                    <input type="text" name="busca" id="busca" value="<?php echo htmlspecialchars($busca); ?>" placeholder="Buscar por sintomas...">
                </div>
                <div class="filter-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-view-details" style="width: 100%;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>

            <!-- Estatísticas -->
            <div class="history-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total']; ?></h3>
                        <p>Total de Consultas</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['este_mes']; ?></h3>
                        <p>Este Mês</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['ultima_consulta']; ?></h3>
                        <p>Última Consulta</p>
                    </div>
                </div>
            </div>

            <div class="consultations-container">
                <div class="consultations-header">
                    <h2>Suas Consultas (<?php echo count($consultas); ?>)</h2>
                    <a href="consulta.php" class="btn-new-consultation">
                        <i class="fas fa-plus"></i>
                        Nova Consulta
                    </a>
                </div>

                <?php if (empty($consultas)): ?>
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Nenhuma consulta encontrada</h3>
                        <p>Você ainda não fez nenhuma consulta ou não há resultados para os filtros aplicados.</p>
                        <a href="consulta.php" class="btn-primary">
                            <i class="fas fa-stethoscope"></i>
                            Fazer Primeira Consulta
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($consultas as $consulta): ?>
                        <?php
                            // Define classe da intensidade
                            $intensidadeClass = 'low';
                            $intensidadeTexto = 'Leve';
                            
                            if ($consulta['intensidade'] >= 7) {
                                $intensidadeClass = 'high';
                                $intensidadeTexto = 'Severa';
                            } elseif ($consulta['intensidade'] >= 4) {
                                $intensidadeClass = 'medium';
                                $intensidadeTexto = 'Moderada';
                            }
                        ?>
                        <div class="consultation-card">
                            <div class="consultation-header">
                                <div class="consultation-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span><?php echo $consulta['data_formatada']; ?> às <?php echo $consulta['hora_formatada']; ?></span>
                                    <span class="date-relative">(<?php echo $consulta['data_relativa']; ?>)</span>
                                </div>
                                <div class="consultation-badges">
                                    <span class="badge intensity-<?php echo $intensidadeClass; ?>">
                                        <i class="fas fa-thermometer-half"></i>
                                        <?php echo $intensidadeTexto; ?> (<?php echo $consulta['intensidade']; ?>/10)
                                    </span>
                                    <span class="badge duration">
                                        <i class="fas fa-clock"></i>
                                        <?php echo $consulta['duracao_texto']; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="consultation-body">
                                <h4><i class="fas fa-notes-medical"></i> Sintomas Relatados:</h4>
                                <p><?php echo htmlspecialchars($consulta['sintomas_resumo']); ?></p>
                            </div>
                            
                            <div class="consultation-footer">
                                <a href="detalhes_consulta.php?id=<?php echo $consulta['id']; ?>" class="btn-view-details">
                                    <i class="fas fa-eye"></i>
                                    Ver Análise Completa
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <div class="logo">
                            <i class="fas fa-heartbeat"></i>
                            <div class="brand-text">
                                <h3>Self Med</h3>
                                <span>Powered by AI</span>
                            </div>
                        </div>
                        <p>Revolucionando o cuidado com a saúde através da inteligência artificial.</p>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Navegação</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Início</a></li>
                        <li><a href="consulta.php">Consulta IA</a></li>
                        <li><a href="historico.php">Histórico</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Aviso Médico</h4>
                    <div class="medical-disclaimer">
                        <p><strong>⚠️ Importante:</strong> O Self Med é um assistente digital informativo. Não substitui consulta médica profissional.</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; 2025 Self Med. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>