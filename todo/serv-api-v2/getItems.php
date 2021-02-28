<?php

require('../functions.php');
require('../define.php');

function main_code(){
	//-----------------------sql get items-----------------------
	$new_array = array("items" => []);
	$post_data['login'] = $_SESSION['name'];

	$link = mysqli_connect(DBHOST, DBLOGIN, DBPASS, USERS_DB);
	if ($link){
		$sql = "SELECT id, text, checked FROM ".$post_data['login'];
		$resp = mysqli_query($link, $sql);
		$i = 0;
		while($row = mysqli_fetch_assoc($resp)) {
			$new_array['items'][$i] = array('id'=>$row['id'], 'text'=>$row['text'], 'checked'=>$row['checked']>0);
			$i++;
		}
		echo json_encode($new_array);
	}
}

corsHeaders('main_code');

?>