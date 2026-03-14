# 🚀 Guia de Instalação do Dashboard Admin

## 📋 Pré-requisitos

- XAMPP (Apache + MySQL + PHP 7.4+)
- Navegador web moderno
- Acesso ao banco de dados MySQL

## 🛠️ Instalação

### 1️⃣ Criar as Tabelas no Banco de Dados

Execute o arquivo SQL no seu banco de dados:

```sql
-- Acesse o phpMyAdmin ou MySQL
-- Selecione o banco: ong_amigos_rua
-- Execute o arquivo: admin/database/schema.sql
```

Ou via terminal:

```bash
cd C:\xampp\htdocs\projeto_faculdade\admin
mysql -u root -p ong_amigos_rua < database/schema.sql
```

### 2️⃣ Configurar Permissões de Pastas

As pastas `.tokens` e `uploads` já foram criadas automaticamente.

Verifique se têm permissão de escrita:

```bash
# No Windows (PowerShell):
icacls .tokens /grant Everyone:F
icacls uploads /grant Everyone:F
```

### 3️⃣ Acessar o Painel

1. Inicie o XAMPP (Apache e MySQL)
2. Acesse: `http://localhost/projeto_faculdade/admin/login.php`
3. Faça login com suas credenciais cadastradas na tabela `wtz_usuarios`

## 📁 Estrutura Criada

```
admin/
├── assets/
│   ├── css/
│   │   ├── global.css       → Estilos globais reutilizáveis
│   │   ├── login.css        → Estilos do login
│   │   ├── dashboard.css    → Estilos do dashboard
│   │   └── animais.css      → Estilos do CRUD de animais
│   └── img/
│       └── logo-ong.png     → Logo da ONG
│
├── controllers/
│   ├── login-controller.php      → Autenticação
│   ├── dashboard-controller.php  → Estatísticas
│   └── animais-controller.php    → CRUD de animais
│
├── views/
│   ├── layout-header.php         → Header com menu lateral
│   ├── layout-footer.php         → Footer do layout
│   ├── login-view.php            → Template de login
│   ├── dashboard-view.php        → Dashboard principal
│   ├── animais-lista-view.php    → Lista de animais
│   └── animais-form-view.php     → Formulário de animal
│
├── database/
│   ├── connection.php     → Conexão PDO
│   └── schema.sql         → Estrutura das tabelas
│
├── security/
│   ├── session.php        → Gerenciamento de sessões + cookies
│   ├── authenticate.php   → Autenticação de usuários
│   ├── crypto.php         → Criptografia de senhas
│   └── require-login.php  → Proteção de rotas
│
├── uploads/               → Fotos dos animais (protegida)
├── .tokens/              → Tokens de sessão (protegida)
│
├── index.php                        → Dashboard principal
├── login.php                        → Página de login
├── logout.php                       → Logout
├── animais.php                      → Gerenciamento de animais
├── formularios-adocao.php           → Formulários de adoção
└── formularios-lar-temporario.php   → Formulários de lar temp.
```

## 🎯 Funcionalidades Implementadas

### ✅ Dashboard Principal

- Cards com estatísticas em tempo real
- Últimos animais cadastrados
- Últimos formulários recebidos
- Navegação intuitiva

### ✅ CRUD de Animais

- **Listar**: Grid responsivo com cards visuais
- **Filtros**: Por tipo, status e busca textual
- **Cadastrar**: Formulário completo com upload de foto
- **Editar**: Atualização de dados e foto
- **Excluir**: Remoção com confirmação

### ✅ Formulários

- Visualização de formulários de adoção
- Visualização de formulários de lar temporário
- Filtros por status
- Interface preparada para expansão

### ✅ Sistema de Login

- Autenticação segura
- "Lembrar-me" com cookies (30 dias)
- Logout completo
- Proteção contra XSS e CSRF

### ✅ Menu Lateral

- Navegação intuitiva
- Links ativos destacados
- Informações do usuário
- Botão de logout
- Responsivo para mobile

## 🎨 Design

- **Layout moderno** com gradientes e sombras
- **Totalmente responsivo** (mobile, tablet, desktop)
- **Componentes reutilizáveis** (badges, cards, tabelas)
- **Variáveis CSS** para fácil customização
- **Animações suaves** para melhor UX

## 🔐 Segurança

- ✅ Prepared statements (SQL Injection)
- ✅ Password hashing (OpenSSL AES-128-CBC)
- ✅ CSRF protection (SameSite cookies)
- ✅ XSS protection (htmlspecialchars)
- ✅ Session management seguro
- ✅ Pastas protegidas (.htaccess)
- ✅ Upload validation (tipo e tamanho)

## 📝 Como Usar

### Cadastrar um Animal:

1. Acesse "Gerenciar Animais" no menu
2. Clique em "Cadastrar Novo Animal"
3. Preencha o formulário (campos obrigatórios marcados com \*)
4. Faça upload de uma foto (opcional)
5. Clique em "Cadastrar Animal"

### Editar um Animal:

1. Na lista de animais, clique em "Editar"
2. Atualize as informações desejadas
3. Clique em "Salvar Alterações"

### Filtrar Animais:

1. Use os campos de filtro no topo da lista
2. Selecione tipo, status ou busque por nome/raça
3. Clique em "Filtrar"

### Visualizar Formulários:

1. Acesse "Formulários de Adoção" ou "Lares Temporários"
2. Visualize a lista de formulários recebidos
3. Clique em "Ver Detalhes" (em desenvolvimento)

## 🚧 Próximos Passos (Opcional)

### Funcionalidades Sugeridas:

1. **Detalhamento de Formulários**: View completa com todas as informações
2. **Atualização de Status**: Aprovar/Recusar formulários
3. **Sistema de Notificações**: Alertas de novos formulários
4. **Relatórios**: Gráficos de adoções por mês
5. **Múltiplas Fotos**: Galeria de fotos por animal
6. **Integração com Front**: API REST para exibir animais no site
7. **Histórico**: Log de alterações nos animais
8. **Busca Avançada**: Filtros mais detalhados

## 🐛 Solução de Problemas

### Erro ao fazer login:

- Verifique se o banco de dados `ong_amigos_rua` existe
- Confirme que a tabela `wtz_usuarios` tem registros
- Verifique as credenciais em `config/database.php`

### Erro ao fazer upload de foto:

- Verifique permissões da pasta `uploads`
- Confirme que o arquivo é uma imagem válida
- Verifique tamanho máximo (5MB)

### Menu lateral não aparece:

- Limpe o cache do navegador
- Verifique se os arquivos CSS estão carregando
- Inspecione o console do navegador (F12)

### Sessão não persiste:

- Verifique permissões da pasta `.tokens`
- Confirme que os cookies estão habilitados
- Verifique se `session_start()` está funcionando

## 📞 Suporte

Para dúvidas ou problemas:

- Consulte a documentação em `/admin/README.md`
- Veja exemplos em `/admin/COMO-CRIAR-PAGINAS.md`
- Leia sobre "Lembrar-me" em `/admin/FUNCIONALIDADE-LEMBRAR-ME.md`

## 🎉 Pronto!

Seu dashboard administrativo está instalado e funcionando! Explore as funcionalidades e customize conforme suas necessidades.

**Boa gestão! 🐾❤️**
