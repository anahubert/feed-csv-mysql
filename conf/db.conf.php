<?php

$settings = array("user" => "", "pass" => "", "host" => "", "name" => "");

switch ($_app_context) {
	case "test":
		$settings['user'] = "";
		$settings['pass'] = "";
		$settings['host'] = "";
		$settings['name'] = "";
		break;
	case "development":
		$settings['user'] = "";
		$settings['pass'] = "";
		$settings['host'] = "";
		$settings['name'] = "";
		break;
	case "integration":
		$settings['user'] = "";
		$settings['pass'] = "";
		$settings['host'] = "";
		$settings['name'] = "";
		break;
	case "production":
		$settings['user'] = "";
		$settings['pass'] = "";
		$settings['host'] = "";
		$settings['name'] = "";
		break;
	default:
		$settings['user'] = "";
		$settings['pass'] = "";
		$settings['host'] = "";
		$settings['name'] = "";
}
