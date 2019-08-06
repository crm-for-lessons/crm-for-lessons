<?php
	/*
	* класс для быстрых запросов SQL
	* при необходимости можно добавить что нибудь для защиты
	* пришлось отказаться от статической функции (например SQLRequest::send()),
	* потому конструктор не запускался. а хотелось бы засунуть подключения и отключение к БД
	* в конструктор и деструктор для красиво записи
	*/
	interface iSQLRequest //запросы к БД
	{
		public function set_fetch_assoc(bool $bool); //true == assoc; false == row - смена типа получаемых данных
		public function send($query); //отправка запроса без ответа
		public function multi_send($query);
		public function get_data_as_array($query) : array; //отправка запроса с ответом
	}

	class SQLRequest implements iSQLRequest
	{
		private $host;
		private $data_base;
		private $user;
		private $password;
		private $fetch_function = 'fetch_assoc'; //функция получаемых данных

		function __construct() { //подключение к бд
			global $CONST_db_host, $CONST_db_user, $CONST_db_password, $CONST_db_data_base, $LOCAL_DB_connect_error;

			$this->host = $CONST_db_host;
			$this->data_base = $CONST_db_data_base;
			$this->user = $CONST_db_user;
			$this->password = $CONST_db_password;

			$this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->data_base);
      if ($this->mysqli->connect_errno) 
    		throw new Error($LOCAL_DB_connect_error . $mysqli->connect_error);
     	$this->mysqli->set_charset("utf8");
		}

		function __destruct() { //отключение от бд
			$this->mysqli->close();
		}

		public function send($query) {
			$result = $this->mysqli->query($query);
			if (!$result) {
    		throw new Error("ошибка: (" . $this->mysqli->errno . ") " . $this->mysqli->error);
			}	else return $result;
		}
		public function multi_send($query){
			return $this->mysqli->multi_query($query);
		}

		public function get_data_as_array($query) : array {
			$query_result = $this->send($query);
			return $this->convert_result_to_array($query_result);
		}

		public function set_fetch_assoc(bool $bool) {
			$this->fetch_function = ($bool) ? 'fetch_assoc' : 'fetch_row';
		}

		public function get_mysqli(){
			return $this->mysqli;
		}

		private function convert_result_to_array($query_result):array { //конвертация результата в массив
			$result_array = [];
			$fetch_function = $this->fetch_function;
			while ($row = $query_result->$fetch_function())
				array_push($result_array, $row);
			return $result_array;
		}
	}
?>