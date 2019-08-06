<?php
	declare(strict_types = 1);
	date_default_timezone_set('Etc/GMT-3');
	require_once __DIR__.'/autoloader.php';
	//require_once __DIR__.'/commands/CommandAddLessons.inc.php';
	//require_once __DIR__.'/autoloader.php';
	$user_command = new UserCommand();

	$id_schoolboys = json_encode([1,2,3]);
	$lesson = [
		// 'id' => 2,
		'id_schoolboys' => $id_schoolboys,
		'id_teacher' => 5,
		'id_type' => 1,
		'start_datetime' => '2019-08-02 12:00:00',
		'end_datetime' => '2019-08-02 13:00:00',
		'id_room' => 1,
		'comment' => 'первый'
	];
	$lessons[0] = $lesson;
	// $lesson = [
	// 	//'id' => 3,
	// 	'id_schoolboys' => $id_schoolboys,
	// 	'id_teacher' => 5,
	// 	'id_type' => 1,
	// 	'start_datetime' => '2019-08-02 13:00:00',
	// 	'end_datetime' => '2019-08-02 14:00:00',
	// 	'id_room' => 1,
	// 	'comment' => 'второй'
	// ];
	// $lessons[1] = $lesson;
	echo "входные данные: <br>";
	$lessons = json_encode($lessons, JSON_UNESCAPED_UNICODE);
	print_r($lessons);
	$command = 'CommandAddLessons';
	$changes = $user_command->perform(new $command(), $lessons);
	// echo "выходные данные: <br>";
	// print_r($changes);
	// print_r($lessons);
	// echo gettype($lessons[0]['id_schoolboys']);
?>