<?php
	/*
	* запуск команд, а так же их отмена и возврат *
	* @command - потомки класса Command
	* @JSON_data_command - данные для команды в формате JSON
	*/

	interface iUserCommand
	{
		public function perform($command, string $JSON_data_command); //выполнить команду
		public function undo(); //отменить последнюю команду
		public function redo(); //вернуть последнюю отменённую команду
	}

	class UserCommand extends User implements iUserCommand 
	{
		// колличество секунд, которое даётся пользователю, чтобы отменить действие или срок годности команды
		private $cancel_duration; 

		function __construct()
		{
			global $CONST_undo_duration;
			$this->cancel_duration = $CONST_undo_duration;
		}

		public function perform($command, string $JSON_data_command)
		{
			//декодирование данных
			$data_command = json_decode($JSON_data_command, true);

			//выполняем команду
			$changes = $command->execute($data_command);

			//добавляем в БД данные команды
			return $this->push_backup_command($changes, get_class($command));
		}

		public function undo()
		{
			global $LOCAL_error_command_not_found;
			$id_user = $this->get_id();

			//получаем последнюю команду без возврата
			$query = "SELECT * FROM commands WHERE id_user = '$id_user' AND existence_undo = 0 ORDER BY id DESC LIMIT 1";
			@$last_command = (new SQLRequest())->get_data_as_array($query)[0]; 

			//проверка команды на возможность отмены
			$this->check_command($last_command, $LOCAL_error_command_not_found);

			//выполняем обратную команду
			(new $last_command['name_command'])->un_execute(json_decode($last_command['data'], true)); 

			//ставим ключ в бд, что это команда отменена
			$this->set_key($last_command['id'], 1);
		}
		public function redo(){
			global $LOCAL_error_undo_not_found;
			$id_user = $this->get_id();

			//получаем последнюю отменненую команду
			$query = "SELECT * FROM commands WHERE id_user = '$id_user' AND existence_undo = 1 ORDER BY id LIMIT 1";
			@$last_undo_command = (new SQLRequest())->get_data_as_array($query)[0]; 

			//проверка команды на возможность возврата
			$this->check_command($last_undo_command, $LOCAL_error_undo_not_found);

			//выполняем повтор команды
			(new $last_undo_command['name_command'])->execute(json_decode($last_undo_command['data'], true));

			//выставляем ключ в БД, что команда готова к отмене
			$this->set_key($last_undo_command['id'], 0);
		}

		//проверка команды на возможность возврата
		private function check_command($command, $error_text) 
		{
			if (!isset($command)) Answer::error($error_text); 
			$this->check_time($command['datetime']);
		}

		//установка ключа в бд
		private function set_key(int $id_command, int $existence_undo) 
		{
			$query = "UPDATE commands SET existence_undo = $existence_undo WHERE id = $id_command";
			(new SQLRequest())->send($query);
		}

		//сохраняем команду для возможности отмены и возвращаем данные для использования в фронте
		private function push_backup_command(array $changes, string $name_command) 
		{ 
			$id_user = $this->get_id();

			//удаляем записи, который можно было использовать для $this->redo();
			$query = "DELETE FROM commands WHERE id_user = '$id_user' AND existence_undo = 1"; 
			(new SQLRequest())->send($query); 

			//сохраняем выполненную команду в БД
			$JSON_changes = json_encode($changes, JSON_UNESCAPED_UNICODE);
			$query = "INSERT INTO `commands`(`id`, `name_command`, `id_user`, `data`, `datetime`, `existence_undo`) VALUES ('', '$name_command', '$id_user', '$JSON_changes', current_timestamp, '0')";
			(new SQLRequest())->send($query);

			return $JSON_changes;
		}

		//проверка на успеваемость пользователя отменить команду
		private function check_time(string $datetime) 
		{
			global $LOCAL_check_time_error;

			$server_datetime = new DateTime();
			$command_datetime = new DateTime($datetime);
			$difference = $server_datetime->diff($command_datetime);
			$difference_seconds = $this->to_seconds($difference);

			if ($difference_seconds > $this->cancel_duration)
				Answer::Error($LOCAL_check_time_error.$this->cancel_duration);
		}

		//временной интервал переводит в секунды
		private function to_seconds($interval) 
    { 
      return ($interval->y * 365 * 24 * 60 * 60) + 
             ($interval->m * 30 * 24 * 60 * 60) + 
             ($interval->d * 24 * 60 * 60) + 
             ($interval->h * 60 * 60) + 
             ($interval->i * 60) + 
              $interval->s; 
    } 
	}
?>