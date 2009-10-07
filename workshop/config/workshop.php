<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['default'] = array
(
	'config_delegate'        => 'Database_Workshop_Config_Delegate_Core', //File_Workshop_Config_Delegate_Core
	'min_of_authors'     => 0,
	'max_of_authors'   => 5,
);

$config['rules'] = array
(
	'blog_post'        => array('min' => 1),
);

