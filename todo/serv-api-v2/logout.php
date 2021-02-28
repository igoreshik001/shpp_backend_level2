<?php

require('../functions.php');
require('../define.php');

function main_code(){
	setcookie("name", $_COOKIE['name'], array("SameSite"=>"None", "Secure"=>"true", "expires" => 1));
	unset($_SESSION['name']);
	unset($_COOKIE['name']);
	echo '{"ok":true}';
}

corsHeaders('main_code');

?>