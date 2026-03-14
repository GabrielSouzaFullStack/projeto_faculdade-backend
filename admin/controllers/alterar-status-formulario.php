<?php

/**
 * Controller para alterar status de formulários
 * API REST para atualização via AJAX
 */

require_once __DIR__ . '/../security/session.php';
require_once __DIR__ . '/../config/database.php';

// Configuração de resposta JSON
header('Content-Type: application/json; charset=utf-8');

// Resposta padrão
$response = [
    'success' => false,
    'message' => ''
];

try {
    // Verifica sessão
    iniciar_sessao();
    verificar_cookie_sessao();

    if (!isset($_SESSION['login'])) {
        throw new Exception('Não autorizado. Faça login novamente.');
    }

    // Verifica método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }

    // Pega dados JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        throw new Exception('Dados inválidos');
    }

    // Valida campos obrigatórios
    if (empty($data['id']) || empty($data['tipo']) || empty($data['status'])) {
        throw new Exception('Parâmetros obrigatórios ausentes');
    }

    $id = (int)$data['id'];
    $tipo = $data['tipo'];
    $novoStatus = $data['status'];

    // Valida tipo de formulário
    $tiposPermitidos = ['adocao', 'lar_temporario'];
    if (!in_array($tipo, $tiposPermitidos)) {
        throw new Exception('Tipo de formulário inválido');
    }

    // Valida status
    $statusPermitidos = ['pendente', 'em_analise', 'aprovado', 'recusado'];
    if (!in_array($novoStatus, $statusPermitidos)) {
        throw new Exception('Status inválido');
    }

    // Define tabela
    $tabela = $tipo === 'adocao' ? 'formularios_adocao' : 'formularios_lar_temporario';

    // Conecta ao banco
    $conn = conectar_db();

    // Atualiza status
    $sql = "UPDATE {$tabela} SET status = :status WHERE id_formulario = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $novoStatus, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Verifica se realmente atualizou
        if ($stmt->rowCount() > 0) {
            $statusTexto = ucfirst(str_replace('_', ' ', $novoStatus));
            $response['success'] = true;
            $response['message'] = "Status alterado para '{$statusTexto}' com sucesso!";

            // Log da ação
            error_log("Status do formulário #{$id} ({$tabela}) alterado para '{$novoStatus}' por " . $_SESSION['login']);
        } else {
            // Pode ser que o status já era esse
            $response['success'] = true;
            $response['message'] = "Status já está definido como '{$novoStatus}'";
        }
    } else {
        throw new Exception('Erro ao atualizar status no banco de dados');
    }
} catch (PDOException $e) {
    $response['message'] = 'Erro de banco de dados: ' . $e->getMessage();
    error_log('Erro PDO em alterar-status-formulario.php: ' . $e->getMessage());
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('Erro em alterar-status-formulario.php: ' . $e->getMessage());
}

// Retorna JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
