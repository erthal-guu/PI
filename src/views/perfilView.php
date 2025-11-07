<?php
include("../controller/perfil.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Self Med</title>
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
                <a href="" class="nav-link active" style="color: white;">
                    <i class="fas fa-user"></i>
                    <span>Perfil</span>
                </a>
            </nav>
            <div class="auth-section">
                <div class="user-profile" id="user-profile">
                    <div class="profile-dropdown">
                        <div class="profile-menu" id="profile-menu">
                            <a href="perfil.php" class="profile-menu-item active">
                                <i class="fas fa-user-circle"></i>Meu Perfil
                            </a>
                            <a href="historico.php" class="profile-menu-item">
                                <i class="fas fa-history"></i>Histórico
                            </a>
                            <div class="logout-section" style="margin-top: 30px; text-align: center;">
                                <a href="../controller/logout.php" class="btn-logout">
                                    <i class="fas fa-sign-out-alt"></i> Sair da Conta
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="profile-page">
        <div class="container">
            <div class="page-header">
                <h1><i class="fas fa-user-circle"></i> Meu Perfil</h1>
                <p>Gerencie suas informações pessoais e preferências</p>
            </div>

            <div class="profile-container">
                <!-- Sidebar -->
                <div class="profile-sidebar">
                    <div class="profile-card">
                        <div class="profile-avatar-large">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 id="sidebar-name">
                            <?php echo htmlspecialchars($user['nome']) . ' ' . htmlspecialchars($user['sobrenome']); ?>
                        </h3>
                        <p id="sidebar-email">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </p>
                        <div class="profile-stats">
                            <div class="stat">
                                <span class="stat-label">Consultas</span>
                                <span class="stat-number" id="total-consultations">
                                    <?php echo htmlspecialchars($total_consultas); ?>
                                </span>
                            </div>  
                            <div class="stat">
                                <span class="stat-label">Membro desde</span>
                                <span class="stat-number" id="member-since">
                                    <?php echo date('d/m/Y', strtotime($user['data_cadastro'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-nav">
                        <a href="#personal-info" class="profile-nav-item active" data-tab="personal-info">
                            <i class="fas fa-user"></i>Informações Pessoais
                        </a>
                        <a href="historico.php" class="profile-nav-item">
                            <i class="fas fa-history"></i>Histórico
                        </a>
                    </div>
                </div>

                <!-- Conteúdo Principal -->
                <div class="profile-content">
                    <!-- Tab: Informações Pessoais -->
                    <div class="tab-content active" id="personal-info">
                        <div class="content-header">
                            <h2>Informações Pessoais</h2>
                            <button class="btn-edit" type="button">
                                <i class="fas fa-edit"></i>Editar
                            </button>
                        </div>
                        
                        <form class="profile-form" id="personal-info-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">Nome</label>
                                    <input 
                                        type="text" 
                                        id="firstName" 
                                        name="firstName" 
                                        value="<?php echo htmlspecialchars($user['nome']); ?>" 
                                        disabled
                                        required
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Sobrenome</label>
                                    <input 
                                        type="text" 
                                        id="lastName" 
                                        name="lastName" 
                                        value="<?php echo htmlspecialchars($user['sobrenome']); ?>" 
                                        disabled
                                        required
                                    >
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="<?php echo htmlspecialchars($user['email']); ?>" 
                                    disabled
                                    required
                                >
                            </div>
                            
                            <div class="form-group">
                                <label for="birthDate">Data de Nascimento</label>
                                <input 
                                    type="date" 
                                    id="birthDate" 
                                    name="dataNascimento" 
                                    value="<?php echo htmlspecialchars($user['data_nascimento']); ?>" 
                                    disabled
                                >
                            </div>
                            
                            <div class="form-actions" style="display: none;">
                                <button type="button" class="btn-cancel" onclick="cancelEdit('personal-info')">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i>Salvar Alterações
                                </button>
                            </div>
                        </form>
                        
                        <!-- Botão de Logout -->
                        <div class="logout-section" style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                            <a href="../controller/logout.php" class="btn-logout" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background-color: #dc2626; color: white; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s;">
                                <i class="fas fa-sign-out-alt"></i> Sair da Conta
                            </a>
                        </div>
                    </div>

                    <!-- Tab: Segurança (oculta por padrão) -->
                    <div class="tab-content" id="security">
                        <div class="content-header">
                            <h2>Segurança</h2>
                        </div>
                        <div class="security-section">
                            <h3>Alterar Senha</h3>
                            <form class="profile-form" id="password-form">
                                <div class="form-group">
                                    <label for="currentPassword">Senha Atual</label>
                                    <div class="password-input">
                                        <input 
                                            type="password" 
                                            id="currentPassword" 
                                            name="currentPassword" 
                                            required
                                            placeholder="Digite sua senha atual"
                                        >
                                        <button 
                                            type="button" 
                                            class="password-toggle" 
                                            onclick="togglePassword('currentPassword')"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="newPassword">Nova Senha</label>
                                    <div class="password-input">
                                        <input 
                                            type="password" 
                                            id="newPassword" 
                                            name="newPassword" 
                                            required 
                                            minlength="6"
                                            placeholder="Mínimo 6 caracteres"
                                        >
                                        <button 
                                            type="button" 
                                            class="password-toggle" 
                                            onclick="togglePassword('newPassword')"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirmNewPassword">Confirmar Nova Senha</label>
                                    <div class="password-input">
                                        <input 
                                            type="password" 
                                            id="confirmNewPassword" 
                                            name="confirmNewPassword" 
                                            required
                                            placeholder="Digite novamente"
                                        >
                                        <button 
                                            type="button" 
                                            class="password-toggle" 
                                            onclick="togglePassword('confirmNewPassword')"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-key"></i>Alterar Senha
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Tab: Preferências (oculta por padrão) -->
                    <div class="tab-content" id="preferences">
                        <div class="content-header">
                            <h2>Preferências</h2>
                        </div>
                        <form class="profile-form" id="preferences-form">
                            <div class="preference-section">
                                <h3>Notificações</h3>
                                <div class="preference-item">
                                    <label class="switch-label">
                                        <input type="checkbox" name="emailNotifications" checked>
                                        <span class="switch"></span>
                                        Receber notificações por e-mail
                                    </label>
                                </div>
                                <div class="preference-item">
                                    <label class="switch-label">
                                        <input type="checkbox" name="consultationReminders" checked>
                                        <span class="switch"></span>
                                        Lembretes de consultas
                                    </label>
                                </div>
                            </div>
                            
                            <div class="preference-section">
                                <h3>Privacidade</h3>
                                <div class="preference-item">
                                    <label class="switch-label">
                                        <input type="checkbox" name="dataSharing">
                                        <span class="switch"></span>
                                        Compartilhar dados para pesquisa (anônimo)
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i>Salvar Preferências
                            </button>
                        </form>
                    </div>
                </div>
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
                        <li><a href="perfil.php">Perfil</a></li> 
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
    <script src="js/profile.js"></script>
</body>
</html>