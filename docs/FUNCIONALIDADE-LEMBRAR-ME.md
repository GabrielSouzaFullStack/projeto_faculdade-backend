# Funcionalidade "Lembrar-me" - Guia de Uso

## ✨ Funcionalidade Implementada

A funcionalidade "Lembrar-me" permite que os usuários permaneçam conectados por **30 dias** sem precisar fazer login novamente, mesmo após fechar o navegador.

## 🎯 Como Usar

### Para o Usuário Final:

1. Acesse a página de login: `admin/login.php`
2. Digite seu usuário e senha
3. ✅ **Marque o checkbox "Lembrar-me neste dispositivo"**
4. Clique em "Entrar"

O sistema criará cookies seguros que manterão você conectado por 30 dias.

### Para Fazer Logout:

Acesse `admin/logout.php` ou crie um link de logout em suas páginas:

```html
<a href="logout.php">Sair</a>
```

## 🔐 Segurança Implementada

### 1. Token Único

- Cada sessão gera um token aleatório único de 64 caracteres
- Token é hasheado com SHA-256 antes de ser armazenado

### 2. Cookies Seguros

```php
'httponly' => true,     // Previne acesso via JavaScript (XSS)
'samesite' => 'Strict'  // Previne CSRF
'expires' => 30 dias    // Expira automaticamente
```

### 3. Validação Automática

- A cada acesso, o sistema verifica:
  - ✓ Existência dos cookies
  - ✓ Validade do token
  - ✓ Expiração temporal
  - ✓ Correspondência com o hash armazenado
  - ✓ Existência do usuário no banco

### 4. Proteção da Pasta .tokens

- `.htaccess`: Nega acesso direto via web
- `index.php`: Retorna 403 se acessado diretamente
- `.gitignore`: Previne commit dos tokens no Git

## 📁 Arquivos Modificados/Criados

### Modificados:

- `views/login-view.php` - Adicionado checkbox
- `controllers/login-controller.php` - Processa checkbox
- `security/session.php` - Gerenciamento de cookies e tokens

### Criados:

- `logout.php` - Página de logout completo
- `.tokens/` - Pasta para armazenar tokens
- `.tokens/.htaccess` - Proteção da pasta
- `.tokens/index.php` - Previne listagem
- `.gitignore` - Ignora tokens no Git

## 🔄 Fluxo de Funcionamento

### Login com "Lembrar-me":

```
1. Usuário marca checkbox →
2. Controller processa login →
3. Cria token aleatório →
4. Armazena hash do token em arquivo →
5. Define cookies no navegador →
6. Usuário permanece logado
```

### Próximo Acesso:

```
1. Usuário acessa qualquer página protegida →
2. Sistema verifica cookies →
3. Valida token com arquivo →
4. Restaura sessão automaticamente →
5. Usuário acessa sem login
```

### Logout:

```
1. Usuário acessa logout.php →
2. Remove arquivo de token →
3. Remove cookies do navegador →
4. Destrói sessão PHP →
5. Redireciona para login
```

## 📝 Exemplo de Código

### Proteger uma página:

```php
<?php
require_once __DIR__ . '/security/session.php';

iniciar_sessao();

// Verifica cookie de sessão persistente
verificar_cookie_sessao();

// Exige login
exigir_login();

// Se chegou aqui, o usuário está autenticado!
?>
```

### Adicionar link de logout:

```php
<nav>
    <a href="index.php">Dashboard</a>
    <a href="usuarios.php">Usuários</a>
    <a href="logout.php" class="btn btn-danger">Sair</a>
</nav>
```

## ⚠️ Considerações de Segurança

### ✅ Boas Práticas Implementadas:

- Tokens aleatórios criptograficamente seguros
- Hash SHA-256 dos tokens armazenados
- Cookies HttpOnly (previne XSS)
- SameSite=Strict (previne CSRF)
- Expiração automática (30 dias)
- Validação rigorosa a cada acesso

### 🔒 Recomendações Adicionais:

1. **Produção**: Migrar armazenamento de tokens do filesystem para banco de dados
2. **HTTPS**: Em produção, adicionar `'secure' => true` aos cookies (requer SSL)
3. **Rotação de Tokens**: Implementar regeneração periódica de tokens
4. **Logs**: Adicionar logging de tentativas de validação de token
5. **Limite de Dispositivos**: Limitar número de tokens ativos por usuário

## 🧪 Como Testar

1. **Teste Básico**:
   - Faça login marcando "Lembrar-me"
   - Feche o navegador completamente
   - Abra novamente e acesse `admin/index.php`
   - ✅ Deve estar logado automaticamente

2. **Teste de Logout**:
   - Faça login com "Lembrar-me"
   - Acesse `admin/logout.php`
   - Tente acessar `admin/index.php` novamente
   - ✅ Deve redirecionar para login

3. **Teste de Segurança**:
   - Delete o arquivo `.tokens/[id_usuario].token`
   - Acesse uma página protegida
   - ✅ Deve redirecionar para login (token inválido)

## 📊 Estrutura de Arquivos de Token

Cada token é armazenado em:

```
.tokens/[id_usuario].token
```

Conteúdo do arquivo:

```
[hash_sha256_do_token]|[timestamp_expiracao]
```

Exemplo:

```
a3f5b8c9d2e1...4f6a|1741564800
```

## 🚀 Melhorias Futuras (Opcional)

1. **Dashboard de Sessões**: Página para visualizar dispositivos conectados
2. **Revogação Manual**: Botão para revogar sessões de outros dispositivos
3. **Notificações**: Alertas de novos logins
4. **Geolocalização**: Registrar IP/localização dos logins
5. **2FA**: Autenticação de dois fatores
6. **Migrar para BD**: Armazenar tokens em tabela do banco de dados

## 💡 Dicas

- Os tokens expiram automaticamente após 30 dias
- Cada logout remove o token permanentemente
- Os arquivos de token são protegidos contra acesso web
- Não commite a pasta `.tokens/` no Git (já está no .gitignore)
