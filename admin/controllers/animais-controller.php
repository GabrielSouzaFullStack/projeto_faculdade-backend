<?php

/**
 * Controller de Animais
 * CRUD completo para gerenciamento de animais
 */

require_once __DIR__ . '/../security/session.php';
require_once __DIR__ . '/../config/database.php';

class AnimaisController
{
    private $conn;
    private $mensagem = '';
    private $tipo_mensagem = '';

    public function __construct()
    {
        iniciar_sessao();
        verificar_cookie_sessao();
        exigir_login();

        $this->conn = conectar_db();
    }

    /**
     * Lista todos os animais com filtros opcionais
     */
    public function listar($filtros = [])
    {
        try {
            $sql = "SELECT * FROM animais WHERE 1=1";
            $params = [];

            // Filtro por tipo
            if (!empty($filtros['tipo'])) {
                $sql .= " AND tipo = :tipo";
                $params[':tipo'] = $filtros['tipo'];
            }

            // Filtro por status
            if (!empty($filtros['status'])) {
                $sql .= " AND status = :status";
                $params[':status'] = $filtros['status'];
            }

            // Filtro por busca
            if (!empty($filtros['busca'])) {
                $sql .= " AND (nome LIKE :busca OR raca LIKE :busca OR descricao LIKE :busca)";
                $params[':busca'] = '%' . $filtros['busca'] . '%';
            }

            $sql .= " ORDER BY data_cadastro DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->mensagem = 'Erro ao listar animais: ' . $e->getMessage();
            $this->tipo_mensagem = 'danger';
            return [];
        }
    }

