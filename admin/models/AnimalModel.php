<?php

require_once __DIR__ . '/Model.php';

/**
 * Model de Animal
 * Gerencia operações relacionadas a animais
 */
class AnimalModel extends Model
{
    protected $table = 'animais';
    protected $primaryKey = 'id_animal';

    /**
     * Lista animais com filtros
     */
    public function listarComFiltros($filtros = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
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

        return $this->query($sql, $params);
    }

    /**
     * Conta animais por status
     */
    public function contarPorStatus($status = 'disponivel')
    {
        return $this->count(['status' => $status]);
    }

    /**
     * Busca animais disponíveis para adoção
     */
    public function buscarDisponiveis($tipo = null)
    {
        $conditions = ['status' => 'disponivel'];

        if ($tipo) {
            $conditions['tipo'] = $tipo;
        }

        return $this->findAll($conditions, 'data_cadastro DESC');
    }

    /**
     * Atualiza status do animal
     */
    public function atualizarStatus($id, $novoStatus)
    {
        return $this->update($id, ['status' => $novoStatus]);
    }

    /**
     * Busca animais recentemente adicionados
     */
    public function buscarRecentes($limite = 5)
    {
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY data_cadastro DESC 
                LIMIT :limite";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Estatísticas de animais
     */
    public function getEstatisticas()
    {
        $stats = [
            'total' => $this->count(),
            'disponiveis' => $this->count(['status' => 'disponivel']),
            'adotados' => $this->count(['status' => 'adotado']),
            'tratamento' => $this->count(['status' => 'tratamento'])
        ];

        return $stats;
    }

    /**
     * Busca por múltiplos IDs
     */
    public function buscarPorIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} IN ({$placeholders})";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
