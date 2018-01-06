<?php

class DbManager{

	private $_link = null;

    public $status = 0;

	public $host;

	public $user;

	public $pass;

	public $dbname;

	public function __construct() {

	}

	public function getLink() {

		return $this->_link;
	}

	public function connect() {

		$dsn = "mysql:dbname=".$this->dbname.";host=".$this->host."";

		try {

			$this->_link = new PDO($dsn, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			$this->status = 1;

		} catch (PDOException $e) {

			Log::printLog($e->getMessage(), 1);
			exit($e->getCode());
		}
	}

	public function connected(){

		return $this->status;

	}

	public function close(){

		$this->_link = null;
		unset($this->_link);

	}

}
?>
