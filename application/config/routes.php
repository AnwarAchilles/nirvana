<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Blank';
$route['404_override'] = 'Blank/not_found';
$route['translate_uri_dashes'] = FALSE;








// todo router admin cyruz
$route['Cyruz'] = 'Cyruz/Dashboard';
$route['cyruz'] = 'Cyruz/Dashboard';

// todo nirvana docs
$route['docs'] = 'Welcome';








/* 
 * DYNAMIC API
 * carefull dont edit this line cause for the api work properly */
$route['api/(.+)'] = function ( $param ) {
  $param = explode('/', $param);
  $return = '';

  if (file_exists( APPPATH.'/controllers/Api/'.ucfirst($param[0]).'.php' )) {
    $endpoint = [
      'base'=> '',
      'table'=> '',
      'method'=> '',
      'id'=> '',
    ];
    $endpoint['base'] = 'Api';
  }else {
    $endpoint = [
      'base'=> '',
      'method'=> '',
      'table'=> '',
      'id'=> '',
    ];
    $endpoint['base'] = 'Api/BaseApi';
  }

  if (isset($param[1])) {
    if (is_numeric($param[1])) {
      $endpoint['method'] = 'index';
      $endpoint['id'] = $param[1];
    }else {
      $endpoint['method'] = $param[1];
      $endpoint['id'] = '';
    }
  }else {
    $endpoint['method'] = 'index';
  }

  $endpoint['table'] = $param[0];
  $endpoint['id'] = (isset($param[2])) ? $param[2] : '';

  $return = implode('/', $endpoint);

  return $return;
};