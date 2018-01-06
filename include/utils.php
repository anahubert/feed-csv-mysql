<?php
/**
 * DirUtils class
 *
 * @package Utils
 *
 * @class Utils
 *
 * @since July, 2012
 *
 * @author Aleksandra Hubert
 */

class Utils{


	/**
	 * parses the parameters from the comand line interface
	 * eg: you call in cli php script.php -bar=1 -foo=2
	 * parseParamsFromCli() return array('bar' => '1', 'foo' => '2');
	 *
	 * @return $array
	 */
	public static function parseParamsFromCli($param = null){
		$args = $_SERVER['argv'];
		$re = array();
		foreach( $args as $arg ) {

			if(substr($arg,0,1) == '-') {

				$arg = substr($arg,1);

				$arg = explode('=',$arg);

				$tmp = $arg[1];

				$re[$arg[0]] = $arg[1];

				$etmp1 = explode(";", $arg[1]);

				if($etmp1 && !empty($etmp1)) {

					foreach($etmp1 as $val){

						$etmp2 = explode(",", $val);

						if($etmp2 && !empty($etmp2) && count($etmp2) == 2){

							if(!is_array($re[$arg[0]])) $re[$arg[0]] = array();

							array_push($re[$arg[0]], array($etmp2[0] => $etmp2[1]));
						}
					}
				}else{
					$re[$arg[0]] = $tmp;
				}
			} else {
				$re[] = $arg;
			}
		}
		if(isset($re[$param])) return $re[$param];
		return $re;
	}

	public function dos2unix($filepath, $prefix = "tst"){

		try{

			$ext = 1;
			$out = array();
			$tmp = "/tmp/$prefix-tmpfile-dosnix";

			exec("cp '$filepath' $tmp && dos2unix $tmp && cp $tmp '$filepath' && rm -f $tmp", $out, $ext);

			if($ext > 0){

				Throw new Exception("Unable to convert file in unix form", 1);

			}
		}catch(Exception $e){

			$ext = 1;
			$out = array();
			$tmp = "/tmp/$prefix-tmpfile-dosnix";

			exec("rm -f $tmp", $out, $ext);

			if($ext > 0){

				Throw new Exception("Unable to remove file $tmp", 1);

			}

			print $e->getMessage();

			exit($e->getCode());

		}

	}

	public function stripQuoted($string){

		return trim($string, "\"");

	}

}
