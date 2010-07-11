<?php defined('SYSPATH') or die('No direct script access.');

Route::set('cheatsheet', 'cs(/<action>)')
	->defaults(array(
		'controller' => 'cheatsheet',
		'action'     => 'index',
	));