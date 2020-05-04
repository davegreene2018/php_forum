<?php
session_start();
// Set Session data to an empty array
$_SESSION = array();
// Expire their cookie files
if(isset($_COOKIE["user_id"]) && isset($_COOKIE["username"])) {
	setcookie("id", '', strtotime( '-5 days' ), '/');
    setcookie("user", '', strtotime( '-5 days' ), '/');
	setcookie("pass", '', strtotime( '-5 days' ), '/');
}
// Destroy the session variables
session_destroy();
// Double check to see if their sessions exists
if(isset($_SESSION['username'])){
	header("location: register.php");
} else {
	header("location: login.php");
	exit();
} 
?>