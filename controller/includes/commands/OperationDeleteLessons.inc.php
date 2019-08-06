<?php
	/*
	* операция удаления урока
	*/
	class OperationDeleteLessons extends Operation
	{
		public function use(array $data_lessons)
		{
			$query = $this->composition_query($data_lessons);
			(new SQLRequest())->send($query);
			return $data_lessons;
		}
		/*
		* формирование запроса для удаления уроков
		* по ID
		*/
		protected function composition_query(array $data_lessons):string
		{	
			$query = "DELETE FROM lessons WHERE ";
			foreach ($data_lessons as $value) {
				if ($data_lessons[0] != $value) $query .= ' OR ';
				$query .= 'id = '.$value['id'];
			}
			return $query;
		}
	}
?>