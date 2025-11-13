# 🚀 Iniciar Sistema Localmente

## Método Rápido (Windows)

```powershell
# Execute o script
.\scripts\iniciar-local.ps1
```

## Método Manual

### 1. Configurar .env
```bash
# Se não existir, copie:
cp env.example .env

# Edite o .env e configure:
# - APP_BASEURL=http://localhost:8000/
# - DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE
# - APP_ENCRYPTION_KEY (gere com: php -r "echo base64_encode(random_bytes(32));")
```

### 2. Criar Banco de Dados
```sql
CREATE DATABASE advocacia CHARACTER SET utf8 COLLATE utf8_general_ci;
```

### 3. Iniciar Servidor
```bash
php -S localhost:8000
```

### 4. Acessar
```
http://localhost:8000
```

## Credenciais Padrão (se criado manualmente)

- **Email:** admin@advocacia.com
- **Senha:** admin123

⚠️ **Altere a senha após o primeiro login!**

---

📖 **Guia completo:** Veja `INICIAR_LOCAL.md` para mais detalhes.

