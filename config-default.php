<?php

$GLOBALS['config'] = [

	'Database' => [
		'db_host' => '',
		'db_database' => '',
		'db_user' => '',
		'db_password' => '',

	],
	//i--- Random constants and keys for security reasons ; inside_security ; torrison ; 01.08.2018 ; 1 ---/
	'Security' => [
		'encryption_salt1' => 'ZAAAAAxa',
		'encryption_salt2' => 'AAAAAA8a',
		'encryption_salt_length' => 8,
		'encryption_aes_key' => 'AD26iAAA',
		'encryption_iv_static' => '12d47S8890AAAAAA',
		'csrf_token_key' => '123456AZA0asaAAAjklywsdrfgthyAAA',
		'csrf_token_salt' => '1234567c94asAAAFjklAGsdrfgthyAAA',
	],
	'Mailer' => [
		'from_email' => '',
		'from_name' => 'Inside Mailer',
		'mail_password' => '',
	],
	'Website' => [
		'sitename' => 'Inside Easy',
		'base_url' => 'https://inside5easy.ikiev.biz',
		'fb_app_id' => '',
		'fb_app_secret' => '',
		'fb_app_login_redirect' => '',
		'fb_img_url' => '/Public/AppFront/app_default_template/img/sale1.jpg',
		'g_client_id' => '',
		'g_client_secret' => '',
		'g_redirect_uri' => 'https://inside5easy.ikiev.biz/auth/page/google_redirect/',
		'admin_email' => 'torrison1@gmail.com',
		'google_maps_key' => '',
		'google_maps_key2' => '',
	],
	'Translate' => [
		'default_lang' => 'en',
	],
	'Admin' => [
		'admin_panel_name' => 'Admin Panel',
	],
];