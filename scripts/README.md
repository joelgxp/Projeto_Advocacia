# 📜 Scripts do Sistema de Advocacia

Esta pasta contém scripts úteis para diagnóstico, verificação e gerenciamento do sistema.

## 🔍 Scripts de Diagnóstico

### PHP

#### `diagnosticar-erros.php`
**Descrição:** Script completo de diagnóstico que identifica erros detalhados no sistema.

**Uso:**
```bash
php scripts/diagnosticar-erros.php
```

---

#### `corrigir-problemas.php`
**Descrição:** Script que corrige automaticamente problemas comuns identificados pelo diagnóstico.

**Uso:**
```bash
php scripts/corrigir-problemas.php
```

**O que corrige:**
- ✅ Cria pasta `storage/framework/sessions` se não existir
- ✅ Cria tabela `users` se não existir
- ✅ Cria tabela `advogados` se não existir

**Nota:** Execute o diagnóstico primeiro para identificar problemas, depois execute este script para corrigi-los automaticamente.

**O que verifica:**
- ✅ Carregamento do Laravel
- ✅ Configuração do .env (todas as variáveis)
- ✅ Conexão com banco de dados
- ✅ Sintaxe PHP
- ✅ Arquivos vendor (CSS/JS/Fonts)
- ✅ Views essenciais
- ✅ Permissões de pastas
- ✅ Logs de erro
- ✅ Extensões PHP necessárias

**Saída:** Mostra erros detalhados com stack traces e dicas de solução.

---

#### `diagnosticar-403.php`
**Descrição:** Diagnóstico específico para erro 403 (Forbidden).

**Uso:**
```bash
php scripts/diagnosticar-403.php
```

**O que verifica:**
- ✅ Permissões de arquivos e pastas
- ✅ Configuração do .htaccess
- ✅ DocumentRoot do servidor
- ✅ Estrutura do projeto

---

### PowerShell (Windows)

#### `verificar-local.ps1`
**Descrição:** Verificação do ambiente local no Windows.

**Uso:**
```powershell
.\scripts\verificar-local.ps1
```

**O que verifica:**
- ✅ PHP instalado
- ✅ Composer instalado
- ✅ Dependências instaladas
- ✅ Arquivo .env
- ✅ APP_KEY
- ✅ Banco de dados

---

#### `iniciar-servidor.ps1`
**Descrição:** Inicia o servidor PHP local sem usar Artisan.

**Uso:**
```powershell
.\scripts\iniciar-servidor.ps1
```

**Funcionalidade:**
- Inicia servidor PHP na porta 8000
- Não requer Artisan
- Mostra URL de acesso

---

### Shell Scripts (Linux/Mac)

#### `check-server.sh`
**Descrição:** Diagnóstico completo do servidor (Linux/Mac).

**Uso:**
```bash
bash scripts/check-server.sh
```

**O que verifica:**
- ✅ Estrutura de diretórios
- ✅ Arquivos essenciais
- ✅ Configuração .env
- ✅ Permissões
- ✅ PHP e extensões
- ✅ Composer
- ✅ Banco de dados
- ✅ Laravel
- ✅ Logs
- ✅ Cache

**Saída:** Gera relatório em arquivo com timestamp.

---

#### `check-laravel-online.sh`
**Descrição:** Verificação rápida do Laravel online.

**Uso:**
```bash
bash scripts/check-laravel-online.sh
```

---

## 📋 Quando Usar Cada Script

| Situação | Script Recomendado |
|----------|-------------------|
| Erros no servidor online | `diagnosticar-erros.php` |
| Erro 403 (Forbidden) | `diagnosticar-403.php` |
| Corrigir problemas identificados | `corrigir-problemas.php` |
| Ambiente local Windows | `verificar-local.ps1` |
| Iniciar servidor local | `iniciar-servidor.ps1` |
| Diagnóstico completo Linux/Mac | `check-server.sh` |

## 🚀 Executando no Servidor Online

Para executar scripts PHP no servidor online:

1. **Via SSH:**
   ```bash
   ssh usuario@servidor
   cd /caminho/do/projeto
   php scripts/diagnosticar-erros.php
   ```

2. **Via FTP/SFTP:**
   - Faça upload do script
   - Execute via terminal do provedor ou SSH

3. **Via navegador (temporário):**
   - Coloque o script em `public/`
   - Acesse `https://seudominio.com/diagnosticar-erros.php`
   - ⚠️ **Remova após uso por segurança!**

## 📝 Notas Importantes

- ⚠️ Scripts de diagnóstico são **somente leitura** - não modificam nada
- ✅ Seguros para executar em produção
- 🔒 Não expõem senhas ou informações sensíveis
- 📊 Geram relatórios detalhados para análise

## 🔗 Documentação Relacionada

- [Comandos Sem Artisan](../docs/deploy/COMANDOS_SEM_ARTISAN.md)
- [Testar Servidor](../docs/deploy/TESTAR_SERVIDOR.md)
- [Deploy Rápido](../docs/deploy/DEPLOY_RAPIDO.md)

