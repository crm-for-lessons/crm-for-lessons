<?php
	/*
	* под понятием операция имеется ввиду сам механизм
	*/
	interface iOperation
	{
		public function check(); //проверка пользователя на доступ данной операции
		public function use(array $data_lesson); //использование операции
	}
	class Operation implements iOperation
	{
		protected $columns_commands;

		function __construct()
		{
			$query = "SHOW COLUMNS FROM lessons";
			$this->columns_commands = (new SQLRequest())->get_data_as_array($query);
			$new_columns_commands = [];
			foreach ($this->columns_commands as $value) 
				array_push($new_columns_commands, $value['Field']);
			$this->columns_commands = $new_columns_commands;
		}

		public function check(){
			return true;
		}
		public function use(array $data_lesson){}
	}
?>