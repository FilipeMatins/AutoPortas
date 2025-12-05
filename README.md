# Auto Portas - Sistema de Gestão

Sistema de gestão completo para empresa de portas automáticas, desenvolvido em PHP com arquitetura MVC.

## 📁 Estrutura do Projeto

```
AutoPortas/
├── app/
│   ├── Controllers/          # Controllers da aplicação
│   │   ├── HomeController.php
│   │   ├── ClienteController.php
│   │   ├── ServicoController.php
│   │   └── OrcamentoController.php
│   ├── Models/               # Models (acesso ao banco)
│   │   ├── Cliente.php
│   │   ├── Servico.php
│   │   └── Orcamento.php
│   └── Views/                # Views (templates)
│       ├── layouts/
│       │   └── main.php
│       ├── home/
│       ├── clientes/
│       ├── servicos/
│       ├── orcamentos/
│       └── errors/
├── config/
│   ├── app.php               # Configurações da aplicação
│   └── database.php          # Configurações do banco
├── core/
│   ├── App.php               # Classe principal
│   ├── Router.php            # Sistema de rotas
│   ├── Controller.php        # Controller base
│   ├── Model.php             # Model base
│   ├── Database.php          # Conexão PDO
│   └── helpers.php           # Funções auxiliares
├── database/
│   └── autoportas.sql        # Script do banco de dados
├── public/
│   ├── css/
│   │   └── style.css         # Estilos principais
│   ├── js/
│   │   └── app.js            # JavaScript principal
│   ├── img/                  # Imagens (opcional)
│   ├── index.php             # Ponto de entrada
│   └── .htaccess             # Configuração Apache
└── routes/
    └── web.php               # Definição das rotas
```

## 🚀 Instalação

### Requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache com mod_rewrite habilitado
- XAMPP, WAMP, LAMP ou servidor similar

### Passo a Passo

1. **Clone ou copie o projeto** para o diretório do seu servidor web:
   ```
   C:\xampp\htdocs\AutoPortas (Windows/XAMPP)
   /var/www/html/AutoPortas (Linux/Apache)
   ```

2. **Crie o banco de dados** executando o script SQL:
   ```sql
   -- No phpMyAdmin ou terminal MySQL:
   SOURCE database/autoportas.sql;
   ```
   
   Ou execute manualmente o conteúdo do arquivo `database/autoportas.sql`

3. **Configure a conexão** com o banco em `config/database.php`:
   ```php
   return [
       'driver' => 'mysql',
       'host' => 'localhost',
       'port' => 3306,
       'database' => 'autoportas_db',
       'username' => 'root',      // Seu usuário
       'password' => '',          // Sua senha
       // ...
   ];
   ```

4. **Configure a URL base** em `config/app.php`:
   ```php
   'base_url' => 'http://localhost/AutoPortas/public',
   ```

5. **Acesse o sistema** no navegador:
   ```
   http://localhost/AutoPortas/public
   ```

## ✨ Funcionalidades

### Dashboard
- Visão geral com estatísticas
- Últimos orçamentos
- Últimos clientes cadastrados
- Ações rápidas

### Clientes
- Listagem com paginação
- Cadastro com validação
- Edição de dados
- Visualização detalhada
- Exclusão

### Serviços
- Catálogo de serviços
- Categorização (Instalação, Manutenção, Reparo, etc.)
- Preços base
- Tempo estimado
- Status ativo/inativo

### Orçamentos
- Criação vinculada a cliente
- Seleção de serviços com quantidades
- Cálculo automático de valores
- Descontos
- Formas de pagamento
- Controle de status (Pendente, Aprovado, Rejeitado, Em Execução, Concluído)
- Geração de PDF para impressão
- Filtros por status

## 🛠️ Tecnologias

- **Backend:** PHP 7.4+ (MVC puro, sem framework)
- **Banco de Dados:** MySQL com PDO
- **Frontend:** HTML5, CSS3 (design moderno e responsivo)
- **JavaScript:** Vanilla JS (máscaras, validações, interatividade)
- **Ícones:** Bootstrap Icons
- **Fonte:** Outfit (Google Fonts)

## 📝 Rotas Disponíveis

```
GET  /                          Dashboard
GET  /clientes                  Lista de clientes
GET  /clientes/novo             Form novo cliente
POST /clientes                  Salvar cliente
GET  /clientes/{id}             Ver cliente
GET  /clientes/{id}/editar      Form editar cliente
POST /clientes/{id}             Atualizar cliente
POST /clientes/{id}/excluir     Excluir cliente

GET  /servicos                  Lista de serviços
GET  /servicos/novo             Form novo serviço
POST /servicos                  Salvar serviço
GET  /servicos/{id}             Ver serviço
GET  /servicos/{id}/editar      Form editar serviço
POST /servicos/{id}             Atualizar serviço
POST /servicos/{id}/excluir     Excluir serviço

GET  /orcamentos                Lista de orçamentos
GET  /orcamentos/novo           Form novo orçamento
POST /orcamentos                Salvar orçamento
GET  /orcamentos/{id}           Ver orçamento
GET  /orcamentos/{id}/editar    Form editar orçamento
POST /orcamentos/{id}           Atualizar orçamento
POST /orcamentos/{id}/status    Alterar status
POST /orcamentos/{id}/excluir   Excluir orçamento
GET  /orcamentos/{id}/pdf       Gerar PDF
```

## 🎨 Design

O sistema possui um design moderno com:
- Sidebar fixa para navegação
- Layout responsivo (desktop e mobile)
- Tema escuro na sidebar com cores vibrantes
- Cards com sombras suaves
- Formulários bem organizados
- Tabelas com hover effects
- Badges coloridos para status
- Animações sutis

## 🔒 Segurança

- Proteção contra SQL Injection (PDO prepared statements)
- Proteção contra XSS (escape de output)
- Tokens CSRF em formulários
- Validação de dados no servidor
- Headers de segurança no .htaccess

## 📄 Licença

Este projeto é de uso livre para fins educacionais e comerciais.

---

Desenvolvido com ❤️ para Auto Portas
