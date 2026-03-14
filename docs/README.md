# Painel Administrativo - ONG Amigos da Rua

## 📁 Estrutura de Pastas

A estrutura do painel administrativo foi organizada seguindo boas práticas de desenvolvimento web, com separação clara entre apresentação, lógica e recursos.

```
admin/
├── assets/                 # Recursos estáticos (CSS, JS, imagens)
│   ├── css/               # Arquivos de estilização
│   │   └── login.css      # Estilos da página de login
│   ├── img/               # Imagens do admin
│   │   └── logo-ong.png   # Logo da ONG
│   └── js/                # Scripts JavaScript
│
├── controllers/           # Controllers (lógica de negócio)
│   └── login-controller.php  # Controller de autenticação
│
├── views/                 # Views (apresentação)
│   └── login-view.php     # Template da página de login
│
├── security/              # Módulos de segurança
│   ├── authenticate.php   # Autenticação de usuários
│   ├── crypto.php         # Criptografia de senhas
│   ├── session.php        # Gerenciamento de sessões
│   └── require-login.php  # Proteção de rotas
│
├── database/              # Conexão com banco de dados
│   └── connection.php     # Gerenciador de conexões PDO
│
├── config/                # Configurações
│   └── database.php       # Configurações do banco de dados
│
├── login.php              # Ponto de entrada do login
└── index.php              # Dashboard principal
```

## 🎨 Padrão de Front-end

### CSS Modular

- Cada página/componente possui seu próprio arquivo CSS
- Variáveis CSS (`:root`) para facilitar customização de cores e estilos
- Design responsivo para mobile, tablet e desktop
- Animações suaves para melhor experiência do usuário

### Estrutura de Assets

- **assets/css/**: Todos os arquivos de estilização
- **assets/img/**: Imagens, logos e ícones
- **assets/js/**: Scripts JavaScript (quando necessário)

## 🏗️ Arquitetura MVC Simplificada

O sistema utiliza uma arquitetura baseada em MVC (Model-View-Controller):

- **Controllers**: Lógica de negócio e processamento de requisições
- **Views**: Templates HTML/PHP para apresentação
- **Models**: Módulos de segurança e database (camada de dados)

### Exemplo de Fluxo (Login)

1. `login.php` instancia o **LoginController**
2. **LoginController** processa a autenticação usando módulos de security
3. `login-view.php` é renderizada com os dados processados
4. CSS em `assets/css/login.css` estiliza a apresentação

## 🔒 Segurança

- Senhas criptografadas com OpenSSL (AES-128-CBC)
- Proteção contra SQL Injection (PDO com prepared statements)
- Sanitização de inputs com `htmlspecialchars()`
- Gerenciamento seguro de sessões
- **Sessão Persistente ("Lembrar-me")**: Tokens seguros com expiração de 30 dias
  - Cookies HttpOnly e SameSite para prevenir XSS e CSRF
  - Tokens armazenados com hash SHA-256
  - Validação de token e expiração a cada acesso

### 🔐 Funcionalidade "Lembrar-me"

O sistema implementa sessão persistente de forma segura:

1. **Checkbox no Login**: Usuário pode marcar "Lembrar-me neste dispositivo"
2. **Token Único**: Sistema gera token aleatório de 64 caracteres
3. **Armazenamento Seguro**: Token é hasheado (SHA-256) e armazenado em arquivo protegido
4. **Cookies Seguros**: HttpOnly, SameSite=Strict, expiração de 30 dias
5. **Validação Automática**: A cada acesso, verifica token e restaura sessão
6. **Logout Completo**: Remove cookies e arquivos de token

**Arquivos envolvidos:**

- `views/login-view.php`: Checkbox "Lembrar-me"
- `controllers/login-controller.php`: Processa opção de lembrar
- `security/session.php`: Funções de gerenciamento de cookies e tokens
- `.tokens/`: Pasta protegida com tokens (nunca commitar no Git!)
- `logout.php`: Encerra sessão e remove cookies

## 🎯 Benefícios da Estrutura

1. **Manutenibilidade**: Código organizado e fácil de localizar
2. **Escalabilidade**: Fácil adicionar novos controllers e views
3. **Reutilização**: Componentes podem ser reutilizados
4. **Separação de Responsabilidades**: Cada módulo tem uma função específica
5. **Profissionalismo**: Estrutura padrão da indústria

## 🚀 Próximos Passos

Para criar novas páginas no admin, siga o padrão:

1. Crie o **controller** em `controllers/`
2. Crie a **view** em `views/`
3. Crie o **CSS** em `assets/css/`
4. Crie o arquivo de entrada que integra controller + view

## 📝 Convenções de Nomenclatura

- Arquivos PHP: `kebab-case.php` (ex: `login-controller.php`)
- Classes PHP: `PascalCase` (ex: `LoginController`)
- Arquivos CSS: `kebab-case.css` (ex: `login.css`)
- Classes CSS: `kebab-case` (ex: `.login-container`)
