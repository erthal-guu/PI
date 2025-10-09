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
    <title>Self Med - Saúde na palma da sua mão</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
                <a href="index.php" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Início</span>
                </a>
                <a href="consulta.php" class="nav-link">
                    <i class="fas fa-stethoscope"></i>
                    <span>Consulta</span>
                </a>
                <a href="#about" class="nav-link">
                    <i class="fas fa-info-circle"></i>
                    <span>Sobre</span>
                </a>
            </nav>

            <div class="auth-section">
                <?php if (!$isLoggedIn): ?>
                <div class="auth-buttons" id="auth-buttons">
                    <button class="btn-login" onclick="abrirModalLogin()">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Entrar</span>
                    </button>
                    <button class="btn-register" onclick="abrirModalCadastro()">
                        <i class="fas fa-user-plus"></i>
                        <span>Cadastrar</span>
                    </button>
                </div>
                <?php else: ?>
                <div class="user-profile" id="user-profile">
                    <div class="profile-dropdown">
                        <button class="profile-btn" onclick="toggleProfileMenu()">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name" id="profile-name"><?php echo htmlspecialchars($userName); ?></span>
                            <a href="perfilView.php"><i class="fas fa-chevron-down" class="fas fa-chevron-down"></i></a>
                        </button>

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
                <?php endif; ?>
            </div>

            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>
    
    <div class="modal" id="loginModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-sign-in-alt"></i> Entrar na sua conta</h2>
                <button class="modal-close" onclick="fecharModalLogin()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form class="auth-form" id="loginForm" action="..//controller/login.php" method="post">
                <div class="form-group">
                    <label for="loginEmail">E-mail</label>
                    <input type="email" id="loginEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Senha</label>
                    <div class="password-input">
                        <input type="password" id="loginPassword" name="senha" required>
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Lembrar de mim
                    </label>
                    <a href="#" class="forgot-password">Esqueci minha senha</a>
                </div>
                <button type="submit" class="btn-auth">
                    <span class="btn-text">Entrar</span>
                    <div class="loading-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
            <div class="auth-footer">
                <p>Não tem uma conta? <a href="#" onclick="fecharModalLogin(); abrirModalCadastro();">Cadastre-se</a></p>
            </div>
        </div>
    </div>
    
    <div class="modal" id="registerModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus"></i> Criar nova conta</h2>
                <button class="modal-close" onclick="fecharModalCadastro()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form class="auth-form" id="registerForm" action="../../controller/cadastro.php" method="post"
                onsubmit="return confirmarSenha()">
                <div class="form-row">
                    <div class="form-group">
                        <label for="registerFirstName">Nome</label>
                        <input type="text" id="registerFirstName" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="registerLastName">Sobrenome</label>
                        <input type="text" id="registerLastName" name="sobrenome" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="registerEmail">E-mail</label>
                    <input type="email" id="registerEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="registerDate">Data de Nascimento</label>
                    <input type="date" id="registerDate" name="date" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Senha</label>
                    <div class="password-input">
                        <input type="password" id="registerPassword" name="senha" required minlength="6">
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmar Senha</label>
                    <div class="password-input">
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        Aceito os <a href="#" target="_blank">Termos de Uso</a> e <a href="#" target="_blank">Política
                            de Privacidade</a>
                    </label>
                </div>
                <button type="submit" class="btn-auth">
                    <span class="btn-text">Criar Conta</span>
                    <div class="loading-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
            <div class="auth-footer">
                <p>Já tem uma conta? <a href="#" onclick="fecharModalCadastro(); abrirModalLogin();">Faça login</a></p>
            </div>
        </div>
    </div>

    <section class="hero-banner">
        <div class="hero-background">
            <div class="hero-overlay"></div>
            <img src="https://star.med.br/wp-content/uploads/2023/03/como-funciona-a-inteligencia-artificial.jpg"
                alt="Medicina e IA" class="hero-image">
        </div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Saúde Inteligente na
                        <span class="gradient-text">Palma da Sua Mão</span>
                    </h1>
                    <p class="hero-subtitle">
                        Revolucione o cuidado com sua saúde através da inteligência artificial.
                        Obtenha orientações iniciais precisas e confiáveis sobre seus sintomas.
                    </p>
                    <div class="hero-buttons">
                        <a href="consulta.php" class="btn-primary">
                            <i class="fas fa-robot"></i>
                            Iniciar Consulta IA
                        </a>
                        <a href="#how-it-works" class="btn-secondary">
                            <i class="fas fa-play-circle"></i>
                            Como Funciona
                        </a>
                    </div>
                </div>

                <div class="scroll-indicator">
                    <div class="scroll-arrow">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2>Por que escolher o Self Med?</h2>
                <p>Tecnologia de ponta para cuidar da sua saúde</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Inteligência Artificial</h3>
                    <p>Algoritmos avançados analisam seus sintomas com precisão e oferecem orientações personalizadas.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Disponível 24/7</h3>
                    <p>Acesse orientações de saúde a qualquer hora, em qualquer lugar, sem filas ou esperas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Seguro e Confiável</h3>
                    <p>Suas informações são protegidas e nossas orientações seguem diretrizes médicas rigorosas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Fácil de Usar</h3>
                    <p>Interface intuitiva e responsiva, funciona perfeitamente em qualquer dispositivo.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>Como Funciona</h2>
                <p>Simples, rápido e eficiente</p>
            </div>
            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Descreva seus Sintomas</h3>
                        <p>Conte-nos como você está se sentindo de forma natural e detalhada.</p>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>IA Analisa</h3>
                        <p>Nossa inteligência artificial processa suas informações em segundos.</p>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Receba Orientações</h3>
                        <p>Obtenha orientações iniciais e recomendações personalizadas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Sobre o Self Med</h2>
                    <p class="about-intro">
                        Somos pioneiros em democratizar o acesso à informação de saúde através da tecnologia,
                        oferecendo uma plataforma inteligente que combina inteligência artificial com
                        responsabilidade médica.
                    </p>

                    <div class="about-grid">
                        <div class="about-item">
                            <div class="about-icon">
                                <i class="fas fa-target"></i>
                            </div>
                            <div class="about-info">
                                <h4>Nossa Missão</h4>
                                <p>Democratizar o acesso à informação de saúde através da tecnologia, promovendo o
                                    autocuidado responsável.</p>
                            </div>
                        </div>

                        <div class="about-item">
                            <div class="about-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="about-info">
                                <h4>Nossa Visão</h4>
                                <p>Ser referência em saúde digital, conectando pessoas à informação de qualidade.</p>
                            </div>
                        </div>

                        <div class="about-item">
                            <div class="about-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="about-info">
                                <h4>Nossos Valores</h4>
                                <p>Responsabilidade, transparência, acessibilidade e compromisso com a educação em
                                    saúde.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="https://tse4.mm.bing.net/th/id/OIP.wn_4G_RN636BOIBhIsyHxwHaE8?rs=1&pid=ImgDetMain&o=7&rm=3"
                        alt="Equipe médica e tecnologia" class="about-img">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para cuidar melhor da sua saúde?</h2>
                <p>Comece agora mesmo sua jornada para um cuidado de saúde mais inteligente e acessível.</p>
                <a href="consulta.php" class="btn-cta">
                    <i class="fas fa-rocket"></i>
                    Começar Agora
                </a>
            </div>
        </div>
    </section>

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
                        <li><a href="#about">Sobre Nós</a></li>
                        <li><a href="#how-it-works">Como Funciona</a></li>
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
                        <p><strong>⚠️ Importante:</strong> O Self Med é um assistente digital informativo. Não substitui
                            consulta médica profissional.</p>
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
</body>

</html>