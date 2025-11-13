<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Default Controller
|--------------------------------------------------------------------------
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
*/
$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
$route['login'] = 'Login';
$route['logout'] = 'Login/logout';

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/
$route['dashboard'] = 'Dashboard';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
$route['admin'] = 'Admin';
$route['admin/(:any)'] = 'Admin/$1';

/*
|--------------------------------------------------------------------------
| Advogado Routes
|--------------------------------------------------------------------------
*/
$route['advogado'] = 'Advogado';
$route['advogado/(:any)'] = 'Advogado/$1';

/*
|--------------------------------------------------------------------------
| Recepcao Routes
|--------------------------------------------------------------------------
*/
$route['recepcao'] = 'Recepcao';
$route['recepcao/(:any)'] = 'Recepcao/$1';

/*
|--------------------------------------------------------------------------
| Cliente Routes
|--------------------------------------------------------------------------
*/
$route['cliente'] = 'Cliente';
$route['cliente/(:any)'] = 'Cliente/$1';

