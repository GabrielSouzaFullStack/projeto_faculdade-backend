<!-- Layout Base - Header com Sidebar -->
<?php
// Verifica se o usuário está logado
require_once __DIR__ . '/../security/session.php';
iniciar_sessao();
verificar_cookie_sessao();
exigir_login();

// Pega informações do usuário
$usuario_nome = $_SESSION['login'] ?? 'Usuário';
$usuario_inicial = strtoupper(substr($usuario_nome, 0, 1));
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - Admin ONG</title>
    <link rel="stylesheet" href="assets/css/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/dashboard.css?v=<?php echo time(); ?>">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>?v=<?php echo time(); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <div class="dashboard">
        <button
            type="button"
            class="menu-toggle"
            aria-label="Abrir menu"
            aria-controls="adminSidebar"
            aria-expanded="false">
            ☰ Menu
        </button>

        <!-- Sidebar -->
        <aside class="sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <img src="assets/img/logo-ong.png" alt="Logo ONG" class="sidebar-logo">
                <h2 class="sidebar-title">Painel Admin</h2>
                <p class="sidebar-subtitle">ONG Amigos da Rua</p>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <a href="index.php" class="nav-item <?php echo ($page_active ?? '') === 'dashboard' ? 'active' : ''; ?>">
                        📊 Dashboard
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Animais</div>
                    <a href="animais.php" class="nav-item <?php echo ($page_active ?? '') === 'animais' ? 'active' : ''; ?>">
                        🐾 Gerenciar Animais
                    </a>
                    <a href="animais.php?action=new" class="nav-item <?php echo ($page_active ?? '') === 'animais-novo' ? 'active' : ''; ?>">
                        ➕ Cadastrar Animal
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Formulários</div>
                    <a href="formularios-adocao.php" class="nav-item <?php echo ($page_active ?? '') === 'formularios-adocao' ? 'active' : ''; ?>">
                        📋 Formulários de Adoção
                    </a>
                    <a href="formularios-lar-temporario.php" class="nav-item <?php echo ($page_active ?? '') === 'formularios-lar' ? 'active' : ''; ?>">
                        🏠 Lares Temporários
                    </a>
                </div>
            </nav>

            <div class="sidebar-user">
                <div class="user-info">
                    <div class="user-avatar"><?php echo htmlspecialchars($usuario_inicial); ?></div>
                    <div class="user-details">
                        <p class="user-name"><?php echo htmlspecialchars($usuario_nome); ?></p>
                        <p class="user-role">Administrador</p>
                    </div>
                </div>
                <button onclick="window.location.href='logout.php'" class="btn-logout">
                    🚪 Sair
                </button>
            </div>
        </aside>

        <button type="button" class="sidebar-overlay" aria-label="Fechar menu" aria-hidden="true"></button>

        <!-- Main Content -->
        <main class="main-content">