<?php

/**
 * Gerenciamento de Animais
 * CRUD completo para animais disponíveis para adoção
 */

require_once __DIR__ . '/controllers/animais-controller.php';

// Instancia o controller
$controller = new AnimaisController();

// Define a ação
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Processa ações
$mensagem = '';
$tipo_mensagem = '';

switch ($action) {
    case 'new':
    case 'edit':
        // Carrega animal para edição  
        $animal = null;
        if ($action === 'edit' && $id) {
            $animal = $controller->buscarPorId($id);
            if (!$animal) {
                header('Location: animais.php');
                exit();
            }
        }

        // Processa formulário
        if (isset($_POST['salvar'])) {
            if ($animal) {
                // Atualizar
                $sucesso = $controller->atualizar($id, $_POST);
            } else {
                // Cadastrar
                $sucesso = $controller->cadastrar($_POST);
            }

            $mensagem = $controller->getMensagem();
            $tipo_mensagem = $controller->getTipoMensagem();

            if ($sucesso) {
                header('Location: animais.php?msg=success');
                exit();
            }
        }

        // Exibe formulário
        require_once __DIR__ . '/views/animais-form-view.php';
        break;

    case 'delete':
        // Exclui animal
        if ($id) {
            $controller->excluir($id);
            header('Location: animais.php?msg=deleted');
            exit();
        }
        header('Location: animais.php');
        exit();

    case 'list':
    default:
        // Mensagens de feedback
        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case 'success':
                    $mensagem = 'Operação realizada com sucesso!';
                    $tipo_mensagem = 'success';
                    break;
                case 'deleted':
                    $mensagem = 'Animal excluído com sucesso!';
                    $tipo_mensagem = 'success';
                    break;
            }
        }

        // Aplica filtros
        $filtros = [
            'tipo' => $_GET['tipo'] ?? '',
            'status' => $_GET['status'] ?? '',
            'busca' => $_GET['busca'] ?? ''
        ];

        // Lista animais
        $animais = $controller->listar($filtros);

        // Exibe lista
        require_once __DIR__ . '/views/animais-lista-view.php';
        break;
}
