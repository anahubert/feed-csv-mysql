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

class DirUtils extends Utils{

		/**
		 * Creates new dir or if exists do nothing
		 * @param string $dir apsolute dir path
		 * @param array $args list of arguments
		 * @return bool true | false
		 */
		public function createDir($dir, $args = array()){
			@mkdir($dir, 0777, true);
			return is_dir($dir);
		}

		/**
		 * Chmod dir
		 * @param string $dir apsolute dir path
		 * @param octal $mode
		 * @return bool true | false
		 */
		public function chmodDir($dir, $mode = 0777){

			return chmod($dir, $mode);

		}

		/**
		 * Removes  dir or if exists do nothing
		 * @param string $dir apsolute dir path
		 * @param array $args list of arguments
		 * @return bool true | false
		 */
		public function removeDir($dir, $args = array()){
			@rmdir($dir);
			return !is_dir($dir);
		}

		/**
		 * Removes  dir or if exists do nothing
		 * @param string $dir apsolute dir path
		 * @param array $args list of arguments
		 * @return bool true | false
		 */
		public function nixRemoveDir($dir, $args = array()){

			$ext = 1;
			$out = array();

			exec("rm -rf " . $dir, $out, $ext);

			if($ext > 0){
				return false;
			}

			return true;

		}

		/**
		 * Chmod dir
		 * @param string $dir apsolute dir path
		 * @param octal $mode
		 * @return bool true | false
		 */
		public function nixChmodDir($dir){

			$ext = 1;
			$out = array();

			exec("chmod -R 777 " . $dir, $out, $ext);

			if($ext > 0){
				return false;
			}

			return true;

		}

		/**
		 * Creates new dir or if exists do nothing
		 * @param string $dir apsolute dir path
		 * @param array $args list of arguments
		 * @return bool true | false
		 */
		public function nixCreateDir($dir){

			$ext = 1;
			$out = array();

			exec("mkdir -p " . $dir, $out, $ext);

			if($ext > 0){
				return false;
			}

			return true;
		}

		/**
		 * Check if directory exists
		 * @param string $dir apsolute dir path
		 * @param array $args list of arguments
		 * @return bool true | false
		 */
		public function isDirectory($dir, $args = array()){
			return is_dir($dir);
		}

		/**
		 * Copies file from source to destination.
		 * Does nothing in case that can not find filepath
		 * @param string $source file path
		 * @param string $destination filepath
		 * @return bool true | false
		 */
		public function copyFile($source, $destination){
			return copy($source, $destination);
		}

		/**
		 * Save contents from file pointer to file on filepath.
		 * @param string $filepath absolute file path
		 * @param string $fp resource file pointer
		 * @return int 1 | 0
		 */
		public function saveFile($filepath, $fp){

			$status = 0;

			try{

				// Open and create file for writting data
				$file_fp = fopen($filepath, 'w');

				fseek($fp, 0);

				$contents = '';

				while (!feof($fp)) { $contents .= fread($fp, 8192); }

				fwrite($file_fp, $contents);

				fclose($file_fp);

				$status = 1;

			}catch(Exception $e){

				print_r($e->getMessage());

			}

			return $status;

		}

		/**
		 * Save contents from file pointer to file on filepath.
		 * @param string $filepath absolute file path
		 * @param string $fp resource file pointer
		 * @return int 1 | 0
		 */
		public function saveToFile($filepath, $contents){

			$status = 0;

			try{

				// Open and create file for writting data
				$file_fp = fopen($filepath, 'w');

				if($file_fp === FALSE){

					Throw new Exception("Can not create the file", 1);

				}
				$fw = fwrite($file_fp, $contents);

				if($fw === FALSE){

					Throw new Exception("Can not write the file", 1);

				}

				fclose($file_fp);

				$status = 1;

			}catch(Exception $e){

				print_r($e->getMessage());

			}

			return $status;

		}



}
