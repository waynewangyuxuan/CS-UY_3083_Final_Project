<?php
include "connect.php";
// Set your valid username and password here
$valid_username = 'user';
$valid_password = 'pass';

// Get the values from the form
$firstname = $_POST['fname'];
$lastname = $_POST['lname'];
$username = $_POST['uname'];
$password = $_POST['pwd'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo $hashed_password;


$sql = "INSERT into users(firstname, lastname, username, password) values('$firstname','$lastname','$username','$hashed_password')";


$result = mysqli_query($conn, $sql);
if($result){
	echo $firstname. " is registred succesfully!";
}
header("Location: index.html");
exit; 

$conn->close();


?>
