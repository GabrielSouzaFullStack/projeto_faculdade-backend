<?php

/**
 * Controller do Dashboard
 * Responsável por coletar estatísticas e dados do painel principal
 */

require_once __DIR__ . '/../security/session.php';
require_once __DIR__ . '/../config/database.php';

class DashboardController
{
    private $conn;

    public function __construct()
    {
        iniciar_sessao();
        verificar_cookie_sessao();
        exigir_login();

        $this->conn = conectar_db();
    }

    /**
     * Coleta estatísticas do sistema
     */
    public function getEstatisticas()
    {
        $stats = [
            'total_animais' => $this->getTotalAnimais(),
            'animais_disponiveis' => $this->getAnimaisDisponiveis(),
            'formularios_pendentes' => $this->getFormulariosPendentes(),
            'adocoes_mes' => $this->getAdocoesMes()
        ];

        return $stats;
    }

    /**
     * Total de animais cadastrados
     */
    private function getTotalAnimais()
    {
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM animais");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Animais disponíveis para adoção
     */
    private function getAnimaisDisponiveis()
    {
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM animais WHERE status = 'disponivel'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Formulários pendentes de análise
     */
    private function getFormulariosPendentes()
    {
        try {
            $stmt = $this->conn->query("
                SELECT 
                    (SELECT COUNT(*) FROM formularios_adocao WHERE status = 'pendente') +
                    (SELECT COUNT(*) FROM formularios_lar_temporario WHERE status = 'pendente') as total
            ");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Adoções realizadas no mês atual
     */
    private function getAdocoesMes()
    {
        try {
            $stmt = $this->conn->query("
                SELECT COUNT(*) as total 
                FROM animais
                WHERE status = 'adotado' 
                AND MONTH(data_atualizacao) = MONTH(CURRENT_DATE())
                AND YEAR(data_atualizacao) = YEAR(CURRENT_DATE())
            ");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Últimos animais cadastrados
     */
    public function getUltimosAnimais($limite = 5)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT id_animal, nome, tipo, raca, status, data_cadastro
                FROM animais
                ORDER BY data_cadastro DESC
                LIMIT :limite
            ");
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Últimos formulários recebidos
     */
    public function getUltimosFormularios($limite = 5)
    {
        try {
            $stmt = $this->conn->prepare("
                (SELECT 'adocao' as tipo, nome_completo, email, status, data_envio 
                 FROM formularios_adocao 
                 ORDER BY data_envio DESC LIMIT :limite1)
                UNION ALL
                (SELECT 'lar_temporario' as tipo, nome_completo, email, status, data_envio 
                 FROM formularios_lar_temporario 
                 ORDER BY data_envio DESC LIMIT :limite2)
                ORDER BY data_envio DESC
                LIMIT :limite3
            ");
            $stmt->bindValue(':limite1', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':limite2', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':limite3', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
