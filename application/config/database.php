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

$db['default'] = array(
	'dsn'	=> getenv('DB_DSN') ?: '',
	'hostname' => getenv('DB_HOSTNAME') ?: 'localhost',
	'username' => getenv('DB_USERNAME') ?: 'root',
	'password' => getenv('DB_PASSWORD') ?: '',
	'database' => getenv('DB_DATABASE') ?: 'advocacia',
	'dbdriver' => getenv('DB_DRIVER') ?: 'mysqli',
	'dbprefix' => getenv('DB_PREFIX') ?: '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => getenv('DB_CHARSET') ?: 'utf8',
	'dbcollat' => getenv('DB_COLLATION') ?: 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

