<?php

require('functions.php');
require('define.php');

function main_code(){
	
	if (checkUserInsqlDB()){
		$js = '{"ok":"true"}';
		header('Content-Type: application/json');
		echo $js;
	}
}

corsHeaders('main_code');

?>