<?php

$variables = [
	'IS_DEV' => 'use sandbox or not' // boolean
];

foreach ($variables as $key => $value) {
	putenv("$key=$value");
}

?>