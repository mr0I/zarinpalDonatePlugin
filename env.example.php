<?php

$variables = [
	'IS_DEV' => '0' ,// use sandbox or not (boolean)
	'PAGINATE_NUM' => 10 // number of pages in paginations
];

foreach ($variables as $key => $value) {
	putenv("$key=$value");
}

?>