    /**
     * Busca um animal por ID
     */
    public function buscarPorId($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM animais WHERE id_animal = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->mensagem = 'Erro ao buscar animal: ' . $e->getMessage();
            $this->tipo_mensagem = 'danger';
            return null;
        }
    }

    /**
     * Cadastra um novo animal
     */
    public function cadastrar($dados)
    {
        try {
            // Validação básica
            if (empty($dados['nome']) || empty($dados['tipo']) || empty($dados['sexo']) || empty($dados['porte'])) {
                $this->mensagem = 'Preencha todos os campos obrigatórios.';
                $this->tipo_mensagem = 'warning';
                return false;
            }

            // Upload de foto
            $foto = $this->processarUploadFoto();

            $sql = "INSERT INTO animais (
                nome, tipo, raca, idade_anos, idade_meses, sexo, porte, cor, 
                descricao, foto, castrado, vacinado, vermifugado, status
            ) VALUES (
                :nome, :tipo, :raca, :idade_anos, :idade_meses, :sexo, :porte, :cor,
                :descricao, :foto, :castrado, :vacinado, :vermifugado, :status
            )";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':nome' => $dados['nome'],
                ':tipo' => $dados['tipo'],
                ':raca' => $dados['raca'] ?? null,
                ':idade_anos' => $dados['idade_anos'] ?? null,
                ':idade_meses' => $dados['idade_meses'] ?? null,
                ':sexo' => $dados['sexo'],
                ':porte' => $dados['porte'],
                ':cor' => $dados['cor'] ?? null,
                ':descricao' => $dados['descricao'] ?? null,
                ':foto' => $foto,
                ':castrado' => isset($dados['castrado']) ? 1 : 0,
                ':vacinado' => isset($dados['vacinado']) ? 1 : 0,
                ':vermifugado' => isset($dados['vermifugado']) ? 1 : 0,
                ':status' => $dados['status'] ?? 'disponivel'
            ]);

            if ($result) {
                $this->mensagem = 'Animal cadastrado com sucesso!';
                $this->tipo_mensagem = 'success';
                return true;
            }

            return false;
        } catch (Exception $e) {
            $this->mensagem = 'Erro ao cadastrar animal: ' . $e->getMessage();
            $this->tipo_mensagem = 'danger';
            return false;
        }
    }

    /**
     * Atualiza um animal existente
     */
    public function atualizar($id, $dados)
    {
        try {
            // Validação básica
            if (empty($dados['nome']) || empty($dados['tipo']) || empty($dados['sexo']) || empty($dados['porte'])) {
                $this->mensagem = 'Preencha todos os campos obrigatórios.';
                $this->tipo_mensagem = 'warning';
                return false;
            }

            // Upload de foto (se houver)
            $foto = $this->processarUploadFoto();

            $sql = "UPDATE animais SET 
                nome = :nome, tipo = :tipo, raca = :raca, idade_anos = :idade_anos, 
                idade_meses = :idade_meses, sexo = :sexo, porte = :porte, cor = :cor,
                descricao = :descricao, castrado = :castrado, vacinado = :vacinado, 
                vermifugado = :vermifugado, status = :status";

            $params = [
                ':nome' => $dados['nome'],
                ':tipo' => $dados['tipo'],
                ':raca' => $dados['raca'] ?? null,
                ':idade_anos' => $dados['idade_anos'] ?? null,
                ':idade_meses' => $dados['idade_meses'] ?? null,
                ':sexo' => $dados['sexo'],
                ':porte' => $dados['porte'],
                ':cor' => $dados['cor'] ?? null,
                ':descricao' => $dados['descricao'] ?? null,
                ':castrado' => isset($dados['castrado']) ? 1 : 0,
                ':vacinado' => isset($dados['vacinado']) ? 1 : 0,
                ':vermifugado' => isset($dados['vermifugado']) ? 1 : 0,
                ':status' => $dados['status'] ?? 'disponivel',
                ':id' => $id
            ];

            if ($foto) {
                $sql .= ", foto = :foto";
                $params[':foto'] = $foto;
            }

            $sql .= " WHERE id_animal = :id";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($params);

            if ($result) {
                $this->mensagem = 'Animal atualizado com sucesso!';
                $this->tipo_mensagem = 'success';
                return true;
            }

            return false;
        } catch (Exception $e) {
            $this->mensagem = 'Erro ao atualizar animal: ' . $e->getMessage();
            $this->tipo_mensagem = 'danger';
            return false;
        }
    }

    /**
     * Exclui um animal
     */
    public function excluir($id)
    {
        try {
            // Busca a foto para deletar
            $animal = $this->buscarPorId($id);
            if ($animal && !empty($animal['foto'])) {
                $caminhoFoto = __DIR__ . '/../uploads/' . $animal['foto'];
                if (file_exists($caminhoFoto)) {
                    unlink($caminhoFoto);
                }
            }

            $stmt = $this->conn->prepare("DELETE FROM animais WHERE id_animal = :id");
            $result = $stmt->execute([':id' => $id]);

            if ($result) {
                $this->mensagem = 'Animal excluído com sucesso!';
                $this->tipo_mensagem = 'success';
                return true;
            }

            return false;
        } catch (Exception $e) {
            $this->mensagem = 'Erro ao excluir animal: ' . $e->getMessage();
            $this->tipo_mensagem = 'danger';
            return false;
        }
    }

    /**
     * Processa upload de foto
     */
    private function processarUploadFoto()
    {
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Validação de tipo
        $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['foto']['type'], $tiposPermitidos)) {
            $this->mensagem = 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP.';
            $this->tipo_mensagem = 'warning';
            return null;
        }

        // Validação de tamanho (5MB)
        if ($_FILES['foto']['size'] > 5 * 1024 * 1024) {
            $this->mensagem = 'Arquivo muito grande. Tamanho máximo: 5MB.';
            $this->tipo_mensagem = 'warning';
            return null;
        }

        // Cria pasta de uploads se não existir
        $pastaUploads = __DIR__ . '/../uploads';
        if (!is_dir($pastaUploads)) {
            mkdir($pastaUploads, 0755, true);
        }

        // Nome único para o arquivo
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = 'animal_' . time() . '_' . rand(1000, 9999) . '.' . $extensao;
        $caminhoDestino = $pastaUploads . '/' . $nomeArquivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoDestino)) {
            return $nomeArquivo;
        }

        return null;
    }

    /**
     * Retorna mensagem de feedback
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Retorna tipo da mensagem (success, warning, danger)
     */
    public function getTipoMensagem()
    {
        return $this->tipo_mensagem;
    }
}
