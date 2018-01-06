<?php

/**
 * Class Import
 *
 *
 *
 * @author Aleksandra Hubert
 * @since 23.01.2012.
 *
 * @todo
 *
 */
require("class.query.php");

class Import {

	var $fields = array();
	var $table = null;
	var $type = 'INSERT';
	var $data = array();
	var $columns = array();
	var $filepath = "unknown";
	var $db = null;
	var $start = 0;

	private function formatParams($res = array()) {
		foreach ($res as $key => $value) {
			$keys[] = $key;
			$values[] = ":{$key}";
		}

		$params['keys'] = $keys;
		$params['values'] = $values;

		return $params;
	}

	public function doImport() {

		$db = $this->db->getLink();

		$success = true;

		try {

			$results = $this->data;

			if (empty($results))
				throw new ImportException("Error Processing Import - Empty Dataset", 1);

			$fparams = $this->formatParams($results[0]);

			switch ($this->type) {
				case 'INSERT':
					$sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->table, implode(",", $fparams['keys']), implode(",", $fparams['values']));
					break;
				case 'REPLACE':
					$sql = sprintf("REPLACE INTO %s (%s) VALUES (%s)", $this->table, implode(",", $fparams['keys']), implode(",", $fparams['values']));

					break;
				default:

					break;
			}

			$sth = $db->prepare($sql);

			foreach ($results as $params) {

				if (!$sth->execute($params))
					throw new ImportException("Error Processing Import", 1);
			}
		} catch (Exception $e) {

			Log::printLog($e->getMessage(), Log::LOG_FATAL);

			exit($e->getCode());
		}
	}

	public function bulkImport() {

		$results = array();

		$results = $this->getSqlValues();

		$params = implode(",", $this->columns);
		$values = implode(",", $results);

		$query = "REPLACE INTO " . $this->table . " (" . $params . ") VALUES $values";

		$res = $this->db->getLink()->query($query);

		return $res;
	}

	public function getSqlValues() {

		$values = array();
		$results = array();

		foreach ($this->data as $k => $row) {

			$tmp = array();

			foreach ($this->columns as $index => $column) {

				$tmp[$index] = $row[$column];
			}

			$results[$k] = "(" . implode(",", $tmp) . ")";
		}

		return $results;
	}

	public function loadData() {

		try {

			$db = $this->db->getLink();

			$query = 'LOAD DATA
        				INFILE \'' . $this->filepath . '\'
        				REPLACE
        				INTO TABLE `'.$this->table . '`
        				CHARACTER SET \'UTF8\'
        				FIELDS
	                TERMINATED BY \'' . $this->terminated . '\'
	                ENCLOSED BY \''. $this->enclosed .'\'
	                LINES TERMINATED BY \'' . $this->lines . '\'
	                IGNORE 1 LINES (' . $this->querycols . ') ' . $this->function .';';

			//print_r($query);

			$sth = $db->prepare($query);

			$succ = $sth->execute();

			if (!$succ) {

				var_dump($sth->errorInfo());

				throw new Exception("Error Processing Request. Can not insert into table {$this->table}", 1);
			}

		} catch (Exception $e) {


			Log::printLog($e->getMessage(), Log::LOG_FATAL);

			exit($e->getCode());

		}
	}

	public function deleteData() {

		try {

			$db = $this->db->getLink();

			$query = "DELETE FROM $this->table WHERE filepath = '{$this->filepath}'";

			//print_r($query);

			$sth = $db->prepare($query);

			$succ = $sth->execute();

			if (!$succ) {

				var_dump($sth->errorInfo());

				throw new Exception("Error Processing Request. Can not delete from table {$this->table}", 1);
			}

		} catch (Exception $e) {


			Log::printLog($e->getMessage(), Log::LOG_FATAL);

			exit($e->getCode());

		}
	}

	public function validate() {

		try {

			$db = $this->db->getLink();

			$out = array();
			$ext = 1;

			exec("wc -l {$this->filepath}", $out, $ext);


			if(empty($out) || $ext > 0){

				Throw new Exception("Couldn't count rows in csv", 1);

			}

			$tmp = explode(" ", $out[0]);
			$cnt1 = ((int) $tmp[0]) - 1; // skip header line


			$q = "SELECT count(*) as cnt FROM {$this->table} WHERE filepath = '{$this->filepath}' LIMIT 1";

			$sth = $db->prepare($q);
			$success = $sth->execute();

			$row = $sth->fetch(PDO::FETCH_ASSOC);

			if ($success == false) {

				Throw new Exception("Fail to run '$q'" , 1);
			}

			$cnt2 = $row["cnt"];

			if($cnt1 == 0 || $cnt2 == 0){

				Throw new Exception("Empty {$cnt1}/{$cnt2}.", 1);

			}
			if ($cnt1 != $cnt2) {

				Throw new Exception("Number of CSV lines ({$cnt1}) differs from number of imported ({$cnt2}).", 1);

			}

			Log::printLog("Number of CSV lines ({$cnt1}). Number of imported ({$cnt2}).", Log::LOG_INFO);

		} catch (Exception $e) {

			Log::printLog($e->getMessage(), Log::LOG_FATAL);

			exit($e->getCode());
		}
	}

}

/**
 * Class ImportException
 *
 *
 *
 * @author Aleksandra Hubert
 * @since 18.06.2012.
 *
 * @todo
 *
 */
class ImportException extends Exception {

	/**
	 * Constructor for ImportException.
	 * @param string $error an optional error message
	 * @param string $httpCode an optional status code of the response
	 */
	public function __construct($error = NULL, $httpCode = NULL) {

		if (empty($error)) {
			$error = 'Importing failed with status code ' . $httpCode;
		}

		parent::__construct($error, $httpCode);
	}

}
