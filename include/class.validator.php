<?php

class Validator{

	function arch($args){

		if (!isset($args["arch"]) || !is_dir($args["arch"])) {

			new ValidatorException("Use -arch='/path' to specify archive directory", 1);

		}

	}

	function failed($args){

		if (!isset($args["failed"]) || !is_dir($args["failed"])) {

			new ValidatorException("Use -failed='/path' to specify failed directory", 1);

		}

	}

	function source($args){

		if (!isset($args["source"]) || !is_dir($args["source"])) {

			new ValidatorException("Use -source='/path' to specify source directory", 1);

		}

	}

	function conf($args){

		if (!isset($args['conf'])) {

			new ValidatorException("Use -conf='conf-name' to specify conf name file", 1);
		}

		$dir = __DIR__ . "/conf/" . $args['conf'] . ".conf.php";

		if (!file_exists($dir)) {

			throw new ValidatorException("Dir '". $dir . "' doesn't exist.", 1);

		}

	}

	/**
	 * Drops temporary table
	 * @param &db PDO Object passed by reference
	 * @param $table Temporary table name for dropping
	 * @return void
	 */
	public static function drop(&$db, $table){

		Log::printLog("Drop table $table", Log::LOG_INFO);

		$query = "DROP TABLE IF EXISTS $table;";

		$dbh = $db->prepare($query);
		$dbh->execute();

		if ($dbh->errorCode() !== "00000") {

			$err = implode(",", $dbh->errorInfo());

			Throw new Exception($err, 1);
		}

		unset($dbh);

		Log::printLog("Finished.", Log::LOG_INFO);

	}

	/**
	 * Creates temporary table
	 * @param &db PDO Object passed by reference
	 * @param $table2 Temporary table name to create
	 * @param $table1 Main table name
	 * @return void
	 */
	public static function create(&$db, $table1, $table2){

		Log::printLog("Start create table $table2", Log::LOG_INFO);

		$query = "CREATE TEMPORARY TABLE $table2 LIKE $table1";

		$dbh = $db->prepare($query);
		$dbh->execute();

		if ($dbh->errorCode() !== "00000") {

			$err = implode(",", $dbh->errorInfo());

			Throw new Exception($err, 1);
		}

		unset($dbh);

		Log::printLog("Finished.", Log::LOG_INFO);

	}

	/**
	 * Import data from associative array to table
	 * @param &db PDO Object passed by reference
	 * @param $table1 string Temporary table name to import
	 * @param $head array Indexed table column names
	 * @param $data array Associative array of values to import
	 * @return void
	 */
	public static function import(&$db, $filepath, $table, $conf){

		$start = time();

		Log::printLog("Start import into table $table2", Log::LOG_INFO);

		$_importer = new Import();
		$_importer->start = $start;
		$_importer->db = &$_db;
		$_importer->type = "REPLACE";
		$_importer->querycols = implode(",", $conf["db"]["fields"]);
		$_importer->filepath = $filepath;
		$_importer->table = $table;
		$_importer->terminated = $conf["db"]["fields"];
		$_importer->enclosed = $conf["db"]["enclosed"];
		$_importer->lines = $conf["db"]["lines"];
		$_importer->function = $conf["db"]["function"];

		$_importer->loadData();

		Log::printLog("Finished.", Log::LOG_INFO);

	}

	/**
	 * Main validation. Counts data in array vs imported
	 * @param &db PDO Object passed by reference
	 * @param $table string Temporary table name for counting
	 * @param $data array Associative array of values to count
	 * @return void
	 */
	public static function validate(&$db, $data, $table){

		$query = "SELECT count(*) as cnt FROM $table;";

		$dbh = $db->prepare($query);
		$dbh->execute();

		if ($dbh->errorCode() !== "00000") {

			$err = implode(",", $dbh->errorInfo());

			Throw new Exception($err, 1);
		}

		$row = $dbh->fetchAll(PDO::FETCH_ASSOC);

		unset($dbh);

		$cnt1 = $row[0]["cnt"];
		$cnt2 = count($data);

		if($cnt1 != $cnt2){

			Throw new Exception("Count diffs: cnt1: $cnt1, cnt2: $cnt2", 1);

		}

		Log::printLog("End of validation.", Log::LOG_INFO);
		Log::printLog("Number of imported raws: $cnt1/$cnt2", Log::LOG_INFO);

	}
}

class ValidatorException{

	public function __construct($msg, $code) {

		Log::printLog("Class::ValidatorException; Code::$code", Log::LOG_FATAL);
		Log::printLog($msg, Log::LOG_FATAL);

		exit($code);

	}

}
