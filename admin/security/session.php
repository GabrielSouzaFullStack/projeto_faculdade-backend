<?php

function iniciar_sessao(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function definir_usuario_logado(array $usuario, bool $lembrar = false): void
{
    iniciar_sessao();

    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['id_pessoa'] = $usuario['id_pessoa'];
    $_SESSION['id_perfil'] = $usuario['id_perfil'];

    // Mantem chaves antigas para compatibilidade com o codigo existente.
    $_SESSION['usuario'] = $usuario['login'];
    $_SESSION['login'] = $usuario['login'];

    // Se o usuário optou por ser lembrado, cria cookies persistentes
    if ($lembrar) {
        criar_cookie_sessao($usuario);
    }
}

function usuario_esta_logado(): bool
{
    iniciar_sessao();
    return isset($_SESSION['usuario']);
}

function exigir_login(string $redirect = 'login.php?erro=true'): void
{
    if (!usuario_esta_logado()) {
        header('Location: ' . $redirect);
        exit;
    }
}

/**
 * Cria cookies persistentes para lembrar o usuário
 */
function criar_cookie_sessao(array $usuario): void
{
    // Gera um token único e seguro
    $token = bin2hex(random_bytes(32));
    $id_usuario = $usuario['id_usuario'];

    // Expira em 30 dias
    $expiracao = time() + (30 * 24 * 60 * 60);

    // Define cookies seguros (HttpOnly para prevenir XSS)
    setcookie('lembrar_token', $token, [
        'expires' => $expiracao,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    setcookie('lembrar_usuario', $id_usuario, [
        'expires' => $expiracao,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    // Armazena o token em um arquivo seguro (em produção, use banco de dados)
    $token_file = __DIR__ . '/../.tokens/' . $id_usuario . '.token';
    $token_dir = dirname($token_file);

    if (!is_dir($token_dir)) {
        mkdir($token_dir, 0700, true);
    }

    file_put_contents($token_file, hash('sha256', $token) . '|' . $expiracao, LOCK_EX);
}

/**
 * Verifica se existe cookie de sessão válido e restaura a sessão
 */
function verificar_cookie_sessao(): void
{
    if (usuario_esta_logado()) {
        return; // Já está logado via sessão normal
    }

    // Verifica se os cookies existem
    if (!isset($_COOKIE['lembrar_token']) || !isset($_COOKIE['lembrar_usuario'])) {
        return;
    }

    $token = $_COOKIE['lembrar_token'];
    $id_usuario = (int) $_COOKIE['lembrar_usuario'];

    // Verifica o token armazenado
    $token_file = __DIR__ . '/../.tokens/' . $id_usuario . '.token';

    if (!file_exists($token_file)) {
        limpar_cookies_sessao();
        return;
    }

    $token_data = file_get_contents($token_file);
    list($token_hash, $expiracao) = explode('|', $token_data);

    // Verifica se o token expirou
    if ($expiracao < time()) {
        limpar_cookies_sessao();
        unlink($token_file);
        return;
    }

    // Verifica se o token corresponde
    if (hash('sha256', $token) !== $token_hash) {
        limpar_cookies_sessao();
        return;
    }

    // Token válido! Restaura a sessão do usuário
    require_once __DIR__ . '/../config/database.php';

    try {
        $conn = conectar_db();
        $stmt = $conn->prepare("SELECT id_usuario, id_pessoa, login, id_perfil FROM wtz_usuarios WHERE id_usuario = :id");
        $stmt->execute(['id' => $id_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            definir_usuario_logado($usuario, false); // Não recria os cookies
        } else {
            limpar_cookies_sessao();
        }
    } catch (Exception $e) {
        limpar_cookies_sessao();
    }
}

/**
 * Remove os cookies de sessão persistente
 */
function limpar_cookies_sessao(): void
{
    setcookie('lembrar_token', '', time() - 3600, '/');
    setcookie('lembrar_usuario', '', time() - 3600, '/');
}

/**
 * Faz logout completo, incluindo cookies
 */
function fazer_logout(): void
{
    iniciar_sessao();

    // Remove o arquivo de token se existir
    if (isset($_COOKIE['lembrar_usuario'])) {
        $id_usuario = (int) $_COOKIE['lembrar_usuario'];
        $token_file = __DIR__ . '/../.tokens/' . $id_usuario . '.token';
        if (file_exists($token_file)) {
            unlink($token_file);
        }
    }

    // Limpa cookies
    limpar_cookies_sessao();

    // Destroi a sessão
    session_destroy();

    header('Location: login.php');
    exit();
}
