<?php
require('define.php');
try {
  $conn = new PDO("mysql:host=".DBHOST.";dbname=".USERS_DB, DBLOGIN, DBPASS);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // prepare sql and bind parameters
  $stmt = $conn->prepare("INSERT INTO ".USERS_TABLE." (id, name, pass) VALUES (:id, :name, :pass)");
  $stmt->bindParam(':id', $id);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':pass', $pass);

  // insert a row
  $id = null;
  $name = "Doe";
  $pass = "97979797";
  $stmt->execute();

  echo "New records created successfully";
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;

?>