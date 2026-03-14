<?php
$page_title = ($animal ? 'Editar' : 'Cadastrar') . ' Animal';
$page_active = $animal ? 'animais' : 'animais-novo';
$extra_css = ['assets/css/animais.css'];
require_once __DIR__ . '/layout-header.php';
?>

<div class="content-header">
    <h1><?php echo $animal ? 'Editar' : 'Cadastrar'; ?> Animal</h1>
    <p><?php echo $animal ? 'Atualize as informações do animal' : 'Preencha os dados do novo animal'; ?></p>
</div>

<!-- Mensagem de feedback -->
<?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?php echo htmlspecialchars($tipo_mensagem); ?>" role="alert">
        <?php echo htmlspecialchars($mensagem); ?>
    </div>
<?php endif; ?>

<div class="animal-form">
    <form method="POST" action="" enctype="multipart/form-data">
        <!-- Informações Básicas -->
        <div class="form-section">
            <div class="form-section-header">
                <h3>📝 Informações Básicas</h3>
                <p class="form-section-subtitle">Dados principais de identificação do animal</p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nome" class="form-label">
                        <span class="label-icon">🏷️</span>
                        Nome <span class="required-mark">*</span>
                    </label>
                    <input type="text" id="nome" name="nome" class="form-control" required
                        value="<?php echo htmlspecialchars($animal['nome'] ?? ''); ?>"
                        placeholder="Ex: Rex, Mimi, Docinho...">
                </div>

                <div class="form-group">
                    <label for="tipo" class="form-label">
                        <span class="label-icon">🐾</span>
                        Tipo <span class="required-mark">*</span>
                    </label>
                    <select id="tipo" name="tipo" class="form-control" required>
                        <option value="">Selecione o tipo...</option>
                        <option value="cachorro" <?php echo ($animal['tipo'] ?? '') === 'cachorro' ? 'selected' : ''; ?>>🐶 Cachorro</option>
                        <option value="gato" <?php echo ($animal['tipo'] ?? '') === 'gato' ? 'selected' : ''; ?>>🐱 Gato</option>
                        <option value="coelho" <?php echo ($animal['tipo'] ?? '') === 'coelho' ? 'selected' : ''; ?>>🐰 Coelho</option>
                        <option value="outro" <?php echo ($animal['tipo'] ?? '') === 'outro' ? 'selected' : ''; ?>>🦜 Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="raca" class="form-label">
                        <span class="label-icon">🎯</span>
                        Raça
                    </label>
                    <input type="text" id="raca" name="raca" class="form-control"
                        value="<?php echo htmlspecialchars($animal['raca'] ?? ''); ?>"
                        placeholder="Ex: Labrador, Siamês, SRD...">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="idade_anos" class="form-label">
                    <span class="label-icon">⏱️</span>
                    Idade (anos)
                </label>
                <input type="number" id="idade_anos" name="idade_anos" class="form-control" min="0" max="30"
                    value="<?php echo htmlspecialchars($animal['idade_anos'] ?? ''); ?>"
                    placeholder="0">
            </div>

            <div class="form-group">
                <label for="idade_meses" class="form-label">
                    <span class="label-icon">📅</span>
                    Idade (meses)
                </label>
                <input type="number" id="idade_meses" name="idade_meses" class="form-control" min="0" max="11"
                    value="<?php echo htmlspecialchars($animal['idade_meses'] ?? ''); ?>"
                    placeholder="0">
            </div>

            <div class="form-group">
                <label for="sexo" class="form-label">
                    <span class="label-icon">⚥</span>
                    Sexo <span class="required-mark">*</span>
                </label>
                <select id="sexo" name="sexo" class="form-control" required>
                    <option value="">Selecione o sexo...</option>
                    <option value="macho" <?php echo ($animal['sexo'] ?? '') === 'macho' ? 'selected' : ''; ?>>♂️ Macho</option>
                    <option value="femea" <?php echo ($animal['sexo'] ?? '') === 'femea' ? 'selected' : ''; ?>>♀️ Fêmea</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="porte" class="form-label">
                    <span class="label-icon">📏</span>
                    Porte <span class="required-mark">*</span>
                </label>
                <select id="porte" name="porte" class="form-control" required>
                    <option value="">Selecione o porte...</option>
                    <option value="pequeno" <?php echo ($animal['porte'] ?? '') === 'pequeno' ? 'selected' : ''; ?>>🐕 Pequeno</option>
                    <option value="medio" <?php echo ($animal['porte'] ?? '') === 'medio' ? 'selected' : ''; ?>>🐕 Médio</option>
                    <option value="grande" <?php echo ($animal['porte'] ?? '') === 'grande' ? 'selected' : ''; ?>>🐕 Grande</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cor" class="form-label">
                    <span class="label-icon">🎨</span>
                    Cor
                </label>
                <input type="text" id="cor" name="cor" class="form-control"
                    value="<?php echo htmlspecialchars($animal['cor'] ?? ''); ?>"
                    placeholder="Ex: Preto, Branco, Caramelo...">
            </div>

            <div class="form-group">
                <label for="status" class="form-label">
                    <span class="label-icon">📊</span>
                    Status
                </label>
                <select id="status" name="status" class="form-control">
                    <option value="disponivel" <?php echo ($animal['status'] ?? 'disponivel') === 'disponivel' ? 'selected' : ''; ?>>✅ Disponível</option>
                    <option value="em_processo" <?php echo ($animal['status'] ?? '') === 'em_processo' ? 'selected' : ''; ?>>⏳ Em Processo</option>
                    <option value="adotado" <?php echo ($animal['status'] ?? '') === 'adotado' ? 'selected' : ''; ?>>❤️ Adotado</option>
                </select>
            </div>
        </div>

        <!-- Descrição -->
        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="descricao" class="form-label">
                    <span class="label-icon">📋</span>
                    Descrição
                </label>
                <textarea id="descricao" name="descricao" class="form-control" rows="4"
                    placeholder="Conte um pouco sobre a personalidade e características do animal..."><?php echo htmlspecialchars($animal['descricao'] ?? ''); ?></textarea>
                <small class="form-text">Descreva o temperamento, comportamento e características especiais</small>
            </div>
        </div>

        <!-- Saúde -->
        <div class="form-section">
            <div class="form-section-header">
                <h3>💉 Informações de Saúde</h3>
                <p class="form-section-subtitle">Marque os cuidados veterinários já realizados</p>
            </div>

            <div class="checkbox-group-modern">
                <label class="checkbox-card">
                    <input type="checkbox" id="castrado" name="castrado" value="1"
                        <?php echo !empty($animal['castrado']) ? 'checked' : ''; ?>>
                    <span class="checkbox-card-content">
                        <span class="checkbox-card-icon">✂️</span>
                        <span class="checkbox-card-label">Castrado</span>
                        <span class="checkbox-card-check">✓</span>
                    </span>
                </label>

                <label class="checkbox-card">
                    <input type="checkbox" id="vacinado" name="vacinado" value="1"
                        <?php echo !empty($animal['vacinado']) ? 'checked' : ''; ?>>
                    <span class="checkbox-card-content">
                        <span class="checkbox-card-icon">💉</span>
                        <span class="checkbox-card-label">Vacinado</span>
                        <span class="checkbox-card-check">✓</span>
                    </span>
                </label>

                <label class="checkbox-card">
                    <input type="checkbox" id="vermifugado" name="vermifugado" value="1"
                        <?php echo !empty($animal['vermifugado']) ? 'checked' : ''; ?>>
                    <span class="checkbox-card-content">
                        <span class="checkbox-card-icon">💊</span>
                        <span class="checkbox-card-label">Vermifugado</span>
                        <span class="checkbox-card-check">✓</span>
                    </span>
                </label>
            </div>
        </div>

        <!-- Foto -->
        <div class="form-section">
            <div class="form-section-header">
                <h3>📷 Foto do Animal</h3>
                <p class="form-section-subtitle">Adicione uma foto para ajudar na divulgação</p>
            </div>

            <div class="form-group">
                <label for="foto" class="form-label">
                    <span class="label-icon">🖼️</span>
                    Upload de Foto
                </label>
                <div class="photo-upload-area">
                    <input type="file" id="foto" name="foto" class="form-control-file" accept="image/*" onchange="previewImage(event)">
                    <div class="photo-preview-modern" id="photoPreview">
                        <?php if (!empty($animal['foto'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($animal['foto']); ?>" alt="Preview">
                            <div class="photo-overlay">
                                <span class="photo-change-text">📷 Clique para alterar</span>
                            </div>
                        <?php else: ?>
                            <div class="photo-preview-placeholder">
                                <span style="font-size: 64px; opacity: 0.3;">📷</span>
                                <p style="margin: 15px 0 5px; font-weight: 500;">Clique ou arraste a foto aqui</p>
                                <small style="color: var(--text-muted);">JPG, PNG, GIF ou WEBP • Máx: 5MB</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="form-actions">
            <a href="animais.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" name="salvar" class="btn btn-primary">
                <?php echo $animal ? '💾 Salvar Alterações' : '➕ Cadastrar Animal'; ?>
            </button>
        </div>
    </form>
</div>

<script>
    // Preview de imagem com melhorias visuais
    function previewImage(event) {
        const preview = document.getElementById('photoPreview');
        const file = event.target.files[0];

        if (file) {
            // Validação de tipo
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('❌ Formato inválido! Use JPG, PNG, GIF ou WEBP.');
                return;
            }

            // Validação de tamanho (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('❌ Arquivo muito grande! Tamanho máximo: 5MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" style="animation: fadeIn 0.3s ease;">
                    <div class="photo-overlay">
                        <span class="photo-change-text">📷 Clique para alterar</span>
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        }
    }

    // Drag & Drop para upload de foto
    document.addEventListener('DOMContentLoaded', function() {
        const photoPreview = document.getElementById('photoPreview');
        const fileInput = document.getElementById('foto');

        if (photoPreview && fileInput) {
            // Previne comportamento padrão do drag & drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                photoPreview.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Adiciona efeito visual no drag
            ['dragenter', 'dragover'].forEach(eventName => {
                photoPreview.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                photoPreview.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                photoPreview.style.borderColor = 'var(--primary)';
                photoPreview.style.background = 'linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%)';
                photoPreview.style.transform = 'scale(1.02)';
            }

            function unhighlight(e) {
                photoPreview.style.borderColor = 'var(--gray-300)';
                photoPreview.style.background = 'linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%)';
                photoPreview.style.transform = 'scale(1)';
            }

            // Handle drop
            photoPreview.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    previewImage({
                        target: fileInput
                    });
                }
            }

            // Click na área para abrir seletor
            photoPreview.addEventListener('click', function() {
                fileInput.click();
            });
        }

        // Validação de idade
        const idadeAnos = document.getElementById('idade_anos');
        const idadeMeses = document.getElementById('idade_meses');

        if (idadeAnos && idadeMeses) {
            idadeAnos.addEventListener('change', function() {
                if (this.value > 0) {
                    idadeMeses.value = idadeMeses.value || 0;
                }
            });
        }
    });
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .photo-preview-modern {
        cursor: pointer;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
        outline: none;
    }

    /* Animação nos checkboxes */
    .checkbox-card-content {
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<?php require_once __DIR__ . '/layout-footer.php'; ?>