<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Painel Administrativo - ONG Amigos da Rua">
    <title>Login - Painel Administrativo</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <!-- Logo -->
        <div class="login-logo">
            <img src="assets/img/logo-ong.png" alt="Logo ONG Amigos da Rua">
        </div>

        <!-- Título -->
        <h1 class="login-title">Painel Administrativo</h1>
        <p class="login-subtitle">Faça login para acessar o sistema</p>

        <!-- Mensagem de erro -->
        <?php if ($temErro): ?>
            <div class="alert alert-warning" role="alert">
                <?php echo htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Login -->
        <form action="" method="POST" class="login-form">
            <div class="form-group">
                <label for="usuario" class="form-label">Usuário</label>
                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    class="form-control"
                    placeholder="Digite seu usuário"
                    required
                    autofocus
                    value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Senha</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Digite sua senha"
                    required>
            </div>

            <div class="remember-me">
                <input
                    type="checkbox"
                    id="lembrar_sessao"
                    name="lembrar_sessao"
                    value="1">
                <label for="lembrar_sessao">Lembrar-me neste dispositivo</label>
            </div>

            <button type="submit" class="btn btn-primary">
                Entrar
            </button>
        </form>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; <?php echo date('Y'); ?> ONG Amigos da Rua. Todos os direitos reservados.</p>
        </div>
    </div>
</body>

</html>