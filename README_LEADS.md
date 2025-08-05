# Sistema de Captação de Leads - Indemepreendimentos

Este documento descreve o sistema completo de captação de leads implementado no site Indemepreendimentos.

## 🎯 Funcionalidades Implementadas

### 1. **Captação Automática de Leads**
- ✅ Todos os formulários do front-end capturam leads automaticamente
- ✅ Armazenamento de dados flexível (JSON) para diferentes tipos de formulário
- ✅ Rastreamento de origem (URL, página, referrer)
- ✅ Captura de parâmetros UTM para análise de campanhas
- ✅ Registro de IP e User Agent

### 2. **Proteção CSRF**
- ✅ Tokens CSRF em todos os formulários
- ✅ Validação automática no backend
- ✅ Proteção contra ataques maliciosos

### 3. **Painel Administrativo**
- ✅ Listagem de leads com filtros avançados
- ✅ Visualização detalhada de cada lead
- ✅ Atualização de status (novo, contatado, convertido, perdido)
- ✅ Dashboard com métricas e gráficos
- ✅ Export para CSV com filtros

### 4. **Sistema de E-mail**
- ✅ Envio automático de emails para cada lead
- ✅ Templates personalizados por tipo de formulário
- ✅ Rastreamento de status de envio

## 📊 Estrutura do Banco de Dados

### Tabela `leads`
```sql
- id (PRIMARY KEY)
- source_url (URL de origem)
- source_page (página identificada)
- form_type (tipo do formulário)
- user_agent, ip_address, referrer
- nome, email, telefone, whatsapp, assunto, mensagem (campos principais)
- form_data (JSON com todos os dados do formulário)
- utm_source, utm_medium, utm_campaign, utm_term, utm_content
- status, email_sent, notes
- created_at, updated_at, deleted_at
```

### Tabela `csrf_tokens`
```sql
- id (PRIMARY KEY)
- token (hash único)
- form_type, ip_address
- used, expires_at, created_at
```

## 🔧 Arquivos Criados/Modificados

### Novos Arquivos:
1. `docs/alter_db/07_leads_system.sql` - Script de criação das tabelas
2. `src/Service/LeadService.php` - Serviço principal de leads
3. `src/Service/CsrfService.php` - Serviço de proteção CSRF
4. `src/Controller/Security/LeadsController.php` - Controller do painel administrativo
5. `src/Provider/LeadServiceProvider.php` - Registra os serviços no container
6. `views/security/leads/index.twig` - Listagem de leads
7. `views/security/leads/view.twig` - Visualização detalhada
8. `views/security/leads/dashboard.twig` - Dashboard com métricas
9. `views/emails/empreendimento_interesse.twig` - Template de email para empreendimentos
10. `views/emails/newsletter.twig` - Template de email para newsletter

### Arquivos Modificados:
1. `src/Controller/Front/HomeController.php` - Método unificado para processamento de formulários
2. `src/bootstrap.php` - Registro do LeadServiceProvider
3. `src/routes.php` - Rotas para envio de formulários
4. `src/routes_security.php` - Rotas do painel de leads
5. `views/containers/contato_empreendimentos_internas.twig` - Adicionado CSRF token
6. `views/front/contato.twig` - Adicionado CSRF token

## 🚀 Como Usar

### 1. **Executar Script do Banco**
```bash
# Execute o script SQL para criar as tabelas
mysql -u usuario -p nome_banco < docs/alter_db/07_leads_system.sql
```

### 2. **Acessar o Painel**
- URL: `/painel/leads`
- Dashboard: `/painel/leads/dashboard`
- Export CSV: `/painel/leads/export`

### 3. **Formulários do Front-end**
Todos os formulários agora enviam para:
- Contato: `POST /contato-send/`
- Empreendimentos: `POST /empreendimento-send/`

## 📈 Funcionalidades do Painel

### Dashboard de Leads
- **Métricas principais**: Total, hoje, mês atual, convertidos
- **Gráficos**: Por página de origem, por tipo de formulário
- **Timeline**: Leads dos últimos 30 dias

### Gestão de Leads
- **Filtros**: Por página, tipo de formulário, status, período
- **Ações**: Visualizar, atualizar status, excluir
- **Export**: CSV com todos os dados filtrados

### Visualização Detalhada
- **Dados pessoais**: Nome, email, telefone, WhatsApp
- **Informações de origem**: URL, página, IP, User Agent
- **Parâmetros UTM**: Source, Medium, Campaign, Term, Content
- **Dados completos**: JSON do formulário original
- **Ações**: Atualizar status, email direto, WhatsApp

## 🔒 Segurança

### CSRF Protection
- Tokens únicos por formulário
- Expiração automática (1 hora)
- Validação obrigatória no backend

### Validação de Dados
- Campos obrigatórios por tipo de formulário
- Sanitização de dados de entrada
- Logs de erro detalhados

## 📝 Tipos de Status

- **novo**: Lead recém capturado
- **contatado**: Equipe já entrou em contato
- **convertido**: Lead se tornou cliente
- **perdido**: Lead não demonstrou mais interesse

## 🔧 Configurações

### E-mail de Destino
Alterar em `src/Controller/Front/HomeController.php`:
```php
$message->setTo(array("pedro.palopoli@hotmail.com")); // Linha 437
```

### Templates de E-mail
- Contato: `views/emails/fale_conosco.twig`
- Empreendimento: `views/emails/empreendimento_interesse.twig`
- Newsletter: `views/emails/newsletter.twig`

## 📊 Métricas Disponíveis

- Total de leads
- Leads por dia/mês
- Taxa de conversão
- Leads por origem
- Leads por tipo de formulário
- Performance de campanhas (UTM)

## 🎨 Personalização

O sistema foi desenvolvido de forma modular, permitindo:
- Novos tipos de formulário
- Campos personalizados
- Templates de email específicos
- Filtros adicionais
- Métricas customizadas

## ⚡ Performance

- Índices otimizados no banco de dados
- Limpeza automática de tokens CSRF expirados
- Paginação eficiente na listagem
- Export otimizado para grandes volumes

---

**Desenvolvido por:** Sistema implementado com foco em captura eficiente de leads e análise de origem para melhor conversão de visitantes em clientes.