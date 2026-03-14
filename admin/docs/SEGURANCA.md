# DOCUMENTAÇÃO DE SEGURANÇA

## Projeto ONG Amigos da Rua

### 🔒 Medidas de Segurança Implementadas

#### 1. Proteção contra SQL Injection

✅ **PDO com Prepared Statements**

- Todos os acessos ao banco usam PDO com prepared statements
- Parâmetros são vinculados separadamente (`:parametro`)
- `PDO::ATTR_EMULATE_PREPARES => false` para segurança máxima

#### 2. Proteção contra XSS (Cross-Site Scripting)

✅ **Sanitização de Entrada**

- `strip_tags()` remove tags HTML/PHP de todos os inputs
- `htmlspecialchars()` converte caracteres especiais em entidades HTML
- Funções dedicadas: `sanitize()`, `sanitizeAttr()`, `sanitizeUrl()`

✅ **Validação de Saída**

- Todos os dados exibidos passam por `sanitize()` antes de renderização
- Headers de segurança configurados (X-XSS-Protection)

#### 3. Validação de Dados

✅ **Validações Implementadas**

- **Nome**: Apenas letras, espaços e acentos (mínimo 3 caracteres)
- **CPF**: Validação completa com dígito verificador
- **Telefone**: Formato brasileiro (10-11 dígitos)
- **Email**: Validação com `FILTER_VALIDATE_EMAIL`
- **Texto Livre**: Limite de caracteres (1000 máximo)
- **Checkboxes/Radios**: Apenas valores pré-definidos são aceitos

#### 4. Proteção contra CSRF (Cross-Site Request Forgery)

✅ **Implementado via Rate Limiting**

- Limite de 1 submissão por IP a cada 60 segundos
- Previne flooding e ataques automatizados

#### 5. Rate Limiting / Proteção contra Spam

✅ **Controle de Taxa de Submissão**

- Arquivo de cache por IP (MD5)
- Tempo mínimo de 60 segundos entre submissões
- Limpeza automática de arquivos antigos (1 hora)
- Mensagem amigável ao usuário informando tempo de espera

#### 6. Validação de Tamanho de Dados

✅ **Limites Implementados**

- POST máximo: 1MB (previne ataques de negação de serviço)
- Campos de texto: limites específicos por tipo
  - Nome: 200 caracteres
  - Email: 150 caracteres
  - Telefone: 20 caracteres
  - Endereço: 255 caracteres
  - Observações: 1000 caracteres

#### 7. Proteção de Arquivos Sensíveis

✅ **Arquivos .htaccess**

- `/includes/.htaccess` - Bloqueia acesso direto aos scripts PHP
- `/cache/.htaccess` - Protege arquivos de cache
- `/logs/.htaccess` - Protege logs de segurança
- `/.htaccess` - Headers de segurança globais

✅ **Diretórios Protegidos**

- `/includes/` - Scripts PHP não acessíveis via HTTP
- `/cache/` - Arquivos temporários protegidos
- `/logs/` - Logs de segurança protegidos

#### 8. Headers de Segurança HTTP

✅ **Headers Configurados**

```
X-XSS-Protection: 1; mode=block
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
Content-Security-Policy: default-src 'self'
```

#### 9. Logging de Segurança

✅ **Registro de Atividades Suspeitas**

- Tentativas de acesso com métodos inválidos
- Tentativas de SQL Injection via URL
- Logs diários em `/logs/seguranca_YYYY-MM-DD.log`
- Informações registradas: IP, User-Agent, timestamp, ação

#### 10. Boas Práticas de Código

✅ **Implementações**

- Separação de responsabilidades (database, validação, processamento)
- Tratamento de exceções com mensagens amigáveis
- Validação de tipos de dados
- Funções reutilizáveis e testáveis
- Comentários e documentação do código

---

### 📁 Estrutura de Arquivos de Segurança

