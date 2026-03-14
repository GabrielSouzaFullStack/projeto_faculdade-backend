<?php

/**
 * Classe Base para Models
 * Provê funcionalidades comuns para todos os modelos
 */

abstract class Model
{
    protected $conn;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct($conn = null)
    {
        if ($conn !== null) {
            $this->conn = $conn;
        }
    }

    /**
     * Define a conexão com o banco de dados
     */
    public function setConnection($conn)
    {
        $this->conn = $conn;
        return $this;
    }

    /**
     * Busca todos os registros
     */
    public function findAll($conditions = [], $orderBy = null)
    {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];

            if (!empty($conditions)) {
                $where = [];
                foreach ($conditions as $field => $value) {
                    $where[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $where);
            }

            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy}";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro em findAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um registro por ID
     */
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro em findById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Busca um registro por condições específicas
     */
    public function findOne($conditions = [])
    {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];

            if (!empty($conditions)) {
                $where = [];
                foreach ($conditions as $field => $value) {
                    $where[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $where);
            }

            $sql .= " LIMIT 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro em findOne: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Insere um novo registro
     */
    public function insert($data)
    {
        try {
            $fields = array_keys($data);
            $placeholders = array_map(function ($field) {
                return ":{$field}";
            }, $fields);

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            $stmt = $this->conn->prepare($sql);

            $params = [];
            foreach ($data as $field => $value) {
                $params[":{$field}"] = $value;
            }

            $result = $stmt->execute($params);

            if ($result) {
                return $this->conn->lastInsertId();
            }

            return false;
        } catch (Exception $e) {
            error_log("Erro em insert: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um registro
     */
    public function update($id, $data)
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            foreach ($data as $field => $value) {
                $fields[] = "{$field} = :{$field}";
                $params[":{$field}"] = $value;
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) .
                " WHERE {$this->primaryKey} = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Erro em update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta um registro
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log("Erro em delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Conta registros com condições
     */
    public function count($conditions = [])
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $params = [];

            if (!empty($conditions)) {
                $where = [];
                foreach ($conditions as $field => $value) {
                    $where[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $where);
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (Exception $e) {
            error_log("Erro em count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Executa uma query personalizada
     */
    protected function query($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro em query: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Executa uma query que retorna um único resultado
     */
    protected function queryOne($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro em queryOne: " . $e->getMessage());
            return null;
        }
    }
}
