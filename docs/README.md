# 📚 Documentação do Sistema de Advocacia

Bem-vindo à documentação completa do Sistema de Gerenciamento para Escritório de Advocacia.

## 📁 Estrutura da Documentação

### 🚀 [Instalação e Configuração](instalacao/)
Documentos relacionados à instalação, configuração inicial e setup do sistema.

- [Guia de Instalação](instalacao/INSTALACAO.md)
- [Quick Start](instalacao/QUICK_START.md)
- [Configurar MySQL](instalacao/CONFIGURAR_MYSQL.md)

### 🏗️ [Arquitetura](arquitetura/)
Documentos sobre a arquitetura do sistema, estrutura de pastas e organização do código.

- [Arquitetura Moderna](arquitetura/ARQUITETURA_MODERNA.md)

### ⚙️ [Funcionalidades](funcionalidades/)
Documentação detalhada sobre funcionalidades específicas do sistema.

- [Consulta Processual](funcionalidades/CONSULTA_PROCESSUAL.md) - Integração com API CNJ/DataJud

### 🚢 [Deploy](deploy/)
Documentos sobre deploy, produção e publicação do sistema.

- [Guia de Deploy](deploy/DEPLOY_SERVIDOR_ONLINE.md) - Deploy completo
- [Iniciar Servidor Online](deploy/INICIAR_SERVIDOR_ONLINE.md) - Configuração do servidor
- [Testar Servidor](deploy/TESTAR_SERVIDOR.md) - Como testar o servidor
- [Reinstalar Servidor](deploy/REINSTALAR_SERVIDOR.md) - Reinstalação completa
- [Comandos Sem Artisan](deploy/COMANDOS_SEM_ARTISAN.md) - Alternativas sem Artisan

### ⚡ [Otimização](otimizacao/)
Documentos sobre otimizações, performance e melhorias.

- [Otimizações Implementadas](otimizacao/OTIMIZACOES.md)

### 💻 [Desenvolvimento](desenvolvimento/)
Documentos para desenvolvedores sobre implementação, testes e desenvolvimento.

- [README Laravel](desenvolvimento/README_LARAVEL.md)

## 🎯 Início Rápido

1. **Primeira vez?** Comece pelo [Quick Start](instalacao/QUICK_START.md)
2. **Configurando?** Veja o [Guia de Instalação](instalacao/INSTALACAO.md)
3. **Fazendo deploy?** Consulte o [Guia de Deploy](deploy/DEPLOY_SERVIDOR_ONLINE.md)

## 📖 Documentação Adicional

### Scripts de Diagnóstico
Scripts úteis para diagnóstico e verificação estão em `scripts/`:
- `scripts/diagnosticar-erros.php` - Diagnóstico detalhado de erros
- `scripts/diagnosticar-403.php` - Diagnóstico de erro 403
- `scripts/corrigir-problemas.php` - Corrige problemas automaticamente
- `scripts/verificar-local.ps1` - Verificação local (Windows)
- `scripts/iniciar-servidor.ps1` - Iniciar servidor local (Windows)
- `scripts/check-server.sh` - Diagnóstico completo (Linux/Mac)

Consulte `scripts/README.md` para mais informações.

### Código Legado
Documentação do código legado está disponível em:
- `legacy/admin/consulta-processual/` - Documentação da consulta processual antiga
- `legacy/src/` - Documentação do código legado

## 🔗 Links Úteis

- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [API DataJud CNJ](https://api-publica.datajud.cnj.jus.br/)

## 📝 Contribuindo

Ao adicionar nova documentação:
1. Coloque na pasta apropriada dentro de `docs/`
2. Atualize este README com o link
3. Mantenha a estrutura organizada

## 📧 Suporte

Para dúvidas ou problemas, consulte a documentação específica ou verifique os logs do sistema.




