<?php defined('SYSPATH') or die('No direct script access.');

Route::set('cheatsheet', 'cs(/<action>(/<file>))', array('file' => '.+'))
    ->defaults(array(
		'controller'    => 'cheatsheet',
		'action'        => 'index',
		'file'          => NULL,
	));