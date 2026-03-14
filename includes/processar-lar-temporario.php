<?php

/**
 * Processa o formulário de lar temporário usando arquitetura MVC
 */

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../admin/controllers/FormularioLarTemporarioController.php';

$pdo = getConnection();
$controller = new FormularioLarTemporarioController($pdo);
$controller->processar();
