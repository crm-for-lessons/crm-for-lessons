<?php
	interface iUserInitialization
	{
		public function auth(string $email, string $password):bool; //авторизация по емаил и паролю
		public function out(); //выход из системы
	}
	class UserInitialization extends User implements iUserInitialization, iUser
	{
		public function auth(string $email, string $password):bool
		{
			if ($this->is_auth()) return true;

			$query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
			$result = (new SQLRequest())->get_data_as_array($query);

			if (isset($result[0])) {
				$this->save_data_in_SESSION($result[0]);
				return true;
			} else return false;
		}

		public function out()
		{
			$_SESSION = array(); 
      session_destroy();
		}

		private function save_data_in_SESSION(array $database_answer) //кидаем инфу пользователя в сессию
		{
			foreach ($database_answer as $key => $value)
				$_SESSION[$key] = $value;
			$_SESSION['is_auth'] = true;
		}
	}
?>