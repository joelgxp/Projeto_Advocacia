# Guia de Instalação e Uso do Composer

## Pré-requisitos

- PHP 7.4 ou superior
- Composer instalado no sistema

## Instalação

### 1. Instalar o Composer (se ainda não tiver)

**Windows:**
```bash
# Baixar o instalador do Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

**Linux/Mac:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Instalar dependências do projeto

```bash
composer install
```

### 3. Para desenvolvimento (opcional)

```bash
composer install --dev
```

## Comandos Úteis

### Instalar uma nova dependência
```bash
composer require nome-do-pacote
```

### Atualizar dependências
```bash
composer update
```

### Ver dependências instaladas
```bash
composer show
```

### Executar scripts personalizados
```bash
composer run-script nome-do-script
```

## Estrutura do Projeto

Após a instalação, o Composer criará:

- `vendor/` - Dependências instaladas
- `composer.lock` - Versões exatas das dependências
- Autoloader PSR-4 em `vendor/autoload.php`

## Uso no Código

```php
<?php
require_once 'vendor/autoload.php';

use Advocacia\Models\Cliente;
use Advocacia\Database\Connection;

// Seu código aqui
```

## Scripts Disponíveis

- `composer test` - Executa testes PHPUnit
- `composer dev` - Inicia servidor de desenvolvimento (localhost:8000)
- `composer start` - Inicia servidor de desenvolvimento (localhost:8000)
- `composer cs` - Verifica padrões de código
- `composer cs-fix` - Corrige padrões de código automaticamente

## Configuração de Desenvolvimento

Para usar as configurações de desenvolvimento, execute:

```bash
composer install --dev
```

Isso instalará ferramentas adicionais como:
- PHPUnit para testes
- Symfony Var Dumper para debug
- Monolog para logs

