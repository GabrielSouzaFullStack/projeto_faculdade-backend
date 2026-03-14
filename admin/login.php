<?php

/**
 * Página de Login - Painel Administrativo
 * Utiliza o padrão MVC para separar lógica de apresentação
 */

require_once __DIR__ . '/controllers/login-controller.php';

// Instancia o controller
$loginController = new LoginController();

// Verifica se já está logado
$loginController->verificarUsuarioLogado();

// Processa o formulário
$loginController->processarLogin();

// Verifica erro via GET
$loginController->verificarErroGet();

// Variáveis para a view
$temErro = $loginController->temErro();
$mensagemErro = $loginController->getErro();

// Inclui a view
require_once __DIR__ . '/views/login-view.php';
