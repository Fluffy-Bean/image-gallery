<?php
/*
  Connect to database

  In the future I want this section to be configurable, but that'll require some work to be done.
  For now it's hard-coded, shouldn't be an issue as most people wont be changing this often anyway
*/
$conn_ip = "192.168.0.79:3306";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "gallery";

/*
$conn_ip = $database['ip'].":".$database['port'];
$conn_username = $database['username'];
$conn_password = $database['password'];
$conn_database = $database['database'];

echo $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']);
echo $_SERVER['PHP_SELF'];
*/

$conn = mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
if ($conn->connect_error) {
  ?>
    <script>
      sniffleAdd('Error','Could not make a connection to the server, please try again later','var(--red)','assets/icons/warning.svg');
    </script>
  <?php
}

/*
  Start session

  This is important as most pages use the PHP session and will complain if its not possible to access.
*/
session_start();
