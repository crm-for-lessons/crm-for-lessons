<?php
	/*
	* Под понятием команда понимается то что будет запускать пользователь
	* @data_lessons - массив данных уроков
	*/
	interface iCommand
	{
		public function execute(array $data_lessons);
		public function un_execute(array $data_lessons);
	}
	class Command implements iCommand
	{
		public function execute(array $data_lessons) {}
		public function un_execute(array $data_lessons) {}
	}
?>