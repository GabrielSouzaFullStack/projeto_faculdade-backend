<?php

/**
 * Processa o formulário de adoção usando arquitetura MVC
 */

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../admin/controllers/FormularioAdocaoController.php';

$pdo = getConnection();
$controller = new FormularioAdocaoController($pdo);
$controller->processar();
