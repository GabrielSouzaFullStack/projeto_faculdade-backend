# Como Criar Novas Páginas no Admin

Este guia mostra como criar novas páginas seguindo a estrutura modular do painel administrativo.

## 📋 Passo a Passo

### 1️⃣ Criar o Controller

Crie um arquivo em `controllers/` com o nome `nome-da-pagina-controller.php`:

```php
<?php
/**
 * Controller de [Nome da Página]
 * Descrição do que o controller faz
 */

require_once __DIR__ . '/../security/session.php';
require_once __DIR__ . '/../security/require-login.php';

class NomeDaPaginaController {

    public function __construct() {
        iniciar_sessao();
        require_login(); // Protege a página
    }

    /**
     * Método principal que processa a lógica
     */
    public function processar() {
        // Sua lógica aqui
        $dados = [
            'titulo' => 'Minha Página',
            'conteudo' => 'Dados processados'
        ];

        return $dados;
    }
}
```

### 2️⃣ Criar a View

Crie um arquivo em `views/` com o nome `nome-da-pagina-view.php`:

```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($dados['titulo']); ?> - Admin</title>

    <!-- CSS Global -->
    <link rel="stylesheet" href="assets/css/global.css">

    <!-- CSS da Página -->
    <link rel="stylesheet" href="assets/css/nome-da-pagina.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($dados['titulo']); ?></h1>

        <p><?php echo htmlspecialchars($dados['conteudo']); ?></p>
    </div>
</body>
</html>
```

### 3️⃣ Criar o CSS

Crie um arquivo em `assets/css/` com o nome `nome-da-pagina.css`:

```css
/* Estilos específicos da página */

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: var(--spacing-lg);
}

/* Aproveite as variáveis CSS do global.css */
.meu-componente {
  background: var(--white);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-md);
  padding: var(--spacing-lg);
}
```

### 4️⃣ Criar o Arquivo de Entrada

Crie um arquivo na raiz de `admin/` com o nome `nome-da-pagina.php`:

```php
<?php
/**
 * Página [Nome da Página]
 */

require_once __DIR__ . '/controllers/nome-da-pagina-controller.php';

// Instancia o controller
$controller = new NomeDaPaginaController();

// Processa a lógica
$dados = $controller->processar();

// Inclui a view
require_once __DIR__ . '/views/nome-da-pagina-view.php';
```

## 🎯 Exemplo Real: Página de Usuários

Vamos criar uma página para listar usuários:

### `controllers/usuarios-controller.php`

```php
<?php
require_once __DIR__ . '/../security/session.php';
require_once __DIR__ . '/../security/require-login.php';
require_once __DIR__ . '/../database/connection.php';

class UsuariosController {

    public function __construct() {
        iniciar_sessao();
        require_login();
    }

    public function listarUsuarios() {
        try {
            $conn = conectar_db();
            $stmt = $conn->query("SELECT id_usuario, login, id_perfil FROM wtz_usuarios");
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'titulo' => 'Gerenciar Usuários',
                'usuarios' => $usuarios,
                'total' => count($usuarios)
            ];
        } catch (Exception $e) {
            return [
                'titulo' => 'Gerenciar Usuários',
                'usuarios' => [],
                'erro' => 'Erro ao carregar usuários'
            ];
        }
    }
}
```

### `views/usuarios-view.php`

```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($dados['titulo']); ?></title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/usuarios.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($dados['titulo']); ?></h1>

        <?php if (isset($dados['erro'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($dados['erro']); ?>
            </div>
        <?php else: ?>
            <p>Total de usuários: <strong><?php echo $dados['total']; ?></strong></p>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Login</th>
                        <th>Perfil</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados['usuarios'] as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['login']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['id_perfil']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary">Editar</button>
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
```

### `assets/css/usuarios.css`

```css
.container {
  max-width: 1200px;
  margin: 2rem auto;
  padding: var(--spacing-lg);
}

.alert {
  padding: var(--spacing-md);
  border-radius: var(--border-radius);
  margin-bottom: var(--spacing-lg);
}

.alert-danger {
  background-color: var(--danger-light);
  color: var(--danger-color);
  border-left: 4px solid var(--danger-color);
}
```

### `usuarios.php`

```php
<?php
require_once __DIR__ . '/controllers/usuarios-controller.php';

$controller = new UsuariosController();
$dados = $controller->listarUsuarios();

require_once __DIR__ . '/views/usuarios-view.php';
```

## ✅ Checklist de Criação

- [ ] Controller criado em `controllers/`
- [ ] View criada em `views/`
- [ ] CSS criado em `assets/css/`
- [ ] Arquivo de entrada criado na raiz do admin
- [ ] Proteção de login adicionada (`require_login()`)
- [ ] Sanitização de outputs (`htmlspecialchars()`)
- [ ] Validação de inputs
- [ ] Tratamento de erros

## 🔐 Segurança

Sempre inclua:

1. **Proteção de sessão**: `iniciar_sessao()` e `require_login()`
2. **Sanitização de saída**: `htmlspecialchars()` em todos os dados exibidos
3. **Prepared statements**: Use PDO com placeholders
4. **Validação de input**: Valide todos os dados recebidos

## 🎨 Boas Práticas de CSS

1. Use as variáveis CSS definidas em `global.css`
2. Crie classes reutilizáveis
3. Evite CSS inline
4. Use naming consistente (kebab-case)
5. Mobile-first (media queries para desktop)

## 📚 Recursos Úteis

- Variáveis CSS: Ver `assets/css/global.css`
- Classes utilitárias: `.text-center`, `.mt-3`, `.btn`, etc.
- Exemplos: Ver implementação do `login.php`
