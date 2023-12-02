<?php

// To use PDO to connect to a database, you need the following information:
$servname = "localhost:3307";
$username = "root";
$password = "";
$dbname = "CRIME_TRACKER";

// database connection
$conn = mysqli_connect($servname, $username, $password, $dbname);
if(!$conn){
	echo "Connection failed";
}


?>
