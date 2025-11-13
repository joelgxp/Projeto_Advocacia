# Arquivos Vendor Locais

Este projeto agora usa arquivos locais ao invés de CDNs para melhor performance e disponibilidade offline.

## Estrutura de Arquivos

```
public/
├── css/
│   └── vendor/
│       ├── bootstrap.min.css          # Bootstrap 5.3.2 CSS
│       ├── fontawesome.min.css       # Font Awesome 6.5.1 CSS
│       └── inter-font.css            # Fonte Inter (Google Fonts)
├── js/
│   └── vendor/
│       ├── bootstrap.bundle.min.js   # Bootstrap 5.3.2 JS
│       └── jquery.min.js             # jQuery 3.7.1
└── fonts/
    ├── fontawesome/
    │   ├── fa-solid-900.woff2
    │   ├── fa-regular-400.woff2
    │   └── fa-brands-400.woff2
    └── inter/
        ├── Inter-300.ttf
        ├── Inter-400.ttf
        ├── Inter-500.ttf
        ├── Inter-600.ttf
        └── Inter-700.ttf
```

## Bibliotecas Incluídas

- **Bootstrap 5.3.2** - Framework CSS
- **Font Awesome 6.5.1** - Ícones
- **jQuery 3.7.1** - Biblioteca JavaScript
- **Inter Font** - Tipografia moderna (300, 400, 500, 600, 700)

## Vantagens

✅ **Performance**: Arquivos servidos localmente são mais rápidos
✅ **Offline**: Sistema funciona sem conexão com internet
✅ **Controle**: Versões fixas garantem compatibilidade
✅ **Privacidade**: Não há requisições externas
✅ **Confiabilidade**: Não depende de CDNs externos

## Atualização

Para atualizar os arquivos vendor no futuro:

1. Baixe as novas versões dos CDNs
2. Substitua os arquivos em `public/css/vendor/` e `public/js/vendor/`
3. Atualize as fontes se necessário
4. Teste o sistema completamente

## Notas

- Os layouts (`app.blade.php` e `guest.blade.php`) foram atualizados para usar `asset()`
- O caminho das fontes Font Awesome foi ajustado para caminhos relativos
- A ordem de carregamento: jQuery → Bootstrap (jQuery deve vir antes)

