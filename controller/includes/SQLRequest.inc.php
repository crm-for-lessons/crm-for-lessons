<?php
	interface iSQLRequest //запросы к БД
	{
		public function set_fetch_assoc($bool); //true == assoc; false == row - смена типа получаемых данных
		public function send($query); //отправка запроса без ответа
		public function get_data_as_array($query); //отправка запроса с ответом
	}

	class SQLRequest implements iSQLRequest
	{
		private $host = 'localhost';
		private $data_base = 'crm-for-lessons';
		private $user = 'root';
		private $password = '';
		private $fetch_function = 'fetch_assoc'; //функция получаемых данных

		function __construct() { //подключение к бд
			$this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->data_base);
      if ($this->mysqli->connect_errno) 
    		throw new Error("Could not connect to MySQL: " . $mysqli->connect_error);

     	$this->mysqli->set_charset("utf8");
		}

		function __destruct() { //отключение от бд
			$this->mysqli->close();
		}

		public function send($query) {
			return $this->mysqli->query($query);
		}

		public function get_data_as_array($query) {
			$query_result = $this->send($query);
			return $this->convert_result_to_array($query_result);
		}

		public function set_fetch_assoc($bool) {
			if (gettype($bool) !== 'boolean')
				throw new Error('set_fetch_assoc :: invalid input parameter type!');
			$this->fetch_function = ($bool) ? 'fetch_assoc' : 'fetch_row';
		}

		private function convert_result_to_array($query_result) { //конвертация результата в массив
			$result_array = [];
			$fetch_function = $this->fetch_function;
			while ($row = $query_result->$fetch_function())
				array_push($result_array, $row);
			return $result_array;
		}
	}
?>