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
    <!-- Header -->
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
            
            <!-- User Profile -->
            <div class="auth-section">
                <div class="user-profile" id="user-profile">
                    <div class="profile-dropdown">
                        <button class="profile-btn" onclick="toggleProfileMenu()">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name" id="profile-name">Usuário</span>
                            <a href="perfilView.php"><i class="fas fa-chevron-down" class="fas fa-chevron-down"></i></a>
                        </button>
                        
                        <div class="profile-menu" id="profile-menu">
                            <a href="perfil.php" class="profile-menu-item">
                                <i class="fas fa-user-circle"></i>
                                Meu Perfil
                            </a>
                            <a href="historico.php" class="profile-menu-item active">
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
            </div>
        </div>
    </header>

    <!-- History Page -->
    <main class="history-page">
        <div class="container">
            <div class="page-header">
                <h1><i class="fas fa-history"></i> Histórico de Consultas</h1>
                <p>Acompanhe suas consultas anteriores e orientações recebidas</p>
            </div>

            <!-- Filters -->
            <div class="history-filters">
                <div class="filter-group">
                    <label for="dateFilter">Período:</label>
                    <select id="dateFilter" onchange="filterConsultations()">
                        <option value="all">Todas as consultas</option>
                        <option value="week">Última semana</option>
                        <option value="month">Último mês</option>
                        <option value="3months">Últimos 3 meses</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="searchFilter">Buscar:</label>
                    <input type="text" id="searchFilter" placeholder="Buscar por sintomas..." onkeyup="searchConsultations()">
                </div>
            </div>

            <!-- Statistics -->
            <div class="history-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="total-consultations-stat">12</h3>
                        <p>Total de Consultas</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="this-month-stat">3</h3>
                        <p>Este Mês</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="last-consultation-stat">2 dias</h3>
                        <p>Última Consulta</p>
                    </div>
                </div>
            </div>

            <div class="consultations-container">
                <div class="consultations-header">
                    <h2>Suas Consultas</h2>
                    <a href="consulta.php" class="btn-new-consultation">
                        <i class="fas fa-plus"></i>
                        Nova Consulta
                    </a>
                </div>

                <div class="consultations-list" id="consultations-list">
                </div>

                <div class="loading-state" id="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Carregando histórico...</p>
                </div>
                <div class="empty-state" id="empty-state" style="display: none;">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Nenhuma consulta encontrada</h3>
                    <p>Você ainda não fez nenhuma consulta ou não há resultados para os filtros aplicados.</p>
                    <a href="consulta.php" class="btn-primary">
                        <i class="fas fa-stethoscope"></i>
                        Fazer Primeira Consulta
                    </a>
                </div>
            </div>
        </div>
    </main>

    <div class="modal" id="consultationModal">
        <div class="modal-content large">
            <div class="modal-header">
                <h2><i class="fas fa-file-medical"></i> Detalhes da Consulta</h2>
                <button class="modal-close" onclick="closeModal('consultationModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="consultation-detail" id="consultation-detail">
            </div>
        </div>
    </div>

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
                        <li><a href="perfilView.php">Perfil</a></li>
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
                    <p>&copy; 2024 Self Med. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="auth.js"></script>
    <script src="history.js"></script>
</body>
</html>
