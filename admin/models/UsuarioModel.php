<?php

require_once __DIR__ . '/Model.php';

/**
 * Model de Usuário
 * Gerencia operações relacionadas a usuários do sistema
 */
class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    /**
     * Busca usuário por username
     */
    public function buscarPorUsername($username)
    {
        return $this->findOne(['username' => $username]);
    }

    /**
     * Busca usuário por email
     */
    public function buscarPorEmail($email)
    {
        return $this->findOne(['email' => $email]);
    }

    /**
     * Verifica se o username já existe
     */
    public function usernameExiste($username, $excluirId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE username = :username";
        $params = [':username' => $username];

        if ($excluirId) {
            $sql .= " AND {$this->primaryKey} != :id";
            $params[':id'] = $excluirId;
        }

        $result = $this->queryOne($sql, $params);
        return (int) $result['total'] > 0;
    }

    /**
     * Verifica se o email já existe
     */
    public function emailExiste($email, $excluirId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email = :email";
        $params = [':email' => $email];

        if ($excluirId) {
            $sql .= " AND {$this->primaryKey} != :id";
            $params[':id'] = $excluirId;
        }

        $result = $this->queryOne($sql, $params);
        return (int) $result['total'] > 0;
    }

    /**
     * Cria um novo usuário
     */
    public function criar($dados)
    {
        // Validações
        if ($this->usernameExiste($dados['username'])) {
            throw new Exception('Username já está em uso');
        }

        if (!empty($dados['email']) && $this->emailExiste($dados['email'])) {
            throw new Exception('Email já está em uso');
        }

        $dadosUsuario = [
            'username' => $dados['username'],
            'password' => $dados['password'], // Deve vir já hasheado
            'email' => $dados['email'] ?? null,
            'nome_completo' => $dados['nome_completo'] ?? null,
            'nivel_acesso' => $dados['nivel_acesso'] ?? 'usuario',
            'ativo' => $dados['ativo'] ?? 1,
            'data_criacao' => date('Y-m-d H:i:s')
        ];

        return $this->insert($dadosUsuario);
    }

    /**
     * Atualiza senha do usuário
     */
    public function atualizarSenha($id, $novaSenhaHash)
    {
        return $this->update($id, [
            'password' => $novaSenhaHash,
            'data_atualizacao' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Atualiza último acesso
     */
    public function atualizarUltimoAcesso($id)
    {
        return $this->update($id, [
            'ultimo_acesso' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Lista usuários ativos
     */
    public function listarAtivos()
    {
        return $this->findAll(['ativo' => 1], 'nome_completo ASC');
    }

    /**
     * Desativa um usuário (soft delete)
     */
    public function desativar($id)
    {
        return $this->update($id, [
            'ativo' => 0,
            'data_atualizacao' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Ativa um usuário
     */
    public function ativar($id)
    {
        return $this->update($id, [
            'ativo' => 1,
            'data_atualizacao' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Estatísticas de usuários
     */
    public function getEstatisticas()
    {
        $stats = [
            'total' => $this->count(),
            'ativos' => $this->count(['ativo' => 1]),
            'inativos' => $this->count(['ativo' => 0])
        ];

        return $stats;
    }
}
