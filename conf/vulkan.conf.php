<?php

$CSV_FIELDS = array(
	);
$CSV_FIELDS_SEPARATOR = ",";

$DB_FIELDS = array(
	"category_id",
	"category_name",
	"category_url",
	"book_id",
	"book_title",
	"book_url",
	"book_author",
	"book_publisher",
	"@`book_published_web`",
	"book_isbn",
	"book_published",
	"book_format",
	"book_num_pages",
	"book_paperback",
	"book_letter",
	"@`book_price`",
	"@`book_price_web`",
	"@`book_description`",
	"book_image_small",
	"book_image_big",
);

$SET_FUNCTION = 'SET `book_description`=REPLACE(@`book_description`, \'“\', \'\\"\'), `book_published_web`=str_to_date(@`book_published_web`, \'%d.%m.%Y.\'), `book_price` = REPLACE(@`book_price`, \',\', \'.\'), `book_price_web` = REPLACE(@`book_price_web`, \',\', \'.\')';

$CSV_FILEDS_TERMINATED_BY = '\',\'';
$CSV_FILEDS_ENCLOSED_BY = '\'"\'';
$CSV_LINES_TERMINATED_BY = '\'\n\'';

$CSV_CHECK_HEADER = 0;

$TABLE = 'biblio_books_vulkan';
$HISTORY_TABLE = '';

$conf["emptycheck"] = 1;

?>
