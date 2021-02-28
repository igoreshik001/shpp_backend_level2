<?php

//---------------------logout----------------------------------
function logOut(){
	if (isset($_COOKIE['name'])){
		setcookie("name", $_COOKIE['name'], array("SameSite"=>"None", "Secure"=>"true", "expires" => 1));
		unset($_COOKIE['name']);
	}
	unset($_SESSION['name']);
	return true;
}
//-------------------------------------------------------------


//---------------------check or put last id--------------------
function lastID($json_file = "lastid.json", $id = false){
	$lastid_json = json_decode(file_get_contents($json_file, true), true);

	if ($lastid_json){
		if ($id){
			$lastid_json['id']+= 1;
			file_put_contents($json_file, json_encode($lastid_json));
		}
		return $lastid_json['id'];
	}
	else{
		return false;
	}
}
//----------------------


//---------------------add item to jsonDB--------------------
function addItemToDB($json_file = "items.json", $new_item = false){
	$json_file = $_SESSION['name'].ITEMS_JSON;
	$items_json = file_get_contents($json_file, true);
	$new_item = file_get_contents('php://input', true);

	if ($items_json){
		$items_array = json_decode($items_json, true);
		$new_item = json_decode($new_item, true);
		$id = lastID(id: true);
		$last = count($items_array['items']);
		$items_array['items'][$last] = array("id" => $id, "text" => $new_item["text"], "checked" => true);
		file_put_contents($json_file, json_encode($items_array));
		
		return '{"id":"' . $id . '"}';
		
	}
}
//----------------------


//---------------------del item from jsonDB--------------------
function delItemFromDB($json_file = "items.json", $del_item = false){
	$json_file = $_SESSION['name'].ITEMS_JSON;
	$items_json = file_get_contents($json_file, true);
	$del_item = file_get_contents('php://input', true);

	if ($items_json){
		$items_array = json_decode($items_json, true);
		$del_item = json_decode($del_item, true);
		$a = [];
		$i = 0;
		$new_array = array("items" => $a);
		foreach ($items_array['items'] as $key => $value) {
			if ($value['id'] != $del_item['id']){
				$new_array['items'][$i] = $value;
				$i++;
			}
		}
		file_put_contents($json_file, json_encode($new_array));
		
		return '{"ok":true}';
		
	}
}
//----------------------



//---------------------change item in jsonDB--------------------
function changeItemInDB($json_file = "items.json", $change_item = false){
	$json_file = $_SESSION['name'].ITEMS_JSON;
	$items_json = file_get_contents($json_file, true);
	$change_item = file_get_contents('php://input', true);

	if ($items_json){
		$items_array = json_decode($items_json, true);
		$change_item = json_decode($change_item, true);
		$a[0] = (0);
		$i = 0;
		$new_array = array("items" => $a);
		foreach ($items_array['items'] as $key => $value) {
			if ($value['id'] == $change_item['id']){
				$new_array['items'][$i] = $change_item;
				$i++;
			}
			else{
				$new_array['items'][$i] = $value;
				$i++;
			}
		}
		file_put_contents($json_file, json_encode($new_array));
		
		return '{"ok":true}';
		
	}
}
//----------------------


//---------------------add to jsonDB--------------------
function addUserToDB($json_file){
	$users_json = file_get_contents($json_file, true);
	$post_data = json_decode(file_get_contents('php://input'), true);

	if ($users_json){
		if (strpos($users_json, $post_data['login']) < 1 && strpos($users_json, ($post_data['pass'])."") < 1){
			$users_array = json_decode($users_json, true);
			$users_array[$post_data['login']] = $post_data['pass'];
			$users_json = json_encode($users_array);
			file_put_contents($json_file, $users_json, true);
			file_put_contents("serv-api-v2/".$post_data['login'].ITEMS_JSON, "{\"items\":[]}", true);
			return true;
		}
	}
}
//----------------------


//---------------------check is user in BD jsonDB--------------------
function checkUserInDB($json_file){
	$users_json = file_get_contents($json_file, true);
	$post_data = json_decode(file_get_contents('php://input'), true);


	if ($users_json){
		if (strpos($users_json, "\"" . $post_data['login'] . "\"") > 0){
			$users_array = json_decode($users_json, true);
			if ($users_array[$post_data['login']] == $post_data['pass']){
				if(!isset($_SESSION['name']) && isset($_COOKIE['name'])){
					$_SESSION['name'] = $_COOKIE['name'];
				}
				else{
					$_SESSION['name'] = $post_data['login'];
					setcookie("name", $post_data['login'], array("SameSite"=>"None", "Secure"=>"true", "expires" => time() + 3600*24*7));
				}
				
				return true;
			}
		}
	}
}
//----------------------






// ------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------------





