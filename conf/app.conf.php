<?php

date_default_timezone_set('Europe/Belgrade');

/**
 * access data for db connections
 */
$_app_context = "";

if(!isset($_SERVER['APP_ENV']) ) {
	print("Please set APP_ENV!!!\n");
	exit(7);
}

$_app_context = $_SERVER['APP_ENV'];

$_ENV["APP_ROOT"] = dirname(__DIR__);
