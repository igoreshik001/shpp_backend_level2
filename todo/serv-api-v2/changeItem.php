<?php

require('../functions.php');
require('../define.php');

function main_code(){
	$items_json = changeItemInsqlDB();
	if ($items_json){
		header('Content-Type: application/json');
		echo $items_json;
	}
	else{
		echo 'nou';
	}
}

corsHeaders('main_code');

?>