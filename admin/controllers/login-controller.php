<?php

/**
 * Controller de Login
 * Responsável por processar a autenticação do usuário
 */

require_once __DIR__ . '/../security/authenticate.php';
require_once __DIR__ . '/../security/session.php';

class LoginController
{
    private $erro = '';

    public function __construct()
    {
        iniciar_sessao();
    }

    /**
     * Verifica se o usuário já está logado
     */
    public function verificarUsuarioLogado()
    {
        // Verifica se existe cookie de sessão persistente
        verificar_cookie_sessao();

        if (usuario_esta_logado()) {
            header('Location: index.php');
            exit();
        }
    }

    /**
     * Processa o formulário de login
     */
    public function processarLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'], $_POST['password'])) {
            $login = trim((string) $_POST['usuario']);
            $senha = (string) $_POST['password'];

            // Validação básica
            if (empty($login) || empty($senha)) {
                $this->erro = 'Por favor, preencha todos os campos.';
                return false;
            }

            // Tenta autenticar
            $usuario = autenticar_usuario($login, $senha);

            if ($usuario) {
                // Verifica se o usuário quer ser lembrado
                $lembrarSessao = isset($_POST['lembrar_sessao']) && $_POST['lembrar_sessao'] === '1';

                definir_usuario_logado($usuario, $lembrarSessao);

                // Redireciona para o dashboard
                header('Location: index.php');
                exit();
            } else {
                $this->erro = 'Usuário ou senha inválidos.';
                return false;
            }
        }

        return false;
    }

    /**
     * Verifica se há erro via GET
     */
    public function verificarErroGet()
    {
        if (isset($_GET['erro'])) {
            $this->erro = 'Login não encontrado! Favor inserir os dados novamente.';
        }
    }

    /**
     * Retorna a mensagem de erro
     */
    public function getErro()
    {
        return $this->erro;
    }

    /**
     * Verifica se há erro
     */
    public function temErro()
    {
        return !empty($this->erro);
    }
}
