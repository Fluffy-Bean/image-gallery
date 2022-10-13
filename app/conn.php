<?php
/*
	Connect to database

	Make sure to enter your correct database details,
	else it may cause issues with loading the page
*/

$conn_ip = "192.168.0.79:3306";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "gallery";

try {
	$conn = @mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
} catch (Exception $e) {
	die("Unable to connect to the database");
}

session_start();
