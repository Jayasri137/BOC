<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['base_url'] = 'http://localhost/itime-crm/';
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
$route['default_controller'] = 'welcome';
$route['404_override'] = 'errors/page_missing';
$route['translate_uri_dashes'] = FALSE;
$route['supplierlogin'] = 'service/supplierlogin';
$route['chat/get_chats']     = 'chat_presence/get_chats';
$route['chat/get_messages/(:num)'] = 'chat_presence/get_messages/$1';
$route['chat/website_get_thread']      = 'chat_presence/website_get_thread';
$route['chat/website_post_message']    = 'chat_presence/website_post_message';
$route['chat/website_get_messages/(:num)'] = 'chat_presence/website_get_messages/$1';

$route['chat/admin_get_threads']       = 'chat_presence/admin_get_threads';
$route['chat/admin_get_messages/(:num)'] = 'chat_presence/admin_get_messages/$1';
$route['chat/admin_post_message']      = 'chat_presence/admin_post_message';
$route['chat/check_agent_status'] = 'chat_presence/check_agent_status';
$route['chat/admin_unread_count'] = 'chat_presence/admin_unread_count';
$route['chat/check_status'] = 'chat_presence/check_status';

$route['enquiry/toggle_status/(:num)']   = 'enquiry/toggle_status/$1';
$route['enquiry/convert_to_lead/(:num)'] = 'enquiry/convert_to_lead/$1';
$route['enquiry/move_to_lead/(:num)'] = 'enquiry/move_to_lead/$1';

