<?php
/*
	Connect to database

	Dunno what else to put here lol
*/
try {
	$conn_ip = "192.168.0.79:3306";
	$conn_username = "uwu";
	$conn_password = "fennec621";
	$conn_database = "gallery";

	$conn = @mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
} catch (Exception $e) {
	header("location: error.php?e=conn");
}

session_start();
