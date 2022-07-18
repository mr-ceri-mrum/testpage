<?php

return [
	// MainController
	'' => [
		'controller' => 'main',
		'action' => 'index',
	],
	'postlist' =>[
		'controller' => 'main',
		'action' => 'postlist',
	],
	'post/{id:\d+}' => [
		'controller' => 'main',
		'action' => 'post',
	],
	// MerchantController***************************************************************
	//***************************************************************
	'merchant/perfectmoney' => [
		'controller' => 'merchant',
		'action' => 'perfectmoney',
	],
	// DashboardController
	'dashboard/lessons' => [ 
		'controller' => 'dashboard',
		'action' => 'lessons',
	],
	'dashboard/tariffs/{page:\d+}' => [
		'controller' => 'dashboard',
		'action' => 'tariffs',
	],
	'dashboard/invest/{id:\d+}' => [
		'controller' => 'dashboard',
		'action' => 'invest',
	],
	'dashboard/history' => [
		'controller' => 'dashboard',
		'action' => 'history',
	],
	'dashboard/history/{page:\d+}' => [
		'controller' => 'dashboard',
		'action' => 'history',
	],
	'dashboard/referrals' => [
		'controller' => 'dashboard',
		'action' => 'referrals',
	],
	'dashboard/referrals/{page:\d+}' => [
		'controller' => 'dashboard',
		'action' => 'referrals',
	],
	// AccountController
	'account/login' => [
		'controller' => 'account',
		'action' => 'login',
	],
	'account/register' => [
		'controller' => 'account',
		'action' => 'register',
	],
	'account/register/{ref:\w+}' => [
		'controller' => 'account',
		'action' => 'register',
	],
	'account/recovery' => [
		'controller' => 'account',
		'action' => 'recovery',
	],
	'account/confirm/{token:\w+}' => [
		'controller' => 'account',
		'action' => 'confirm',
	],
	'account/reset/{token:\w+}' => [
		'controller' => 'account',
		'action' => 'reset',
	],
	'account/profile' => [
		'controller' => 'account',
		'action' => 'profile',
	],
	'account/profile/edit' => [
		'controller' => 'account',
		'action' => 'edit',
	],
	'account/logout' => [
		'controller' => 'account',
		'action' => 'logout',
	],
	// AdminController
	'admin/withdraw' => [
		'controller' => 'admin',
		'action' => 'withdraw',
	],
	'admin/history' => [
		'controller' => 'admin',
		'action' => 'history',
	],
	'admin/history/{page:\d+}' => [
		'controller' => 'admin',
		'action' => 'history',
	],
	'admin/tariffs' => [
		'controller' => 'admin',
		'action' => 'tariffs',
	],
	'admin/tariffs/{page:\d+}' => [
		'controller' => 'admin',
		'action' => 'tariffs',
	],
	'admin/login' => [
		'controller' => 'admin',
		'action' => 'login',
	],
	'admin/lessons' => [
		'controller' => 'admin',
		'action' => 'lessons',
	],
	'admin/add' => [
		'controller' => 'admin',
		'action' => 'add',
	],
	'admin/posts' => [
		'controller' => 'admin',
		'action' => 'posts',
	],
	'admin/logout' => [
		'controller' => 'admin',
		'action' => 'logout',
	],
];