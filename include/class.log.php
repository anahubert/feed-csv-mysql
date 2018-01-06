<?php

/**
 * Log class
 *
 *
 * @author Aleksandra Hubert
 * @since 2013.10.28.
 */

class Log {

	static $BLACK = "\033[0;30m";
	static $DARKGRAY = "\033[1;30m";
	static $BLUE = "\033[0;34m";
	static $LIGHTBLUE = "\033[1;34m";
	static $GREEN = "\033[0;32m";
	static $LIGHTGREEN = "\033[1;32m";
	static $CYAN = "\033[0;36m";
	static $LIGHTCYAN = "\033[1;36m";
	static $RED = "\033[0;31m";
	static $LIGHTRED = "\033[1;31m";
	static $PURPLE = "\033[0;35m";
	static $LIGHTPURPLE = "\033[1;35m";
	static $BROWN = "\033[0;33m";
	static $YELLOW = "\033[1;33m";
	static $LIGHTGRAY = "\033[0;37m";
	static $WHITE = "\033[1;37m";

	const LOG_RUN = "run";
	const LOG_INFO = "info";
	const LOG_ERROR = "error";
	const LOG_FATAL = "fatal error";
	const LOG_WARNNING = "warnning";

	public static function printLog($msg, $level) {

		switch($level){
			case "error":
			case "fatal error":
				$color = self::error();
				break;
			case "info":
				$color = self::info();
				break;
			case "warnning":
				$color = self::warrning();
				break;
			case "run":
				$color = self::run();
				break;
			default:
				$color = self::$BLACK;
		}

		printf("[%s] %s: %s\n", date("Y-m-d H:i:s"), strtoupper($level), $msg);
	}

	public static function info() {

		return self::$BLACK;
	}

	public static function warrning() {

		return self::$CYAN;
	}

	public static function error() {

		return self::$RED;
	}
	public static function run() {

		return self::$GREEN;
	}


}

?>
