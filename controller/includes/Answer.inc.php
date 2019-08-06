<?php
	/*
	* ответы в json, для последующего вывода в фронт
	*/
	interface iAnswer
	{
		public static function question(string $message):string; //ломает выполнение скрипта и задает вопрос пользователю
		public static function ok(string $message):string; //ответ пользователю
		public static function error(string $message):string; //ломает выполнение программы с ошибкой
	}
	class Answer implements iAnswer
	{
	 	public static function question(string $message):string 
	 	{
	 		$answer['type'] = 'question';
	 		$answer['message'] = $message;
	 		exit(json_encode($answer, JSON_UNESCAPED_UNICODE));
	 	}

	 	public static function ok(string $message):string 
	 	{
	 		$answer['type'] = 'ok';
	 		$answer['message'] = $message;
	 		echo json_encode($answer, JSON_UNESCAPED_UNICODE);
	 	}
	 	
	 	public static function error(string $message):string 
	 	{
	 		$answer['type'] = 'error';
	 		$answer['message'] = $message;
	 		exit(json_encode($answer, JSON_UNESCAPED_UNICODE));
	 	}
	} 
?>