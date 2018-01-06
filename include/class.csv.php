<?php


/* Class CSV
 *
 * Parses CSV file and return associative array
 * with header as keys and data as values
 *
 * @author Aleksandra Hubert
 * @since 18.06.2012.
 *
 * @todo
 *
 */
class Csv{

	public $filepath = "/tmp/xcd";
	public $filetype = "csv";
	public $delimiter = ",";
	public $enclousure = "\"";
	public $fields = array();
	public $isheader = true;

	public function combine() {

		$results = array();

		if ($this->filetype === "csv" && $this->filepath != "" && is_file($this->filepath) && filesize($this->filepath) && !empty($this->fields)) {

			if (($handle = fopen($this->filepath, "r")) !== false) {

				if ($this->isheader){

					$headers = fgetcsv($handle, 0, $this->delimiter, $this->enclousure);

					if($headers !== $this->fields){

						throw new CSVException(sprintf("Header line %s in CSV file doesn't match fields %s.)\n", implode(",", $headers), implode(",", $this->fields)), 1);

					}

				}

				while ($data = fgetcsv($handle, 0, $this->delimiter, $this->enclousure) !== false) {
					// Validate number of fileds

					if (count($this->fields) !== count($data))
						throw new CSVException("CSV File is not well formatted (fields omitted)", 1);

					// Create array with db column names as keys and data as values
					$results[] = array_combine($this->fields, $data);
				}
				fclose($handle);
			} else {
				throw new CSVException("Can not open file " . $this->filepath . " for reading.");
			}
		} else {
			throw new CSVException("File is empty or not a regular CSV file.", 1);
		}

		return $results;
	}

	public function decode(){

		if(!is_file($this->filepath) || filesize($this->filepath) === 0){

			throw new CSVException("File is empty or not exists.\n");

		}

		$content = file_get_contents($this->filepath);

		$decoded = html_entity_decode($content);

		$fwrite = file_put_contents($this->filepath, $decoded);

		if($fwrite === false){

			throw new CSVException("Could not htmlDecode file.");

		}

	}

}

/* ClassCSVException
 *
 *
 *
 * @author Aleksandra Hubert
 * @since 18.06.2012.
 *
 * @todo
 *
 */

class CSVException extends Exception {

	/**
	 * Constructor for CSVException.
	 * @param string $error an optional error message
	 * @param string $code an optional status code of the response
	 */
	public function __construct($error = null, $code = 1) {

		if (empty($error)) {

			$error = "Failed with status code " . $code;

		}

		parent::__construct($error, $code);
	}
}

?>
