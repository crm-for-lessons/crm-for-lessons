<?php
	/*
	* подгрузка классов и констант
	*/ 
	date_default_timezone_set('Etc/GMT-3');
	require_once __DIR__.'/../../settings/service-options.php';
	set_include_path('commands');
	spl_autoload_register(function ($class_name) {
	  include $class_name . '.inc.php';
	  echo "class $class_name has been loaded <br>";
	});
?>