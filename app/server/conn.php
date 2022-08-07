<?php
/*
  Connect to database

  In the future I want this section to be configurable, but that'll require some work to be done.
  For now it's hard-coded, shouldn't be an issue as most people wont be changing this often anyway
*/
// Setting up connection variables
$conn_ip = "localhost";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "swag";

$conn = mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
if ($conn->connect_error) {
  // Send notification that connection couldn't be made
}
