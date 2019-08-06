<?php 
	interface iUser
	{
		public function get_id(); //получение id пользователя, если авторизованы
	}
	abstract class User implements iUser
	{
		function __construct()
		{
			session_start();
		}

		public function get_id()
		{
			return 1;
			// if ($this->is_auth())
			// 	return $_SESSION['id'];
			// else false;
		}

		protected function is_auth():bool
		{
			if (isset($_SESSION["is_auth"]))
        return true; 
      else 
      	return false;
		}
	}

?>