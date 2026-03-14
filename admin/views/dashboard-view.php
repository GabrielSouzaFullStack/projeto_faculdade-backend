<?php
$page_title = 'Dashboard';
$page_active = 'dashboard';
require_once __DIR__ . '/layout-header.php';
?>

<div class="content-header">
    <h1>Dashboard</h1>
    <p>Visão geral do sistema de gerenciamento</p>
</div>

<!-- Cards de Estatísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">🐾</div>
        <div class="stat-label">Total de Animais</div>
        <div class="stat-value"><?php echo $stats['total_animais']; ?></div>
        <div class="stat-description">Cadastrados no sistema</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div class="stat-label">Disponíveis</div>
        <div class="stat-value"><?php echo $stats['animais_disponiveis']; ?></div>
        <div class="stat-description">Para adoção</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">📋</div>
        <div class="stat-label">Formulários Pendentes</div>
        <div class="stat-value"><?php echo $stats['formularios_pendentes']; ?></div>
        <div class="stat-description">Aguardando análise</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">❤️</div>
        <div class="stat-label">Adoções Este Mês</div>
        <div class="stat-value"><?php echo $stats['adocoes_mes']; ?></div>
        <div class="stat-description">Animais adotados</div>
    </div>
</div>

<!-- Últimos Animais Cadastrados -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Últimos Animais Cadastrados</h2>
        <a href="animais.php?action=new" class="btn btn-primary btn-sm">+ Cadastrar Novo</a>
    </div>

    <?php if (!empty($ultimos_animais)): ?>
        <div class="table-responsive">
            <table class="table table-mobile-cards">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Raça</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimos_animais as $animal): ?>
                        <tr>
                            <td data-label="ID"><?php echo htmlspecialchars($animal['id_animal']); ?></td>
                            <td data-label="Nome"><strong><?php echo htmlspecialchars($animal['nome']); ?></strong></td>
                            <td data-label="Tipo"><?php echo ucfirst(htmlspecialchars($animal['tipo'])); ?></td>
                            <td data-label="Raça"><?php echo htmlspecialchars($animal['raca'] ?? 'SRD'); ?></td>
                            <td data-label="Status">
                                <?php
                                $status_class = $animal['status'] === 'disponivel' ? 'success' : ($animal['status'] === 'adotado' ? 'danger' : 'warning');
                                $status_text = $animal['status'] === 'disponivel' ? 'Disponível' : ucfirst(str_replace('_', ' ', $animal['status']));
                                ?>
                                <span class="badge badge-<?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <td data-label="Data Cadastro"><?php echo date('d/m/Y', strtotime($animal['data_cadastro'])); ?></td>
                            <td data-label="Ações">
                                <a href="animais.php?action=edit&id=<?php echo $animal['id_animal']; ?>" class="btn btn-sm btn-primary">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Nenhum animal cadastrado ainda.</p>
    <?php endif; ?>
</div>

<!-- Últimos Formulários Recebidos -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Últimos Formulários Recebidos</h2>
        <a href="formularios-adocao.php" class="btn btn-secondary btn-sm">Ver Todos</a>
    </div>

    <?php if (!empty($ultimos_formularios)): ?>
        <div class="table-responsive">
            <table class="table table-mobile-cards">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Data Envio</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimos_formularios as $form): ?>
                        <tr>
                            <td data-label="Tipo">
                                <?php
                                $tipo_badge = $form['tipo'] === 'adocao' ? 'primary' : 'info';
                                $tipo_texto = $form['tipo'] === 'adocao' ? 'Adoção' : 'Lar Temporário';
                                ?>
                                <span class="badge badge-<?php echo $tipo_badge; ?>">
                                    <?php echo $tipo_texto; ?>
                                </span>
                            </td>
                            <td data-label="Nome"><?php echo htmlspecialchars($form['nome_completo']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($form['email']); ?></td>
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
                                <?php
                                $url = $form['tipo'] === 'adocao' ? 'formularios-adocao.php' : 'formularios-lar-temporario.php';
                                ?>
                                <a href="<?php echo $url; ?>?action=view" class="btn btn-sm btn-primary">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Nenhum formulário recebido ainda.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layout-footer.php'; ?>