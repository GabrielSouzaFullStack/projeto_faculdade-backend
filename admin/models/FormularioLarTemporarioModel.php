<?php

require_once __DIR__ . '/Model.php';

/**
 * Model de Formulário de Lar Temporário
 * Gerencia operações relacionadas a formulários de lar temporário
 */
class FormularioLarTemporarioModel extends Model
{
    protected $table = 'formularios_lar_temporario';
    protected $primaryKey = 'id';

    /**
     * Lista formulários com filtros
     */
    public function listarComFiltros($filtros = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Filtro por status
        if (!empty($filtros['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filtros['status'];
        }

        // Filtro por busca (nome, email ou telefone)
        if (!empty($filtros['busca'])) {
            $sql .= " AND (nome_completo LIKE :busca OR email LIKE :busca OR telefone LIKE :busca)";
            $params[':busca'] = '%' . $filtros['busca'] . '%';
        }

        // Filtro por tipo de animal
        if (!empty($filtros['tipo_animal'])) {
            $sql .= " AND tipo_animal_aceita LIKE :tipo_animal";
            $params[':tipo_animal'] = '%' . $filtros['tipo_animal'] . '%';
        }

        // Filtro por data
        if (!empty($filtros['data_inicio'])) {
            $sql .= " AND data_envio >= :data_inicio";
            $params[':data_inicio'] = $filtros['data_inicio'];
        }

        if (!empty($filtros['data_fim'])) {
            $sql .= " AND data_envio <= :data_fim";
            $params[':data_fim'] = $filtros['data_fim'];
        }

        $sql .= " ORDER BY data_envio DESC";

        return $this->query($sql, $params);
    }

    /**
     * Cria um novo formulário de lar temporário
     */
    public function criar($dados)
    {
        $dadosFormulario = [
            'nome_completo' => $dados['nome_completo'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'cpf' => $dados['cpf'] ?? null,
            'endereco' => $dados['endereco'] ?? null,
            'tipo_residencia' => $dados['tipo_residencia'] ?? null,
            'tem_outros_animais' => $dados['tem_outros_animais'] ?? 0,
            'quais_animais' => $dados['quais_animais'] ?? null,
            'tipo_animal_aceita' => $dados['tipo_animal_aceita'] ?? null,
            'tempo_disponivel' => $dados['tempo_disponivel'] ?? null,
            'experiencia_animais' => $dados['experiencia_animais'] ?? null,
            'observacoes' => $dados['observacoes'] ?? null,
            'status' => $dados['status'] ?? 'pendente',
            'data_envio' => date('Y-m-d H:i:s')
        ];

        return $this->insert($dadosFormulario);
    }

    /**
     * Atualiza status do formulário
     */
    public function atualizarStatus($id, $novoStatus)
    {
        $statusPermitidos = ['pendente', 'em_analise', 'aprovado', 'reprovado', 'ativo', 'finalizado'];

        if (!in_array($novoStatus, $statusPermitidos)) {
            return false;
        }

        return $this->update($id, [
            'status' => $novoStatus,
            'data_atualizacao' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Conta formulários por status
     */
    public function contarPorStatus($status = 'pendente')
    {
        return $this->count(['status' => $status]);
    }

    /**
     * Busca formulários pendentes
     */
    public function buscarPendentes()
    {
        return $this->findAll(['status' => 'pendente'], 'data_envio DESC');
    }

    /**
     * Busca lares temporários ativos
     */
    public function buscarAtivos()
    {
        return $this->findAll(['status' => 'ativo'], 'nome_completo ASC');
    }

    /**
     * Estatísticas de formulários
     */
    public function getEstatisticas()
    {
        $stats = [
            'total' => $this->count(),
            'pendentes' => $this->count(['status' => 'pendente']),
            'em_analise' => $this->count(['status' => 'em_analise']),
            'aprovados' => $this->count(['status' => 'aprovado']),
            'ativos' => $this->count(['status' => 'ativo']),
            'finalizados' => $this->count(['status' => 'finalizado'])
        ];

        return $stats;
    }

    /**
     * Busca formulários recentes
     */
    public function buscarRecentes($limite = 10)
    {
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY data_envio DESC 
                LIMIT :limite";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Conta formulários do mês atual
     */
    public function contarFormulariosMes()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE MONTH(data_envio) = MONTH(CURRENT_DATE()) 
                AND YEAR(data_envio) = YEAR(CURRENT_DATE())";

        $result = $this->queryOne($sql);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Busca lares que aceitam determinado tipo de animal
     */
    public function buscarPorTipoAnimal($tipoAnimal)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'ativo' 
                AND tipo_animal_aceita LIKE :tipo
                ORDER BY nome_completo ASC";

        return $this->query($sql, [':tipo' => '%' . $tipoAnimal . '%']);
    }
}