//---------------------add item in sqlDB--------------------
function addItemInsqlDB(){
	$user_table = $_SESSION['name'];
	$add_item = file_get_contents('php://input', true);
	$add_item = json_decode($add_item, true);
	$link = mysqli_connect(DBHOST, DBLOGIN, DBPASS, USERS_DB);
	if ($link){
		$sql = "INSERT INTO ".$user_table." (`id`, `text`, `checked`) VALUES (NULL, '".$add_item['text']."', '0')";
		$resp = mysqli_query($link, $sql);
		if ($resp){
			$sql = "SELECT `id` FROM ".$user_table." WHERE `text`='".$add_item['text']."'";
			$resp = mysqli_query($link, $sql);
			$row = mysqli_fetch_assoc($resp);
			return '{"id":'.$row['id'].'}';
		}
	}
	mysqli_close($link);
}
//----------------------


//---------------------change item in sqlDB--------------------
function changeItemInsqlDB(){
	$user_table = $_SESSION['name'];
	$change_item = file_get_contents('php://input', true);
	$change_item = json_decode($change_item, true);
	$link = mysqli_connect(DBHOST, DBLOGIN, DBPASS, USERS_DB);
	if ($link){
		$sql = "UPDATE ".$user_table." SET text = '".$change_item['text']."', checked = '".$change_item['checked']."' WHERE ".$user_table.".id = ".$change_item['id'];
		$resp = mysqli_query($link, $sql);
		if ($resp){
			return '{"ok":true}';
		}

	}
	mysqli_close($link);
}
//----------------------


//---------------------del item from sqlDB--------------------
function delItemFromsqlDB(){
	$user_table = $_SESSION['name'];
	$del_item = file_get_contents('php://input', true);
	$del_item = json_decode($del_item, true);
	$link = mysqli_connect(DBHOST, DBLOGIN, DBPASS, USERS_DB);
	if ($link){
		$sql = "DELETE FROM ".$user_table." WHERE ".$user_table.".`id` = ".$del_item['id'];
		$resp = mysqli_query($link, $sql);
		if ($resp){
			mysqli_close($link);
			return '{"ok":true}';
		}

	}
	mysqli_close($link);
}
//----------------------


//---------------------add to mysqlDB--------------------
function addUserTosqlDB(){
	$post_data = json_decode(file_get_contents('php://input'), true);

	$link = mysqli_connect(DBHOST, DBLOGIN, DBPASS, USERS_DB);
	if ($link && $post_data){
		$sql = "SELECT name,pass FROM ".USERS_TABLE." WHERE name='".$post_data['login']."'";
		$resp = mysqli_query($link, $sql);
		if ($resp->num_rows > 0){
			return false;
		}
		else{
			$sql = "INSERT INTO ".USERS_TABLE." (`id`, `name`, `pass`) VALUES (NULL, '".$post_data['login']."', '".$post_data['pass']."')";
			if (mysqli_query($link, $sql)){
				$sql = "CREATE TABLE ".USERS_DB.".".$post_data['login']." ( `id` INT NOT NULL AUTO_INCREMENT , `text` TEXT NOT NULL , `checked` BOOLEAN NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
				mysqli_query($link, $sql);
				mysqli_close($link);
				return true;
			}
		}
	}
	mysqli_close($link);
	
}
//----------------------


//---------------------check in mysqlDB--------------------
function checkUserInsqlDB(){
	$post_data = json_decode(file_get_contents('php://input'), true);

	$link = mysqli_connect(DBHOST, DBLOGIN, DBPASS, USERS_DB);
	if ($link && $post_data){
		$sql = "SELECT name,pass FROM ".USERS_TABLE." WHERE name='".$post_data['login']."'";
		$resp = mysqli_query($link, $sql);
		$row = mysqli_fetch_assoc($resp);
		if ($resp->num_rows >0 && $row['pass'] == $post_data['pass']){
			$_SESSION['name'] = $post_data['login'];
			setcookie("name", $post_data['login'], array("SameSite"=>"None", "Secure"=>"true", "expires" => time() + 3600*24*7));
			mysqli_close($link);
			return true;
		}
		else{
			mysqli_close($link);
			return false;
		}
	}
	mysqli_close($link);
	return false;
}
//----------------------


function corsHeaders($doThisCode){
	if ($_SERVER['REQUEST_METHOD'] == "OPTIONS"){
		if ($_SERVER['HTTP_ORIGIN'] == HTTPS."localhost"){
			header('Access-Control-Allow-Origin: '.HTTPS.'localhost');
			header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
			header('Access-Control-Allow-Headers: Content-Type');
			header('Access-Control-Allow-Credentials: true');
			// header('Access-Control-Max-Age: 3600');
		}
		else{
			header("HTTP/1.1 403 Access Forbidden");
			header("Content-Type: text/plain");
			echo "You cannot repeat this request";
		}

	}
	elseif ($_SERVER['REQUEST_METHOD'] != "OPTIONS"){
		if ($_SERVER['HTTP_ORIGIN'] == HTTPS."localhost"){
			session_set_cookie_params(['SameSite'=>'None', 'Secure'=>'true']);
			session_start();
			header('Access-Control-Allow-Origin: '.HTTPS.'localhost');
			header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
			header('Access-Control-Allow-Headers: Content-Type');
			header('Access-Control-Allow-Credentials: true');


			$doThisCode();
		}
		else{
			die("POSTing Only Allowed from ".HTTPS."localhost");
		}
	}
	else{
		die("No Other Methods Allowed");
	}
}


?>