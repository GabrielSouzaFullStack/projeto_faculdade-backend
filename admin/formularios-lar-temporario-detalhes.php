<?php

/**
 * Detalhes de Formulário de Lar Temporário
 * Visualiza informações completas e permite alterar status
 */

require_once __DIR__ . '/security/session.php';
require_once __DIR__ . '/config/database.php';

iniciar_sessao();
verificar_cookie_sessao();
exigir_login();

$conn = conectar_db();

// Pega ID do formulário
$id = $_GET['id'] ?? 0;

if (!$id) {
    header('Location: formularios-lar-temporario.php');
    exit;
}

// Busca formulário
try {
    $stmt = $conn->prepare("SELECT * FROM formularios_lar_temporario WHERE id_formulario = ?");
    $stmt->execute([$id]);
    $form = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$form) {
        header('Location: formularios-lar-temporario.php');
        exit;
    }
} catch (Exception $e) {
    header('Location: formularios-lar-temporario.php');
    exit;
}

$page_title = 'Detalhes do Formulário #' . $id;
$page_active = 'formularios-lar';
require_once __DIR__ . '/views/layout-header.php';
?>

<style>
    .detail-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .detail-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
    }

    .detail-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .detail-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .detail-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .status-selector {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .status-btn {
        padding: 12px 24px;
        border: 2px solid #ddd;
        background: #fff;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .status-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .status-btn.active {
        border-color: #4CAF50;
        background: #4CAF50;
        color: white;
    }

    .status-btn.pendente {
        border-color: #ffc107;
    }

    .status-btn.pendente.active {
        background: #ffc107;
        border-color: #ffc107;
    }

    .status-btn.em_analise {
        border-color: #2196F3;
    }

    .status-btn.em_analise.active {
        background: #2196F3;
        border-color: #2196F3;
    }

    .status-btn.aprovado {
        border-color: #4CAF50;
    }

    .status-btn.aprovado.active {
        background: #4CAF50;
        border-color: #4CAF50;
    }

    .status-btn.recusado {
        border-color: #f44336;
    }

    .status-btn.recusado.active {
        background: #f44336;
        border-color: #f44336;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .alert-success {
        padding: 15px 20px;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        color: #155724;
        margin-bottom: 20px;
        display: none;
    }

    .alert-error {
        padding: 15px 20px;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        color: #721c24;
        margin-bottom: 20px;
        display: none;
    }

    @media (max-width: 768px) {
        .detail-card {
            padding: 18px;
        }

        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .detail-title {
            font-size: 20px;
        }

        .status-selector {
            flex-direction: column;
        }

        .status-btn {
            width: 100%;
            text-align: center;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="content-header">
    <h1>Formulário de Lar Temporário #<?php echo $id; ?></h1>
    <a href="formularios-lar-temporario.php" class="btn btn-secondary">← Voltar</a>
</div>

<div id="alertSuccess" class="alert-success"></div>
<div id="alertError" class="alert-error"></div>

<div class="detail-card">
    <div class="detail-header">
        <h2 class="detail-title">Informações do Candidato</h2>
        <?php
        $status_colors = [
            'pendente' => 'warning',
            'em_analise' => 'info',
            'aprovado' => 'success',
            'recusado' => 'danger'
        ];
        $status_class = $status_colors[$form['status']] ?? 'secondary';
        ?>
        <span class="badge badge-<?php echo $status_class; ?>" style="font-size: 16px; padding: 8px 16px;">
            <?php echo ucfirst(str_replace('_', ' ', $form['status'])); ?>
        </span>
    </div>

    <!-- Dados Pessoais -->
    <div class="detail-section">
        <h3 class="detail-section-title">📋 Dados Pessoais</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Nome Completo</span>
                <span class="detail-value"><?php echo htmlspecialchars($form['nome_completo']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?php echo htmlspecialchars($form['email']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Telefone</span>
                <span class="detail-value"><?php echo htmlspecialchars($form['telefone']); ?></span>
            </div>
            <?php if (!empty($form['cpf'])): ?>
                <div class="detail-item">
                    <span class="detail-label">CPF</span>
                    <span class="detail-value"><?php echo htmlspecialchars($form['cpf']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Endereço -->
    <?php if (!empty($form['endereco'])): ?>
        <div class="detail-section">
            <h3 class="detail-section-title">📍 Endereço</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Endereço</span>
                    <span class="detail-value"><?php echo htmlspecialchars($form['endereco']); ?></span>
                </div>
                <?php if (!empty($form['cidade'])): ?>
                    <div class="detail-item">
                        <span class="detail-label">Cidade/Estado</span>
                        <span class="detail-value"><?php echo htmlspecialchars($form['cidade']) . '/' . htmlspecialchars($form['estado']); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Informações da Residência -->
    <div class="detail-section">
        <h3 class="detail-section-title">🏠 Informações da Residência</h3>
        <div class="detail-grid">
            <?php if (!empty($form['tipo_residencia'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Tipo de Residência</span>
                    <span class="detail-value"><?php echo htmlspecialchars($form['tipo_residencia']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($form['tem_outros_animais'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Possui Outros Animais?</span>
                    <span class="detail-value"><?php echo $form['tem_outros_animais'] ? 'Sim' : 'Não'; ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Animais Existentes -->
    <?php if (!empty($form['quais_animais'])): ?>
        <div class="detail-section">
            <h3 class="detail-section-title">🐾 Animais Existentes</h3>
            <div class="detail-item">
                <span class="detail-label">Detalhes dos Animais</span>
                <span class="detail-value"><?php echo nl2br(htmlspecialchars($form['quais_animais'])); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Disponibilidade -->
    <div class="detail-section">
        <h3 class="detail-section-title">⏰ Disponibilidade</h3>
        <div class="detail-grid">
            <?php if (!empty($form['tempo_disponivel'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Tempo Disponível</span>
                    <span class="detail-value"><?php echo htmlspecialchars($form['tempo_disponivel']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($form['tipo_animal_aceita'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Tipo de Animal que Aceita</span>
                    <span class="detail-value"><?php echo htmlspecialchars($form['tipo_animal_aceita']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Experiência -->
    <?php if (!empty($form['experiencia_animais'])): ?>
        <div class="detail-section">
            <h3 class="detail-section-title">✨ Experiência com Animais</h3>
            <div class="detail-item">
                <span class="detail-value"><?php echo nl2br(htmlspecialchars($form['experiencia_animais'])); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Observações -->
    <?php if (!empty($form['observacoes'])): ?>
        <div class="detail-section">
            <h3 class="detail-section-title">📝 Observações Adicionais</h3>
            <div class="detail-item">
                <span class="detail-value"><?php echo nl2br(htmlspecialchars($form['observacoes'])); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Informações de Envio -->
    <div class="detail-section">
        <h3 class="detail-section-title">ℹ️ Informações do Formulário</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Data de Envio</span>
                <span class="detail-value"><?php echo date('d/m/Y H:i:s', strtotime($form['data_envio'])); ?></span>
            </div>
            <?php if (!empty($form['data_atualizacao']) && $form['data_atualizacao'] != $form['data_envio']): ?>
                <div class="detail-item">
                    <span class="detail-label">Última Atualização</span>
                    <span class="detail-value"><?php echo date('d/m/Y H:i:s', strtotime($form['data_atualizacao'])); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alterar Status -->
    <div class="detail-section">
        <h3 class="detail-section-title">🔄 Alterar Status</h3>
        <div class="status-selector">
            <button class="status-btn pendente <?php echo $form['status'] === 'pendente' ? 'active' : ''; ?>"
                data-status="pendente">
                ⏳ Pendente
            </button>
            <button class="status-btn em_analise <?php echo $form['status'] === 'em_analise' ? 'active' : ''; ?>"
                data-status="em_analise">
                🔍 Em Análise
            </button>
            <button class="status-btn aprovado <?php echo $form['status'] === 'aprovado' ? 'active' : ''; ?>"
                data-status="aprovado">
                ✅ Aprovado
            </button>
            <button class="status-btn recusado <?php echo $form['status'] === 'recusado' ? 'active' : ''; ?>"
                data-status="recusado">
                ❌ Recusado
            </button>
        </div>
    </div>

    <div class="action-buttons">
        <a href="formularios-lar-temporario.php" class="btn btn-secondary">← Voltar à Lista</a>
    </div>
</div>

<script src="assets/js/alterar-status-formulario.js"></script>
<script>
    // Inicializa o controle de status
    inicializarControleStatus(<?php echo $id; ?>, 'lar_temporario');
</script>

<?php require_once __DIR__ . '/views/layout-footer.php'; ?>