```
projeto_faculdade/
├── includes/
│   ├── database.php          # Conexão PDO segura
│   ├── validacao.php          # Funções de validação e sanitização
│   ├── processar-adocao.php   # Processamento seguro do formulário
│   ├── processar-lar-temporario.php
│   └── .htaccess              # Proteção de acesso direto
├── cache/
│   └── .htaccess              # Proteção de cache
├── logs/
│   └── .htaccess              # Proteção de logs
└── .htaccess                  # Configurações globais de segurança
```

---

### 🔍 Funções de Validação Disponíveis

#### `validacao.php`

- `sanitizarString($string, $maxLength)` - Sanitiza strings
- `validarEmail($email)` - Valida e sanitiza email
- `validarTelefone($telefone)` - Valida telefone brasileiro
- `validarCPF($cpf)` - Valida CPF com dígito verificador
- `validarNome($nome)` - Valida nome completo
- `validarEndereco($endereco)` - Valida endereço
- `validarBooleano($valor)` - Valida Sim/Não
- `validarTextoLivre($texto, $maxLength)` - Valida texto livre
- `validarCheckboxArray($valores, $permitidos)` - Valida checkboxes
- `validarRadio($valor, $permitidos)` - Valida radio buttons
- `verificarRateLimit()` - Controla taxa de submissão
- `validarTamanhoPost()` - Verifica tamanho do POST
- `registrarAtividadeSuspeita($msg)` - Registra em log

#### `database.php`

- `sanitize($string)` - Previne XSS na saída HTML
- `sanitizeAttr($string)` - Sanitiza atributos HTML
- `sanitizeUrl($url)` - Valida e sanitiza URLs

---

### ⚠️ Considerações de Segurança Adicionais

#### Para Produção:

1. **Alterar credenciais do banco de dados** em `includes/database.php`
2. **Habilitar HTTPS** (SSL/TLS) para criptografar dados em trânsito
3. **Configurar permissões de arquivos**:
   - Arquivos PHP: 644
   - Diretórios: 755
   - Arquivos sensíveis: 600
4. **Remover mensagens de erro detalhadas** em produção
5. **Implementar backup automático** do banco de dados
6. **Monitorar logs regularmente** em `/logs/`
7. **Atualizar PHP e extensões** para versões mais recentes
8. **Considerar Web Application Firewall (WAF)**

#### Não Implementado (mas recomendado):

- [ ] Tokens CSRF por sessão (complexo para formulário público)
- [ ] Captcha/reCAPTCHA (pode atrapalhar UX)
- [ ] Autenticação de dois fatores (apenas para admin)
- [ ] Criptografia adicional de dados sensíveis
- [ ] Auditoria completa de banco de dados

---

### 🧪 Como Testar a Segurança

#### Teste de SQL Injection:

```
# Tentar submeter como nome:
' OR '1'='1
admin'--
' DROP TABLE formularios_adocao--
```

✅ Resultado esperado: Valores são sanitizados e tratados como string

#### Teste de XSS:

```
# Tentar submeter como nome:
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
```

✅ Resultado esperado: Tags são removidas ou convertidas

#### Teste de Rate Limiting:

```
# Submeter formulário duas vezes em 60 segundos
```

✅ Resultado esperado: Segunda submissão é bloqueada

#### Teste de Validação:

```
# Tentar submeter:
- CPF inválido: 123.456.789-00
- Email inválido: teste@
- Telefone curto: 123456
```

✅ Resultado esperado: Mensagens de erro específicas

---

### 📞 Contato de Segurança

Se você encontrar uma vulnerabilidade de segurança, por favor:

1. **NÃO** divulgue publicamente
2. Contate o desenvolvedor imediatamente
3. Forneça detalhes para reprodução
4. Aguarde correção antes de divulgar

---

**Última atualização**: 6 de março de 2026  
**Versão**: 1.0.0  
**Desenvolvedor**: [Seu Nome]
