<?php
/**
 *
 * @author Aleksandra Hubert
 */
class File {

	public $dir;
	public $url;
	public $filename;
	public $filepath;
	public $tempname;
	public $name;
	public $downloaded;
	public $protocol = "http";

	private $type = "crawler";
	protected $response;
	private $_data = "";
	static $MAXTRIES = 3;

	public function download() {

		$num = 0;
		$sucsseded = false;

		$data = array();

		try {


			$msg = sprintf("Fetching data");

			Log::printLog($msg, "run");

			$msg = sprintf("%s", $this->url);

			Log::printLog($msg, "info");


			while ($num < self::$MAXTRIES) {

				$response = "";


				$msg = sprintf("Number of tries: %s", $num + 1);

				Log::printLog($msg, "info");

				try {

					$ch = curl_init($this->url);

					curl_setopt($ch, CURLOPT_CRLF, TRUE);
					curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

					if($this->protocol == "sftp"){

						curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);

					}

					if($this->protocol == "ftps"){

						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
						curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);


					}

					curl_setopt($ch, CURLOPT_ENCODING, "");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


					$response = curl_exec($ch);
					$curl_errno = curl_errno($ch);
					$curl_error = curl_error($ch);

					if ($curl_errno > 0) {
						curl_close($ch);
						throw new Exception("cURL Error ($curl_errno): $curl_error", 1);
					}
					
					curl_close($ch);

					$num = self::$MAXTRIES;

					$sucsseded = true;
				} catch (Exception $e) {

					$sucsseded = false;

					//$msg = sprintf("%s", $e->getMessage());

					//Log::printLog($msg, "warnning");


					$num += 1;

					sleep(6);
				}
			}

			Log::printLog("End of fetching.", "info");

			if (empty($sucsseded)) {
				throw new Exception("Server " . $this->url . " is down.", 1);
			}

			$this->response = $response;

		} catch (Exception $ex) {

			Log::printLog($ex->getMessage(), "fatal error");

			exit($ex->getCode());

		}
	}

	public function save() {

		try {


			$msg = sprintf("Start saving file %s/%s", $this->dir, $this->filename);

			Log::printLog($msg, "info");

			if (!DirUtils::saveToFile($this->dir . "/" . $this->filename, $this->response)) {

				throw new Exception("Can not save to file dir " . $this->dir . "/" . $this->filename, 1);
			}

			$msg = sprintf("File is successfully saved.");

			Log::printLog($msg, "info");

		} catch (Exception $e) {

			Log::printLog($e->getMessage(), "fatal error");

			exit($e->getCode());
		}
	}

	public function isNew() {

		$tempfilepath = $filepath = $this->dir . "/" . $this->tempname;

		if (md5($this->response) == md5(file_get_contents($tempfilepath))) {

			return 0;
		}

		return 1;
	}

	public function create() {

		try {

			if (!DirUtils::createDir($this->dir)) {

				throw new Exception("Can not create dir " . $this->dir, 1);

			}

		} catch (Exception $e) {

			Log::printLog($e->getMessage(), "fatal error");

			exit($e->getCode());

		}
	}

}

class Files extends File {

	public $files = array();

	public function add($file, $type = "") {

		$file->type = $type;

		array_push($this->files, $file);
	}

	public function create() {

		foreach ($this->files as $file) {

			$file->create();
		}
	}

	public function getImages(){

		$res = array();

		foreach($this->files as $file){

			if($file->type == "img"){

				$res[] = $file;
			}

		}

		return $res;

	}

}

?>
