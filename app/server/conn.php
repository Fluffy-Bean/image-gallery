<?php
/*
  Connect to database

  In the future I want this section to be configurable, but that'll require some work to be done.
  For now it's hard-coded, shouldn't be an issue as most people wont be changing this often anyway
*/
// Setting up connection variables
$conn_ip = "192.168.0.79:3306";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "gallery";

$conn = mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
if ($conn->connect_error) {
  echo "<script>sniffleAdd('Error','Could not make a connection to the server, please try again later','var(--red)','".$root_dir."../../assets/icons/warning.svg')</script>";
}
