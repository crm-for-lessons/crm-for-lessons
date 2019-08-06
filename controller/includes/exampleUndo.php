<?php
	declare(strict_types = 1);
	require_once __DIR__.'/autoloader.php';
	$user_command = new UserCommand();
	$user_command->undo();
?>