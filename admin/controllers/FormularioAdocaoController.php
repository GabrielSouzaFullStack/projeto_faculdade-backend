<?php

/**
 * Controller de Formulários de Adoção
 * Gerencia operações relacionadas a formulários de adoção
 */

require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/FormularioAdocaoModel.php';
require_once __DIR__ . '/../../includes/validacao.php';

class FormularioAdocaoController
{
    private $model;
    private $mensagem = '';
    private $tipoMensagem = '';

    public function __construct($conn)
    {
        $this->model = new FormularioAdocaoModel($conn);
    }

    /**
     * Processa submissão do formulário (API JSON)
     */
    public function processar()
    {
        header('Content-Type: application/json; charset=utf-8');

        $response = [
            'success' => false,
            'message' => ''
        ];

        try {
            // Proteção contra métodos inválidos
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                registrarAtividadeSuspeita('Tentativa de acesso com método inválido: ' . $_SERVER['REQUEST_METHOD']);
                throw new Exception('Método não permitido');
            }

            // Validação de tamanho do POST
            validarTamanhoPost();

            // Proteção contra spam/flood
            verificarRateLimit();

            // Valida e sanitiza dados
            $dadosValidados = $this->validarDados($_POST);

            // Cria o formulário usando o model
            $id = $this->model->criar($dadosValidados);

            if ($id) {
                $response['success'] = true;
                $response['message'] = 'Formulário enviado com sucesso! Entraremos em contato em breve.';
                $response['id'] = $id;
            } else {
                throw new Exception('Erro ao salvar formulário. Tente novamente.');
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            error_log("Erro ao processar formulário de adoção: " . $e->getMessage());
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Valida e sanitiza dados do formulário
     */
    private function validarDados($post)
    {
        // Valida campos obrigatórios
        $nomeCompleto = validarNome($post['nome'] ?? '');
        $telefone = validarTelefone($post['telefone'] ?? '');

        // Valida campos opcionais
        $email = !empty($post['email'])
            ? validarEmail($post['email'])
            : 'nao-informado@email.com';

        $cpf = validarCPF($post['cpf'] ?? '');
        $endereco = validarEndereco($post['endereco'] ?? '');

        // Valida campos booleanos
        $temCriancas = validarBooleano($post['ha_criancas'] ?? null);
        $temOutrosAnimais = validarBooleano($post['tem_outros_animais'] ?? null);

        // Valida campos de texto livre
        $quaisAnimais = validarTextoLivre($post['detalhes_animais'] ?? '', 500);
        $observacoes = validarTextoLivre($post['mais_informacoes'] ?? '', 1000);

        // Valida checkboxes
        $tipoAnimalPermitido = ['Cachorro', 'Gato'];
        $animalPreferencia = validarCheckboxArray($post['tipo_animal'] ?? [], $tipoAnimalPermitido);

        // Valida radio buttons
        $localPermitido = ['Patio', 'Casa', 'Apartamento', 'Outro'];
        $ondeAnimalFica = validarRadio($post['local'] ?? '', $localPermitido);

        // Verifica castração
        $animaisCastrados = 0;
        if (!empty($post['cachorro_castrado']) || !empty($post['gato_castrado'])) {
            $animaisCastrados = 1;
        }

        return [
            'nome_completo' => $nomeCompleto,
            'email' => $email,
            'telefone' => $telefone,
            'cpf' => $cpf,
            'endereco' => $endereco,
            'tem_criancas' => $temCriancas,
            'tem_outros_animais' => $temOutrosAnimais,
            'quais_animais' => $quaisAnimais,
            'animais_castrados' => $animaisCastrados,
            'animal_preferencia' => $animalPreferencia,
            'onde_animal_fica' => $ondeAnimalFica,
            'observacoes' => $observacoes,
            'status' => 'pendente'
        ];
    }

    /**
     * Lista formulários com filtros
     */
    public function listar($filtros = [])
    {
        try {
            return $this->model->listarComFiltros($filtros);
        } catch (Exception $e) {
            $this->mensagem = 'Erro ao listar formulários: ' . $e->getMessage();
            $this->tipoMensagem = 'danger';
            return [];
        }
    }

    /**
     * Busca um formulário por ID
     */
    public function buscarPorId($id)
    {
        try {
            return $this->model->findById($id);
        } catch (Exception $e) {
            $this->mensagem = 'Erro ao buscar formulário: ' . $e->getMessage();
            $this->tipoMensagem = 'danger';
            return null;
        }
    }

    /**
     * Atualiza status do formulário
     */
    public function atualizarStatus($id, $novoStatus)
    {
        try {
            $resultado = $this->model->atualizarStatus($id, $novoStatus);

            if ($resultado) {
                $this->mensagem = 'Status atualizado com sucesso!';
                $this->tipoMensagem = 'success';
                return true;
            }

            $this->mensagem = 'Erro ao atualizar status';
            $this->tipoMensagem = 'danger';
            return false;
        } catch (Exception $e) {
            $this->mensagem = 'Erro: ' . $e->getMessage();
            $this->tipoMensagem = 'danger';
            return false;
        }
    }

    /**
     * Retorna estatísticas
     */
    public function getEstatisticas()
    {
        return $this->model->getEstatisticas();
    }

    /**
     * Getters para mensagens
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function getTipoMensagem()
    {
        return $this->tipoMensagem;
    }
}
