<?php
$page_title = 'Gerenciar Animais';
$page_active = 'animais';
$extra_css = ['assets/css/animais.css'];
require_once __DIR__ . '/layout-header.php';
?>

<div class="content-header">
    <h1>Gerenciar Animais</h1>
    <p>Cadastre e gerencie os animaisque estão disponíveis para adoção</p>
</div>

<!-- Mensagem de feedback -->
<?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?php echo htmlspecialchars($tipo_mensagem); ?>" role="alert">
        <?php echo htmlspecialchars($mensagem); ?>
    </div>
<?php endif; ?>

<!-- Filtros -->
<div class="filters-card">
    <div class="filters-header">
        <h3>🔍 Buscar e Filtrar</h3>
        <?php
        $total_filtros = 0;
        if (!empty($_GET['busca'])) $total_filtros++;
        if (!empty($_GET['tipo'])) $total_filtros++;
        if (!empty($_GET['status'])) $total_filtros++;
        if ($total_filtros > 0):
        ?>
            <span class="filters-active-badge"><?php echo $total_filtros; ?> filtro(s) ativo(s)</span>
        <?php endif; ?>
    </div>

    <form method="GET" action="" class="filters-form">
        <div class="filters-row">
            <div class="filter-group">
                <label>🔎 Buscar</label>
                <div class="input-with-icon">
                    <input type="text" name="busca" class="form-control" placeholder="Digite nome, raça ou cor..."
                        value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
                    <?php if (!empty($_GET['busca'])): ?>
                        <button type="button" class="input-clear" onclick="this.previousElementSibling.value=''; this.closest('form').submit();">✕</button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="filter-group">
                <label>🐾 Tipo</label>
                <div class="input-with-icon">
                    <select name="tipo" class="form-control">
                        <option value="">Todos os tipos</option>
                        <option value="cachorro" <?php echo ($_GET['tipo'] ?? '') === 'cachorro' ? 'selected' : ''; ?>>🐶 Cachorro</option>
                        <option value="gato" <?php echo ($_GET['tipo'] ?? '') === 'gato' ? 'selected' : ''; ?>>🐱 Gato</option>
                        <option value="coelho" <?php echo ($_GET['tipo'] ?? '') === 'coelho' ? 'selected' : ''; ?>>🐰 Coelho</option>
                        <option value="outro" <?php echo ($_GET['tipo'] ?? '') === 'outro' ? 'selected' : ''; ?>>🦜 Outro</option>
                    </select>
                </div>
            </div>

            <div class="filter-group">
                <label>📊 Status</label>
                <div class="input-with-icon">
                    <select name="status" class="form-control">
                        <option value="">Todos os status</option>
                        <option value="disponivel" <?php echo ($_GET['status'] ?? '') === 'disponivel' ? 'selected' : ''; ?>>✅ Disponível</option>
                        <option value="em_processo" <?php echo ($_GET['status'] ?? '') === 'em_processo' ? 'selected' : ''; ?>>⏳ Em Processo</option>
                        <option value="adotado" <?php echo ($_GET['status'] ?? '') === 'adotado' ? 'selected' : ''; ?>>❤️ Adotado</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-filter">
                    Filtrar
                </button>
                <?php if ($total_filtros > 0): ?>
                    <a href="animais.php" class="btn btn-secondary btn-clear">
                        <span>🔄</span> Limpar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Lista de Animais -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">
            Animais Cadastrados (<?php echo count($animais); ?>)
        </h2>
        <a href="animais.php?action=new" class="btn btn-primary">
            ➕ Cadastrar Novo Animal
        </a>
    </div>

    <?php if (!empty($animais)): ?>
        <div class="animals-grid">
            <?php foreach ($animais as $animal): ?>
                <div class="animal-card">
                    <div class="animal-card-image">
                        <?php if (!empty($animal['foto'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($animal['foto']); ?>"
                                alt="<?php echo htmlspecialchars($animal['nome']); ?>">
                        <?php else: ?>
                            <?php
                            $emoji = ['cachorro' => '🐶', 'gato' => '🐱', 'coelho' => '🐰', 'outro' => '🐾'];
                            echo $emoji[$animal['tipo']] ?? '🐾';
                            ?>
                        <?php endif; ?>
                    </div>

                    <div class="animal-card-body">
                        <h3 class="animal-card-title"><?php echo htmlspecialchars($animal['nome']); ?></h3>

                        <div class="animal-card-info">
                            <span>🔹 <?php echo ucfirst($animal['tipo']); ?></span>
                            <span>⚥ <?php echo ucfirst($animal['sexo']); ?></span>
                            <span>📏 <?php echo ucfirst($animal['porte']); ?></span>
                        </div>

                        <?php if (!empty($animal['descricao'])): ?>
                            <p class="animal-card-description">
                                <?php echo htmlspecialchars($animal['descricao']); ?>
                            </p>
                        <?php endif; ?>

                        <div class="animal-card-badges">
                            <?php if ($animal['castrado']): ?>
                                <span class="badge badge-success">Castrado</span>
                            <?php endif; ?>
                            <?php if ($animal['vacinado']): ?>
                                <span class="badge badge-success">Vacinado</span>
                            <?php endif; ?>
                            <?php if ($animal['vermifugado']): ?>
                                <span class="badge badge-success">Vermifugado</span>
                            <?php endif; ?>
                            <?php
                            $status_badge = [
                                'disponivel' => 'primary',
                                'em_processo' => 'warning',
                                'adotado' => 'danger'
                            ];
                            $badge_class = $status_badge[$animal['status']] ?? 'secondary';
                            $status_text = str_replace('_', ' ', ucfirst($animal['status']));
                            ?>
                            <span class="badge badge-<?php echo $badge_class; ?>">
                                <?php echo $status_text; ?>
                            </span>
                        </div>

                        <div class="animal-card-actions">
                            <a href="animais.php?action=edit&id=<?php echo $animal['id_animal']; ?>"
                                class="btn btn-sm btn-primary" style="flex: 1;">
                                ✏️ Editar
                            </a>
                            <a href="animais.php?action=delete&id=<?php echo $animal['id_animal']; ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir este animal?')">
                                🗑️
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center" style="padding: 60px 20px;">
            <p style="font-size: 48px; margin-bottom: 20px;">🐾</p>
            <h3>Nenhum animal encontrado</h3>
            <p class="text-muted">Cadastre o primeiro animal para começar!</p>
            <a href="animais.php?action=new" class="btn btn-primary" style="margin-top: 20px;">
                Cadastrar Primeiro Animal
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layout-footer.php'; ?>