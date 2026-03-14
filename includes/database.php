<?php

/**
 * Conexão com o banco de dados - Front-end
 * Compartilha as mesmas credenciais do admin
 */

require_once __DIR__ . '/../admin/config/database.php';

/**
 * Busca animais disponíveis para adoção
 * @param int $limit Limite de resultados (opcional)
 * @return array Lista de animais
 */
function getAnimaisDisponiveis($limit = null)
{
    $pdo = getConnection();

    $limit = $limit !== null ? max(1, (int) $limit) : null;

    // Lista apenas animais disponíveis para adoção.
    $sqlDisponiveis = "SELECT * FROM animais WHERE status = :status ORDER BY data_cadastro DESC";
    if ($limit !== null) {
        $sqlDisponiveis .= " LIMIT :limit";
    }

    $stmtDisponiveis = $pdo->prepare($sqlDisponiveis);
    $stmtDisponiveis->bindValue(':status', 'disponivel', PDO::PARAM_STR);
    if ($limit !== null) {
        $stmtDisponiveis->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    $stmtDisponiveis->execute();

    return $stmtDisponiveis->fetchAll();
}

/**
 * Sanitiza string para prevenir XSS
 * Usa ao exibir dados do banco em HTML
 */
function sanitize($string)
{
    if (empty($string)) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitiza para uso em atributos HTML
 */
function sanitizeAttr($string)
{
    if (empty($string)) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitiza para uso em URL
 */
function sanitizeUrl($url)
{
    if (empty($url)) {
        return '';
    }
    $url = filter_var($url, FILTER_SANITIZE_URL);
    // Apenas permite URLs http/https
    if (!preg_match('/^https?:\/\//i', $url)) {
        return '';
    }
    return $url;
}
