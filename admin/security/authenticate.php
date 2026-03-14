<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/crypto.php';

function autenticar_usuario(string $login, string $senha): ?array
{
    $pdo = conectar_db();
    if (!$pdo) {
        return null;
    }

    $stmt = $pdo->prepare(
        'SELECT id_usuario, id_pessoa, login, password, id_perfil
         FROM wtz_usuarios
         WHERE login = :login
         LIMIT 1'
    );
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        return null;
    }

    if (!validar_senha($senha, $usuario['password'])) {
        return null;
    }

    return [
        'id_usuario' => (int) $usuario['id_usuario'],
        'id_pessoa' => (int) $usuario['id_pessoa'],
        'login' => $usuario['login'],
        'id_perfil' => (int) $usuario['id_perfil'],
    ];
}
