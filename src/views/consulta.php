<?php
session_start();
$isLoggedIn = isset($_SESSION['id']);
$userName = '';

if ($isLoggedIn) {
    include("../controller/conexao.php");
    $userId = $_SESSION['id'];

    $stmt = $connection->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userName = $user['nome'];
    }
    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta IA - Self Med</title>
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
                <a href="consulta.php" class="nav-link active">
                    <i class="fas fa-stethoscope"></i>
                    <span>Consulta</span>
                </a>
                <a href="index.php#about" class="nav-link">
                    <i class="fas fa-info-circle"></i>
                    <span>Sobre</span>
                </a>
            </nav>

            <div class="auth-section">
                <?php if (!$isLoggedIn): ?>
                <div class="auth-buttons" id="auth-buttons">
                    <button class="btn-login" onclick="window.location.href='index.php'">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Entrar</span>
                    </button>
                    <button class="btn-register" onclick="window.location.href='index.php'">
                        <i class="fas fa-user-plus"></i>
                        <span>Cadastrar</span>
                    </button>
                </div>
                <?php else: ?>
                <div class="user-profile" id="user-profile">
                    <div class="profile-dropdown">
                        <a href="perfilView.php" id="Perfil-icone"><button class="profile-btn" onclick="toggleProfileMenu()">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name" id="profile-name"><?php echo htmlspecialchars($userName); ?></span>
                            <i class="fas fa-chevron-down" id="Perfil-icone"></i>
                        </button></a>

                        <div class="profile-menu" id="profile-menu">
                            <a href="perfil.php" class="profile-menu-item">
                                <i class="fas fa-user-circle"></i>
                                Meu Perfil
                            </a>
                            <a href="historico.php" class="profile-menu-item">
                                <i class="fas fa-history"></i>
                                Histórico
                            </a>
                            <div class="profile-menu-divider"></div>
                            <button class="profile-menu-item logout-btn" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i>
                                Sair
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <?php if (!$isLoggedIn): ?>
    <!-- Mensagem para usuários não logados -->
    <main class="consultation-page">
        <div class="container">
            <div class="login-required-message">
                <div class="message-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Acesso Restrito</h2>
                <p>Você precisa estar logado para usar a consulta com IA.</p>
                <button class="btn-primary" onclick="window.location.href='index.php'">
                    <i class="fas fa-sign-in-alt"></i>
                    Fazer Login
                </button>
            </div>
        </div>
    </main>
    <?php else: ?>
    <!-- Página de Consulta -->
    <main class="consultation-page">
        <div class="container">
            <div class="page-header">
                <h1>Consulta com IA</h1>
                <p>Descreva seus sintomas e receba orientações iniciais personalizadas</p>
            </div>

            <!-- Important Warning -->
            <div class="warning-banner">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="warning-content">
                    <h3>⚠️ Aviso Médico Importante</h3>
                    <p>Esta ferramenta oferece <strong>orientações iniciais</strong> baseadas em inteligência artificial. <strong>NÃO substitui</strong> consulta médica profissional, diagnóstico ou tratamento. Em emergências, procure atendimento médico imediatamente.</p>
                </div>
            </div>

            <div class="consultation-container">
                <div class="consultation-card">
                    <div class="card-header">
                        <h2><i class="fas fa-robot"></i> Assistente de Saúde IA</h2>
                        <p>Conte-nos como você está se sentindo</p>
                    </div>

                    <form id="consultation-form" class="consultation-form">
                        <div class="form-group">
                            <label for="symptoms">Descreva seus sintomas:</label>
                            <textarea 
                                id="symptoms" 
                                name="sintomas" 
                                rows="6" 
                                maxlength="1000"
                                placeholder="Exemplo: Estou com dor de cabeça intensa, febre e cansaço..."
                                required></textarea>
                            <div class="char-counter">
                                <span id="char-count">0</span>/1000 caracteres
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="duration">Há quanto tempo você tem esses sintomas?</label>
                            <select id="duration" name="duracao-sintomas" required>
                                <option value="">Selecione...</option>
                                <option value="menos-24h">Menos de 24 horas</option>
                                <option value="1-3-dias">1 a 3 dias</option>
                                <option value="4-7-dias">4 a 7 dias</option>
                                <option value="1-2-semanas">1 a 2 semanas</option>
                                <option value="mais-2-semanas">Mais de 2 semanas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="intensity">Intensidade dos sintomas:</label>
                            <div class="intensity-scale">
                                <input type="range" id="intensity" name="intensidade" min="1" max="10" value="5">
                                <div class="scale-labels">
                                    <span>Leve (1)</span>
                                    <span id="intensity-value">5</span>
                                    <span>Severo (10)</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="submit-btn">
                            <span class="btn-text">
                                <i class="fas fa-paper-plane"></i>
                                Enviar para Análise
                            </span>
                            <div class="loading-spinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                                Processando com IA...
                            </div>
                        </button>
                    </form>

                    <!-- Seção de Resultados -->
                    <div id="results-section" class="results-section" style="display: none;">
                        <div class="results-header">
                            <h3><i class="fas fa-clipboard-list"></i> Análise Recebida</h3>
                        </div>
                        <div id="analysis-results" class="analysis-results">
                            <!-- Conteúdo será inserido dinamicamente -->
                        </div>
                        <div class="results-actions">
                            <button type="button" class="btn-secondary" onclick="startNewConsultation()">
                                <i class="fas fa-redo"></i>
                                Nova Consulta
                            </button>
                            <button type="button" class="btn-primary" onclick="window.location.href='historico.php'">
                                <i class="fas fa-history"></i>
                                Ver Histórico
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar com Informações -->
                <div class="side-info">
                    <div class="info-card">
                        <h3><i class="fas fa-lightbulb"></i> Dicas para uma melhor análise</h3>
                        <ul>
                            <li>Seja específico sobre seus sintomas</li>
                            <li>Mencione quando começaram</li>
                            <li>Descreva a intensidade</li>
                            <li>Inclua fatores que melhoram ou pioram</li>
                            <li>Mencione medicamentos que está tomando</li>
                        </ul>
                    </div>

                    <div class="info-card emergency">
                        <h3><i class="fas fa-ambulance"></i> Procure ajuda imediata se:</h3>
                        <ul>
                            <li>Dificuldade para respirar</li>
                            <li>Dor no peito intensa</li>
                            <li>Perda de consciência</li>
                            <li>Sangramento intenso</li>
                            <li>Febre muito alta (>39°C)</li>
                            <li>Vômitos persistentes</li>
                        </ul>
                        <div class="emergency-number">
                            <strong>Emergência: 192 (SAMU)</strong>
                        </div>
                    </div>

                    <div class="info-card ai-info">
                        <h3><i class="fas fa-robot"></i> Sobre a IA</h3>
                        <p>Utilizamos o modelo <strong>Llama 3.3 70B</strong> da Groq, treinado para fornecer orientações médicas preliminares baseadas em grandes volumes de dados médicos.</p>
                        <p class="small-text">Lembre-se: a IA é uma ferramenta de apoio, não um substituto para profissionais de saúde.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php endif; ?>

    <!-- Footer -->
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
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Navegação</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Início</a></li>
                        <li><a href="consulta.php">Consulta IA</a></li>
                        <li><a href="index.php#about">Sobre Nós</a></li>
                        <li><a href="index.php#how-it-works">Como Funciona</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Suporte</h4>
                    <ul class="footer-links">
                        <li><a href="#">Central de Ajuda</a></li>
                        <li><a href="#">Termos de Uso</a></li>
                        <li><a href="#">Política de Privacidade</a></li>
                        <li><a href="#">Contato</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Aviso Médico</h4>
                    <div class="medical-disclaimer">
                        <p><strong>⚠️ Importante:</strong> O Self Med é um assistente digital informativo. Não substitui consulta médica profissional.</p>
                        <p>Em emergências, procure atendimento médico imediatamente.</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; 2025 Self Med. Todos os direitos reservados.</p>
                    <p>Desenvolvido com <i class="fas fa-heart"></i> para cuidar da sua saúde</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script src="js/consulta.js"></script>
</body>
</html>