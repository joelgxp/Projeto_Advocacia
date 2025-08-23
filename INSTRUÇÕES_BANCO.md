# 🗄️ **Configuração do Banco de Dados - Sistema de Advocacia**

## 🎯 **Problema Identificado**

Você está certo! O banco de dados `advocacia` não existe ou está vazio, por isso a conexão está falhando.

## ✅ **Solução Implementada**

Criamos scripts automáticos para configurar o banco de dados completo:

### 📁 **Arquivos Criados:**

1. **`criar-banco.sql`** - Script SQL com todas as tabelas e dados
2. **`criar-banco.php`** - Executor automático do script SQL
3. **`INSTRUÇÕES_BANCO.md`** - Este arquivo de instruções

## 🚀 **Como Resolver (Passo a Passo)**

### **Passo 1: Acessar o Script de Criação**
```
http://localhost:8000/criar-banco.php
```

### **Passo 2: Executar Automaticamente**
O script vai:
- ✅ Conectar ao MySQL
- ✅ Criar o banco `advocacia`
- ✅ Criar todas as tabelas necessárias
- ✅ Inserir dados de exemplo
- ✅ Verificar se tudo funcionou

### **Passo 3: Testar o Sistema**
Após a criação do banco:
```
http://localhost:8000/index.php
```

## 🏗️ **Estrutura do Banco Criado**

### **Tabelas Principais:**
- **`usuarios`** - Usuários do sistema (admin, advogados, etc.)
- **`clientes`** - Cadastro de clientes
- **`processos`** - Processos jurídicos
- **`audiencias`** - Agendamento de audiências
- **`movimentacoes`** - Histórico de movimentações
- **`pagamentos`** - Controle financeiro
- **`especialidades`** - Áreas do direito
- **`cargos`** - Cargos dos funcionários
- **`funcionarios`** - Cadastro de funcionários

### **Dados Iniciais Inseridos:**
- 👤 **Usuário Admin**: `admin@advocacia.com` / `123`
- 👥 **Cliente Exemplo**: João Silva
- 📋 **Processo Exemplo**: 001/2024
- 🎯 **5 Especialidades**: Civil, Trabalhista, Previdenciário, Tributário, Administrativo
- 💼 **5 Cargos**: Advogado, Estagiário, Recepcionista, Tesoureiro, Assistente

## 🔧 **Comandos Manuais (Alternativa)**

Se preferir usar o phpMyAdmin ou linha de comando:

### **Via phpMyAdmin:**
1. Acesse: `http://localhost/phpmyadmin`
2. Clique em "Novo" para criar banco
3. Nome: `advocacia`
4. Collation: `utf8mb4_unicode_ci`
5. Importe o arquivo `criar-banco.sql`

### **Via Linha de Comando:**
```bash
# Conectar ao MySQL
mysql -u root -p

# Executar o script
source criar-banco.sql
```

## 🧪 **Testes Após Configuração**

### **1. Teste Básico:**
```
http://localhost:8000/index.php
```
- Deve mostrar a tela de login sem erros

### **2. Teste do Banco:**
```
http://localhost:8000/teste-banco.php
```
- Deve mostrar "Conexão realizada com sucesso!"

### **3. Teste da Porta:**
```
http://localhost:8000/teste-porta.php
```
- Deve mostrar todos os testes passando

## 🔍 **Verificação de Status**

### **Antes da Configuração:**
- ❌ Banco `advocacia` não existe
- ❌ Conexão falha
- ❌ Sistema mostra "Sistema em Manutenção"

### **Depois da Configuração:**
- ✅ Banco `advocacia` criado
- ✅ Tabelas populadas com dados
- ✅ Conexão funcionando
- ✅ Sistema acessível normalmente

## 🚨 **Problemas Comuns e Soluções**

### **Erro: "Access denied for user 'root'@'localhost'"**
- **Solução**: Verifique se o XAMPP está rodando
- **Verificar**: Painel do XAMPP → MySQL → Start

### **Erro: "Unknown database 'advocacia'"**
- **Solução**: Execute `criar-banco.php`
- **Verificar**: Acesse o script de criação

### **Erro: "Can't connect to server"**
- **Solução**: Verifique se a porta 3306 está livre
- **Verificar**: `netstat -an | findstr :3306`

## 📚 **Arquivos Relacionados**

- `config.php` - Configurações do banco
- `conexao.php` - Conexão PDO
- `index.php` - Sistema principal
- `autenticar.php` - Autenticação de usuários

## 🎉 **Resultado Esperado**

Após executar `criar-banco.php`:
1. **Banco criado** com todas as tabelas
2. **Dados inseridos** automaticamente
3. **Sistema funcionando** normalmente
4. **Login disponível** com usuário admin

---

**💡 Dica**: Execute o script de criação apenas uma vez. Após a configuração, você pode deletar os arquivos `criar-banco.php` e `criar-banco.sql` se desejar.

**Status**: 🚀 **Pronto para Execução**
**Próximo Passo**: Acessar `http://localhost:8000/criar-banco.php`
