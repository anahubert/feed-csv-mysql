<?php
$conf["emptycheck"] = 1;

$conf["csv"] = array(
	"delimiter" => ",",
	"enclousure" => "\"",
	"fields" =>  array(
		"book_url",
		"book_title",
		"book_author",
		"book_bigimg",
		"book_smallimg",
		"book_category0",
		"book_category1",
		"book_category2",
		"book_category3",
		"book_category4",
		"book_format",
		"book_cover",
		"book_pages",
		"book_letter",
		"book_published",
		"book_isbn",
		"book_description",
		"book_price",
		"book_price_web"
	),
	"isheader" => true
);

$conf["db"] = array(
	"table1" => "biblio_books_laguna",
	"table2" => "bbl_test",
	"fields" => array(
		"book_url",
		"book_title",
		"book_author",
		"book_bigimg",
		"book_smallimg",
		"book_category0",
		"book_category1",
		"book_category2",
		"book_category3",
		"book_category4",
		"book_format",
		"book_cover",
		"book_pages",
		"book_letter",
		"book_published",
		"book_isbn",
		"@`book_description`",
		"@`book_price`",
		"@`book_price_web`",
		"@filepath"
	),
	"enclosed" => "\"",
	"terminated" => ",",
	"lines" => "\n",
	"function" => 'SET '
	. '`book_description`=REPLACE(@`book_description`, \'“\', \'\\"\'),'
	. '`book_price` = REPLACE(@`book_price`, \',\', \'.\'), '
	. '`book_price_web` = REPLACE(@`book_price_web`, \',\', \'.\')'
	. '`filepath` = \'' . $conf["dir"]["arch"] . '/' . $conf["dir"]["filename"] . '\''
);

?>
