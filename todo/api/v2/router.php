<?php
require('../../functions.php');
require('../../define.php');


function main_code(){
	if (isset($_GET['action'])){
		$action = $_GET['action'];
		$items_json = "";
	}
	else{
		exit();
	}
	if ($action == 'login'){
		if (checkUserInsqlDB()){
			$items_json = '{"ok":"true"}';
		}
	}
	elseif ($action == 'register'){
		if(logOut()){
			if (addUserTosqlDB()){
				$items_json = '{"ok":"true"}';
			}
		}
	}
	elseif ($action == 'logout'){
		setcookie("name", $_COOKIE['name'], array("SameSite"=>"None", "Secure"=>"true", "expires" => 1));
		unset($_SESSION['name']);
		unset($_COOKIE['name']);
		$items_json = '{"ok":true}';
	}
	elseif ($action == 'getItems'){
		$new_array = array("items" => []);
		if(isset($_SESSION['name'])){
			$post_data['login'] = $_SESSION['name'];
		}
		else{
			exit();
		}
		$link = mysqli_connect(DBHOST, DBLOGIN, DBPASS, USERS_DB);
		if ($link){
			$sql = "SELECT id, text, checked FROM ".$post_data['login'];
			$resp = mysqli_query($link, $sql);
			$i = 0;
			if ($resp){
				while($row = mysqli_fetch_assoc($resp)) {
					$new_array['items'][$i] = array('id'=>$row['id'], 'text'=>$row['text'], 'checked'=>$row['checked']>0);
					$i++;
				}
			}
			$items_json = json_encode($new_array);
		}
	}
	elseif ($action == 'deleteItem'){
		$items_json = delItemFromsqlDB();
	}
	elseif ($action == 'addItem'){
		$items_json = addItemInsqlDB();
	}
	elseif ($action == 'changeItem'){
		$items_json = changeItemInsqlDB();
	}
	if ($items_json){
			header('Content-Type: application/json');
			echo $items_json;
		}

}

corsHeaders('main_code');
?>