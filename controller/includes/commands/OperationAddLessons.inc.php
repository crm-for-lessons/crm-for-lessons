<?php
	/*
	* операция добавления урока
	*/
	class OperationAddLessons extends Operation 
	{
		public function use(array $data_lessons) 
		{
			//формирование мульти запроса в БД
			$query = '';
			foreach ($data_lessons as $data_one_lesson)
				$query .= $this->composition_one_query($data_one_lesson);
			$SQL = new SQLRequest();
			$SQL->multi_send($query);
			$mysqli = $SQL->get_mysqli();

			//возврат массива данных вместе с ID
			return $this->write_id_in_array($data_lessons, $mysqli);
		}

		/*
		* добавление id, сгенерируемых БД, в выходной массив
		*/
		protected function write_id_in_array(array $data_lessons, mysqli $mysqli):array
		{
			foreach ($data_lessons as $key => $value) {
				$data_lessons[$key]['id'] = $mysqli->insert_id;
				$mysqli->next_result();
			}
			return $data_lessons;
		}
		
		/*
		* формирование одного запроса в БД для добавления урока
		* @data_one_lesson - данные вставляемого урока
		*/
		protected function composition_one_query(array $data_one_lesson):string
		{
			$query = "INSERT INTO lessons VALUES (";
			foreach ($this->columns_commands as $value) {
				if ($this->columns_commands[0] != $value) $query .= ", ";
				if (($value == 'id')||($value == 'comment')||($value == 'availability'))
					$query .= "'".($data_one_lesson[$value] ?? '')."'";
				else
					$query .= "'".$data_one_lesson[$value]."'";
			}
			$query .= "); ";
			return $query;
		}
	}
?>