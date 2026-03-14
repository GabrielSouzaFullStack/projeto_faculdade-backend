<?php

/**
 * Visualização de Formulários de Adoção
 * Lista todos os formulários recebidos de interessados em adoção
 */

require_once __DIR__ . '/security/session.php';
require_once __DIR__ . '/config/database.php';

iniciar_sessao();
verificar_cookie_sessao();
exigir_login();

$conn = conectar_db();

// Pega filtro de status
$filtroStatus = $_GET['status'] ?? 'todos';

// Busca formulários
try {
    if ($filtroStatus === 'todos') {
        $stmt = $conn->query("
            SELECT * FROM formularios_adocao 
            ORDER BY data_envio DESC
        ");
    } else {
        $stmt = $conn->prepare("
            SELECT * FROM formularios_adocao 
            WHERE status = :status
            ORDER BY data_envio DESC
        ");
        $stmt->execute(['status' => $filtroStatus]);
    }
    $formularios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Conta formulários por status
    $stmtCounts = $conn->query("
        SELECT 
            status,
            COUNT(*) as total
        FROM formularios_adocao
        GROUP BY status
    ");
    $countsByStatus = [];
    while ($row = $stmtCounts->fetch(PDO::FETCH_ASSOC)) {
        $countsByStatus[$row['status']] = $row['total'];
    }

    $totalGeral = array_sum($countsByStatus);
} catch (Exception $e) {
    $formularios = [];
    $countsByStatus = [];
    $totalGeral = 0;
}

$page_title = 'Formulários de Adoção';
$page_active = 'formularios-adocao';
require_once __DIR__ . '/views/layout-header.php';
?>

<div class="content-header">
    <h1>Formulários de Adoção</h1>
    <p>Visualize e gerencie os pedidos de adoção recebidos</p>
</div>

<!-- Filtros de Status -->
<div class="content-section status-filters">
    <div class="status-filters-menu">
        <a href="?status=todos" class="btn <?php echo $filtroStatus === 'todos' ? 'btn-primary' : 'btn-secondary'; ?>">
            📋 Todos (<?php echo $totalGeral; ?>)
        </a>
        <a href="?status=pendente" class="btn <?php echo $filtroStatus === 'pendente' ? 'btn-warning' : 'btn-secondary'; ?>">
            ⏳ Pendentes (<?php echo $countsByStatus['pendente'] ?? 0; ?>)
        </a>
        <a href="?status=em_analise" class="btn <?php echo $filtroStatus === 'em_analise' ? 'btn-info' : 'btn-secondary'; ?>">
            🔍 Em Análise (<?php echo $countsByStatus['em_analise'] ?? 0; ?>)
        </a>
        <a href="?status=aprovado" class="btn <?php echo $filtroStatus === 'aprovado' ? 'btn-success' : 'btn-secondary'; ?>">
            ✅ Aprovados (<?php echo $countsByStatus['aprovado'] ?? 0; ?>)
        </a>
        <a href="?status=recusado" class="btn <?php echo $filtroStatus === 'recusado' ? 'btn-danger' : 'btn-secondary'; ?>">
            ❌ Recusados (<?php echo $countsByStatus['recusado'] ?? 0; ?>)
        </a>
    </div>
</div>

<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">
            Formulários Recebidos (<?php echo count($formularios); ?>)
        </h2>
    </div>

    <?php if (!empty($formularios)): ?>
        <div class="table-responsive">
            <table class="table table-mobile-cards">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Status</th>
                        <th>Data Envio</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($formularios as $form): ?>
                        <tr>
                            <td data-label="ID"><?php echo htmlspecialchars($form['id_formulario']); ?></td>
                            <td data-label="Nome"><strong><?php echo htmlspecialchars($form['nome_completo']); ?></strong></td>
                            <td data-label="Email"><?php echo htmlspecialchars($form['email']); ?></td>
                            <td data-label="Telefone"><?php echo htmlspecialchars($form['telefone']); ?></td>
                            <td data-label="Status">
                                <?php
                                $status_colors = [
                                    'pendente' => 'warning',
                                    'em_analise' => 'info',
                                    'aprovado' => 'success',
                                    'recusado' => 'danger'
                                ];
                                $status_class = $status_colors[$form['status']] ?? 'secondary';
                                ?>
                                <span class="badge badge-<?php echo $status_class; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $form['status'])); ?>
                                </span>
                            </td>
                            <td data-label="Data Envio"><?php echo date('d/m/Y H:i', strtotime($form['data_envio'])); ?></td>
                            <td data-label="Ações">
                                <a href="formularios-adocao-detalhes.php?id=<?php echo $form['id_formulario']; ?>"
                                    class="btn btn-sm btn-primary">
                                    👁️ Ver Detalhes
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center" style="padding: 60px 20px;">
            <p style="font-size: 48px; margin-bottom: 20px;">📋</p>
            <h3>Nenhum formulário recebido</h3>
            <p class="text-muted">Os formulários enviados pelo site aparecerão aqui.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/views/layout-footer.php'; ?>