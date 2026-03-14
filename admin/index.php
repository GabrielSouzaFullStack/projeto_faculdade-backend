<?php

/**
 * Dashboard Principal - Página inicial do painel administrativo
 */

require_once __DIR__ . '/controllers/dashboard-controller.php';

// Instancia o controller
$controller = new DashboardController();

// Coleta dados
$stats = $controller->getEstatisticas();
$ultimos_animais = $controller->getUltimosAnimais(5);
$ultimos_formularios = $controller->getUltimosFormularios(5);

// Renderiza a view
require_once __DIR__ . '/views/dashboard-view.php';
