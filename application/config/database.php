<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Database Settings
|--------------------------------------------------------------------------
|
| This file contains the database settings for your application.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
*/

$active_group = 'default';
$query_builder = TRUE;

// Função para ler variáveis do .env (suporta Laravel e CodeIgniter)
function getEnvVar($key, $default = '') {
	$value = getenv($key);
	if ($value !== false) {
		return $value;
	}
	
	// Mapeamento de variáveis Laravel para CodeIgniter
	$laravel_map = array(
		'DB_HOSTNAME' => 'DB_HOST',
		'DB_USERNAME' => 'DB_USERNAME',
		'DB_PASSWORD' => 'DB_PASSWORD',
		'DB_DATABASE' => 'DB_DATABASE',
		'APP_ENCRYPTION_KEY' => 'APP_KEY',
		'APP_BASEURL' => 'APP_URL',
		'APP_ENVIRONMENT' => 'APP_ENV'
	);
	
	// Tentar ler do arquivo .env manualmente
	$env_file = __DIR__ . '/../../.env';
	if (file_exists($env_file)) {
		$lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$env_vars = array();
		
		// Primeiro, ler todas as variáveis
		foreach ($lines as $line) {
			$line = trim($line);
			if (empty($line) || strpos($line, '#') === 0) {
				continue; // Ignorar comentários
			}
			if (strpos($line, '=') !== false) {
				list($name, $val) = explode('=', $line, 2);
				$name = trim($name);
				$val = trim($val);
				// Remover aspas e variáveis ${}
				$val = trim($val, '"\'');
				$val = preg_replace('/\$\{([^}]+)\}/', '', $val); // Remove ${VAR}
				$env_vars[$name] = $val;
			}
		}
		
		// Procurar pela chave diretamente
		if (isset($env_vars[$key])) {
			return $env_vars[$key];
		}
		
		// Se não encontrou, tentar mapeamento Laravel
		if (isset($laravel_map[$key]) && isset($env_vars[$laravel_map[$key]])) {
			return $env_vars[$laravel_map[$key]];
		}
		
		// Tratamento especial para APP_KEY (Laravel) -> APP_ENCRYPTION_KEY
		if ($key === 'APP_ENCRYPTION_KEY' && isset($env_vars['APP_KEY'])) {
			$app_key = $env_vars['APP_KEY'];
			// Remover prefixo "base64:" se houver
			if (strpos($app_key, 'base64:') === 0) {
				return substr($app_key, 7);
			}
			return $app_key;
		}
	}
	
	return $default;
}

$db['default'] = array(
	'dsn'	=> getEnvVar('DB_DSN', ''),
	'hostname' => getEnvVar('DB_HOSTNAME', 'localhost'),
	'username' => getEnvVar('DB_USERNAME', 'root'),
	'password' => getEnvVar('DB_PASSWORD', ''),
	'database' => getEnvVar('DB_DATABASE', 'advocacia'),
	'dbdriver' => getEnvVar('DB_DRIVER', 'mysqli'),
	'dbprefix' => getEnvVar('DB_PREFIX', ''),
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => getEnvVar('DB_CHARSET', 'utf8'),
	'dbcollat' => getEnvVar('DB_COLLATION', 'utf8_general_ci'),
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

