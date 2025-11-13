# 🔧 Configurar Banco de Dados no Servidor

## Credenciais do Banco de Dados

```
Host: localhost
Porta: 3306
Usuário: hotel631_joeladv
Senha: mXrnP61Gc&K$
Banco: hotel631_advocacia
```

## 📋 Passo 1: Configurar .env no Servidor

Acesse o servidor via SSH e edite o arquivo `.env`:

```bash
cd /home2/hotel631/adv.joelsouza.com.br
nano .env
```

Ou via FTP, edite o arquivo `.env` na raiz do projeto.

## 📝 Configuração do .env

Adicione/atualize estas linhas no `.env`:

```env
# ============================================
# CONFIGURAÇÕES DO BANCO DE DADOS
# ============================================
DB_HOSTNAME=localhost
DB_USERNAME=hotel631_joeladv
DB_PASSWORD=mXrnP61Gc&K$
DB_DATABASE=hotel631_advocacia
DB_DRIVER=mysqli
DB_PORT=3306

# ============================================
# CONFIGURAÇÕES DA APLICAÇÃO
# ============================================
APP_ENVIRONMENT=production
APP_BASEURL=https://adv.joelsouza.com.br/
APP_ENCRYPTION_KEY=sua_chave_aqui
```

## 🔑 Gerar APP_ENCRYPTION_KEY

No servidor, execute:

```bash
php -r "echo base64_encode(random_bytes(32));"
```

Copie o resultado e cole no `.env` na linha `APP_ENCRYPTION_KEY=`

## ✅ Verificar Conexão

Teste a conexão no servidor:

```bash
mysql -h localhost -P 3306 -u hotel631_joeladv -p'mXrnP61Gc&K$' hotel631_advocacia -e "SELECT COUNT(*) as total_tabelas FROM information_schema.tables WHERE table_schema='hotel631_advocacia';"
```

Se funcionar, você verá o número de tabelas no banco.

## 🔒 Permissões do Arquivo .env

```bash
chmod 600 .env
```

Isso garante que apenas o proprietário possa ler/escrever o arquivo.

## 📋 Checklist

- [ ] Arquivo `.env` criado/editado no servidor
- [ ] Credenciais do banco configuradas
- [ ] `APP_ENCRYPTION_KEY` gerado e configurado
- [ ] `APP_ENVIRONMENT=production` configurado
- [ ] `APP_BASEURL` configurado com a URL correta
- [ ] Permissões do `.env` ajustadas (chmod 600)
- [ ] Conexão MySQL testada e funcionando

## ⚠️ Importante

- **NUNCA** commite o arquivo `.env` no Git
- Mantenha as credenciais seguras
- Use `APP_ENVIRONMENT=production` em produção
- O arquivo `.env` já está no `.gitignore`

