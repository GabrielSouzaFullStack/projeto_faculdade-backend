<?php

require_once __DIR__ . '/Model.php';

/**
 * Model de Formulário de Adoção
 * Gerencia operações relacionadas a formulários de adoção
 */
class FormularioAdocaoModel extends Model
{
    protected $table = 'formularios_adocao';
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
     * Cria um novo formulário de adoção
     */
    public function criar($dados)
    {
        $dadosFormulario = [
            'nome_completo' => $dados['nome_completo'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'cpf' => $dados['cpf'] ?? null,
            'endereco' => $dados['endereco'] ?? null,
            'tem_criancas' => $dados['tem_criancas'] ?? 0,
            'tem_outros_animais' => $dados['tem_outros_animais'] ?? 0,
            'quais_animais' => $dados['quais_animais'] ?? null,
            'animais_castrados' => $dados['animais_castrados'] ?? 0,
            'animal_preferencia' => $dados['animal_preferencia'] ?? null,
            'onde_animal_fica' => $dados['onde_animal_fica'] ?? null,
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
        $statusPermitidos = ['pendente', 'em_analise', 'aprovado', 'reprovado', 'concluido'];

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
     * Estatísticas de formulários
     */
    public function getEstatisticas()
    {
        $stats = [
            'total' => $this->count(),
            'pendentes' => $this->count(['status' => 'pendente']),
            'em_analise' => $this->count(['status' => 'em_analise']),
            'aprovados' => $this->count(['status' => 'aprovado']),
            'reprovados' => $this->count(['status' => 'reprovado']),
            'concluidos' => $this->count(['status' => 'concluido'])
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
}
