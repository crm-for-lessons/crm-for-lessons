<?php
	/*
	*команда добавления уроков
	*вызов в UserCommand->perform()
	*/
	class CommandAddLessons extends Command
	{
		/*
		*метод для добавления уроков
		*@data_lessons массив с данными для добавления уроков
		*/
		public function execute(array $data_lessons) 
		{
			$operation = new OperationAddLessons();
			/*
			* Выполнять поверку пересечений, если вставляем не новые уроки
			*/
			if (isset($data_lessons[0]['id']))
				$this->check_changes_other_users($data_lessons);
			return $operation->use($data_lessons);
		}

		/*
		* метод обратный добавлению
		* @data_lessons массив с ID добавленных уроков
		*/
		public function un_execute(array $data_lessons)
		{
			$operation = new OperationDeleteLessons();
			return $operation->use($data_lessons);
		}

		/*
		* проверка рабочей области на наличие изменений
		* других пользователей
		*/
		protected function check_changes_other_users(array $data_lessons)
		{
			foreach ($data_lessons as $one_lesson)
				$this->find_intersection($data_lessons[0]['id'],
				 												 $one_lesson['start_datetime'], 
				 												 $one_lesson['end_datetime']);
		}

		/*
		* проверка каждого урока на наличие пересечений
		* с новыми уроками
		*/
		protected function find_intersection(int $start_id, string $from_datatime, string $before_datetime)
		{
			global $LOCAL_undo_add_lessons_error;

			$query = "SELECT COUNT(*) FROM lessons WHERE id > $start_id";
			$query .= " AND (('$from_datatime' < end_datetime AND '$before_datetime' > start_datetime)";
			$query .= " OR ('$from_datatime' = start_datetime AND '$before_datetime' = end_datetime))";
			$SQL = new SQLRequest();
			$SQL->set_fetch_assoc(false);
			@$count_problem_lessons = $SQL->get_data_as_array($query)[0];

			if ($count_problem_lessons[0])
				Answer::error($LOCAL_undo_add_lessons_error);
		}
	}
?>