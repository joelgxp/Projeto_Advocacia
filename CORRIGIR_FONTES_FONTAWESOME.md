# 🔧 Correção: Erro 500 ao Carregar Fontes do Font Awesome

## ❌ Problema
Erro ao carregar fontes do Font Awesome:
```
GET https://adv.joelsouza.com.br/assets/css/webfonts/fa-solid-900.woff2 net::ERR_ABORTED 500
GET https://adv.joelsouza.com.br/assets/css/webfonts/fa-solid-900.ttf 500
```

## 🔍 Causa
O arquivo CSS do Font Awesome (`fontawesome.min.css`) referencia os arquivos de fonte usando o caminho relativo `../webfonts/`, mas os arquivos de fonte estavam apenas em `public/fonts/fontawesome/`.

Quando o CSS está em:
- `public/css/vendor/fontawesome.min.css` → procura em `public/css/webfonts/`
- `assets/css/vendor/fontawesome.min.css` → procura em `assets/css/webfonts/`

## ✅ Solução Aplicada

### 1. Criado Diretório `webfonts`
- ✅ `public/css/webfonts/` - Para CSS em `public/css/vendor/`
- ✅ `assets/css/webfonts/` - Para CSS em `assets/css/vendor/`

### 2. Copiados Arquivos de Fonte
Os arquivos `.woff2` foram copiados para ambos os diretórios:
- ✅ `fa-solid-900.woff2`
- ✅ `fa-regular-400.woff2`
- ✅ `fa-brands-400.woff2`

### 3. Script Criado
Criado `scripts/criar-webfonts.php` para automatizar a criação dos diretórios e cópia dos arquivos.

## 📋 Estrutura Final

```
public/
├── css/
│   ├── vendor/
│   │   └── fontawesome.min.css
│   └── webfonts/          ← NOVO
│       ├── fa-solid-900.woff2
│       ├── fa-regular-400.woff2
│       └── fa-brands-400.woff2
└── fonts/
    └── fontawesome/       (mantido para referência)
        ├── fa-solid-900.woff2
        ├── fa-regular-400.woff2
        └── fa-brands-400.woff2

assets/
├── css/
│   ├── vendor/
│   │   └── fontawesome.min.css
│   └── webfonts/          ← NOVO
│       ├── fa-solid-900.woff2
│       ├── fa-regular-400.woff2
│       └── fa-brands-400.woff2
└── fonts/                 (mantido para referência)
    ├── fa-solid-900.woff2
    ├── fa-regular-400.woff2
    └── fa-brands-400.woff2
```

## 🚀 Próximos Passos

1. ✅ Arquivos já copiados localmente
2. ⏳ Fazer deploy para o servidor
3. ⏳ Executar no servidor (se necessário):
   ```bash
   php scripts/criar-webfonts.php
   ```

## 💡 Nota

Os arquivos `.ttf` não foram copiados porque não existem no diretório original. O CSS do Font Awesome tenta carregar `.ttf` como fallback, mas funciona apenas com `.woff2` que é o formato moderno e suportado por todos os navegadores.

