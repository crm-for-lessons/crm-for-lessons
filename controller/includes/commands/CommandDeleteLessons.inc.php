<?php
	/*
	*Команда удаления уроков
	*вызов в UserCommand->perform()
	*для корректного использования в $data_lessons необходимы id
	*удаляемых уроков
	*/
	class CommandDeleteLessons extends CommandAddLessons
	{
		/*
		*метод для удаления уроков
		*@data_lessons массив с данными для удаления уроков
		*/
		public function execute(array $data_lessons) 
		{
			$operation = new OperationDeleteLessons();
			return $operation->use($data_lessons);
		}
		
		/*
		* метод обратный удалению
		* @data_lessons массив с ID добавленных уроков
		*/
		public function un_execute(array $data_lessons)
		{
			$operation = new OperationAddLessons();
			$this->check_changes_other_users($data_lessons);
			return $operation->use($data_lessons);
		}
	}
?>