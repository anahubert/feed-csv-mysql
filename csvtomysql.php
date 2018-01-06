<?php

/**
 * Imports csv feed to mysql
 *
 * @author Aleksandra Hubert
 * @since September, 2012
 */
include_once(__DIR__ . '/conf/app.conf.php');
include_once(__DIR__ . '/conf/db.conf.php');
require_once(__DIR__ . '/include/utils.php');
require_once(__DIR__ . '/include/dirutils.php');
require_once(__DIR__ . '/include/class.log.php');
require_once(__DIR__ . '/include/class.validator.php');
require_once(__DIR__ . '/include/class.database.php');
require_once(__DIR__ . '/include/class.csv.php');
require_once(__DIR__ . '/include/class.import.php');

try {

	$args = parseParamsFromCli();

	Validator::arch($args);
	Validator::failed($args);
	Validator::source($args);
	Validator::conf($args);

	require_once(__DIR__ . "/conf/" . $args['conf'] . ".conf.php");

	$_db = new DbManager();
	$_db->host = $settings["host"];
	$_db->user = $settings["user"];
	$_db->pass = $settings["pass"];
	$_db->dbname = $settings["name"];

	$_db->connect();

	$dh = opendir($args["source"]);

	$_csv = new Csv();

	$_csv->delimiter = $conf["csv"]["delimiter"];
	$_csv->enclousure = $conf["csv"]["enclousure"];
	$_csv->fields = $conf["csv"]["fields"];
	$_csv->isheader = $conf["csv"]["isheader"];

	while ($filepath = readdir($dh) && is_file($filepath)) {

		$_csv->filepath = $filepath;

		$_csv->decode();
		$data = $_csv->parse();

		Validator::drop($db, $conf["db"]["table2"]);
		Validator::create($db, $conf["db"]["table1"], $conf["db"]["table2"]);
		Validator::import($db, $filepath, $conf["db"]["table2"], $conf);
		Validator::validate($db, $data, $conf["db"]["table2"]);
		Validator::import($db, $filepath, $conf["db"]["table1"], $conf);
	}

	$_db->close();

} catch (Exception $ex) {

	Log::printLog($ex->getMessage(), Log::LOG_FATAL);

	exit($ex->getCode());
}
