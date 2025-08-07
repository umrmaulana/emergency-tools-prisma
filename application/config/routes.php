<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Authentication routes
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

// Emergency Tools Inspector routes
$route['emergency_tools/inspector'] = 'emergency_tools/inspector/index';
$route['emergency_tools/inspector/dashboard'] = 'emergency_tools/inspector/index';
$route['emergency_tools/inspector/emergency_tools'] = 'emergency_tools/inspector/emergency_tools';
$route['emergency_tools/inspector/location'] = 'emergency_tools/inspector/location';
$route['emergency_tools/inspector/checksheet'] = 'emergency_tools/inspector/checksheet';
$route['emergency_tools/inspector/scan_qr'] = 'emergency_tools/inspector/scan_qr';
$route['emergency_tools/inspector/process_qr'] = 'emergency_tools/inspector/process_qr';
$route['emergency_tools/inspector/inspection_form/(:num)'] = 'emergency_tools/inspector/inspection_form/$1';
$route['emergency_tools/inspector/submit_inspection'] = 'emergency_tools/inspector/submit_inspection';
$route['emergency_tools/inspector/ajax_get_equipment'] = 'emergency_tools/inspector/ajax_get_equipment';
$route['emergency_tools/inspector/debug_qr/(:any)'] = 'emergency_tools/inspector/debug_qr/$1';
$route['emergency_tools/inspector/debug_qr'] = 'emergency_tools/inspector/debug_qr';

